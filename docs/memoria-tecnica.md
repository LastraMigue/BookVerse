# Memoria técnica

Explicación de cómo está construido **Club de lectura**: la arquitectura elegida y por qué, el
recorrido completo de una petición, cómo se reparten el trabajo el cliente y el servidor, y las
medidas de seguridad.

Cada integrante escribe y defiende su parte:

| Apartado | Responsable |
|---|---|
| 1, 2, 3, 4 y 7 — Arquitectura, recorrido de la petición, modelo de datos y seguridad del servidor | Integrante 1 (DWS) |
| 5 — Reseñas, búsqueda y verificación de libros | Integrante 2 |
| 6 — Cliente frente a servidor y JavaScript | Integrante 4 (DWC) |

---

## 1. Arquitectura

La aplicación es una **web tradicional servida por PHP**: el navegador pide una URL, Apache pasa la
petición a PHP, PHP consulta MySQL y devuelve una página HTML ya completa. La navegación se hace
con enlaces y formularios GET/POST, y cada acción recarga la página.

```
Navegador  ──HTTP──▶  Apache  ──▶  PHP 8  ──PDO──▶  MySQL / MariaDB
    ▲                                 │
    └────────── HTML + CSS + JS ──────┘
```

### Por qué esta arquitectura y no otra

El enunciado excluye expresamente API REST propia, SPA y `fetch()` en el navegador, y la decisión
encaja con el proyecto por tres motivos:

- **El estado vive en el servidor.** Quién tiene la sesión abierta y si es administrador se
  comprueba en PHP en cada petición. Una SPA tendría que replicar esa lógica en el cliente, y lo
  que se comprueba en el cliente no protege nada.
- **La aplicación es de contenido, no de interacción continua.** Catálogo, detalle, reseñas y
  listas son páginas que se piden y se leen; no hay nada que exija actualizar fragmentos sin
  recargar.
- **Menos piezas que explicar y defender.** Un único lenguaje en el servidor, sin capa de API
  intermedia ni estado duplicado.

**Limitación asumida:** cada acción recarga la página entera, lo que da una experiencia menos fluida
que una SPA. Se compensa redirigiendo tras cada formulario y mostrando un mensaje claro en el
destino, de modo que el usuario siempre sepa qué ha pasado.

### Organización del código

```
index.php            Listado del catálogo (entrada de la aplicación)
libro.php            Detalle de un libro y sus reseñas
login.php            Inicio de sesión
registro.php         Alta de usuario
logout.php           Cierre de sesión
anadir-libro.php     Formulario de propuesta de libro
mis-libros.php       Envíos del usuario y su estado
lista.php            Lista de lecturas personal
perfil.php           Perfil del usuario con sus reseñas
moderacion.php       Página del administrador
includes/
  config.php         Credenciales locales (ignorado por Git)
  config.example.php Plantilla sin secretos
  conexion.php       Conexión PDO
  auth.php           Sesión, control de acceso y redirección por tipo de cuenta
  resenas.php        Consultas de reseñas y media
  lista.php          Consultas de la lista de lecturas
  header.php / nav.php / footer.php   Plantilla reutilizable
assets/css|js|img|media
docs/
```

El patrón es sencillo y repetido en todas las páginas: **incluir `auth.php` primero**, procesar el
formulario si lo hay, redirigir, consultar los datos y, por último, pintar el HTML.

---

## 2. Recorrido de una petición

Ejemplo completo: un usuario con sesión abierta envía una reseña desde el detalle de un libro.

1. **El navegador envía el formulario.** `POST /libro.php?id=12` con los campos `valoracion` y
   `texto`. Antes de salir, el JavaScript del cliente ya ha comprobado que el texto no está vacío y
   que la valoración está entre 1 y 5 (sección 6); si no, cancela el envío con `preventDefault()`.
2. **Apache recibe la petición** y, por la extensión `.php`, se la entrega al intérprete de PHP.
3. **PHP arranca la página.** Lo primero que se incluye es `includes/auth.php`, que llama a
   `session_start()`, comprueba que hay sesión y que el tipo de cuenta corresponde. Si no hay
   sesión, redirige al login y termina. Si quien pide la página es el administrador, lo redirige a
   moderación.
4. **Se detecta que la petición es POST** (`$_SERVER['REQUEST_METHOD']`) y se procesa el formulario:
   se recogen los datos con `$_POST`, se recortan y **se validan otra vez en el servidor** (la
   validación del cliente es comodidad, no seguridad).
5. **Se escribe en la base de datos** con una consulta preparada: `INSERT INTO resenas (libro_id,
   usuario_id, valoracion, texto, fecha) VALUES (?, ?, ?, ?, NOW())`. El `usuario_id` sale de la
   sesión, nunca del formulario. La restricción de una reseña por usuario y libro la garantiza la
   propia base de datos.
6. **Redirección.** Se envía `Location: libro.php?id=12&ok=resena` y `exit`. Así, si el usuario
   recarga, no se reenvía el POST (patrón POST–Redirect–GET).
7. **Nueva petición GET.** El navegador pide `libro.php?id=12&ok=resena`. Se repite el paso 3, se
   leen los datos del libro, sus reseñas y la media (`AVG` sobre `resenas`) con consultas preparadas
   y se arma el HTML con `header.php`, el contenido y `footer.php`.
8. **Salida escapada.** Todo lo que han escrito los usuarios (título, descripción, texto de la
   reseña, nombre) se imprime con `htmlspecialchars()`. Se muestra el mensaje de confirmación y la
   reseña recién publicada.
9. **El navegador pinta la página**, aplica el CSS y ejecuta el JavaScript: el conmutador de tema
   lee la cookie y los validadores se enganchan a los formularios.

La diferencia con una solución solo de cliente está en los pasos 3 a 6: la decisión de si esa
persona puede escribir esa reseña, y la escritura en sí, ocurren en un sitio donde el usuario no
puede intervenir.

---

## 3. Modelo de datos

| Tabla | Campos | Notas |
|---|---|---|
| `usuarios` | id, nombre, email, password_hash, es_admin, fecha_registro | `email` único; `es_admin` nunca se activa desde el registro |
| `generos` | id, nombre | Catálogo cerrado |
| `libros` | id, titulo, autor, isbn, descripcion, genero_id, creado_por, estado, fecha_envio | `estado`: pendiente, publicado o rechazado; `isbn` único |
| `resenas` | id, libro_id, usuario_id, valoracion, texto, fecha | Única por usuario y libro; `valoracion` de 1 a 5 |
| `lista_lecturas` | usuario_id, libro_id, estado, fecha | Clave compuesta; `estado`: quiero leer, leyendo, leído |

**Relaciones:** un género tiene muchos libros; un libro tiene muchas reseñas; un usuario escribe
muchas reseñas y tiene muchos libros en su lista. La entidad principal es `libros` y la relacionada,
`resenas`.

Decisiones que conviene justificar en la defensa:

- **El estado del libro es un campo de `libros`, no una tabla aparte.** Son tres valores fijos y
  excluyentes; una tabla de estados añadiría un JOIN sin aportar nada.
- **La media no se guarda**, se calcula con `AVG` sobre `resenas`. Guardarla obligaría a
  recalcularla en cada alta, edición y borrado de reseña, con riesgo de quedar desincronizada.
- **La lista de lecturas usa clave compuesta** (`usuario_id`, `libro_id`): es lo que impide que un
  libro entre dos veces en la misma lista, y lo impide la base de datos, no el código.
- **Solo los libros publicados** aparecen en el catálogo, el buscador y el detalle, y solo ellos
  admiten reseñas y entrar en listas.

El diagrama está en [`docs/img/`](img/) y el script completo en
[`docs/sql/schema.sql`](sql/schema.sql).

---

## 4. Sesiones y control de acceso

Todo el control está en **`includes/auth.php`**, que se incluye al principio de cada página. Que la
comprobación esté en un único archivo es deliberado: así no depende de que alguien se acuerde de
copiarla.

- `session_start()` y `session_regenerate_id()` al iniciar sesión, para evitar la fijación de sesión.
- Funciones para **exigir sesión** y para **exigir administrador**; cada página llama a la que le
  corresponde.
- **Redirección según el tipo de cuenta:** sin sesión, cualquier página restringida lleva al login;
  un usuario normal que pida moderación vuelve al listado; el administrador que pida cualquier otra
  página vuelve a moderación.
- Las contraseñas se guardan con `password_hash()` y se comprueban con `password_verify()`. En
  ningún momento se almacenan ni se envían en claro.
- El administrador es **una única cuenta**, creada en `schema.sql` con `es_admin` activado. El
  formulario de registro nunca activa ese campo y el correo es único, así que no se puede conseguir
  ese permiso registrándose.

Ocultar un botón del menú no es protección: cada página y cada acción vuelven a comprobar en el
servidor quién es la persona que la pide.

---

## 5. Reseñas, búsqueda y verificación de libros

*(Integrante 2)*

### Reseñas y valoración

Las consultas viven en `includes/resenas.php`, todas preparadas:

- Listar las reseñas de un libro con el nombre de su autor (`JOIN` con `usuarios`).
- Insertar una reseña tomando el `usuario_id` de la sesión.
- Calcular la media con `AVG(valoracion)` y el número de reseñas, para mostrar ambos datos juntos.
- Editar y borrar **solo las propias**: la consulta lleva en el `WHERE` tanto el id de la reseña
  como el `usuario_id` de la sesión, de modo que cambiar el id en la URL no permite tocar la reseña
  de otro.

La restricción de una reseña por usuario y libro está en la base de datos; el código la acompaña
con un mensaje claro cuando ya existe.

### Buscador y filtros

El formulario de búsqueda se envía por **GET**, para que la URL del resultado se pueda compartir y
guardar. La consulta se monta con `LIKE` sobre título y autor y con filtros opcionales por género y
autor, siempre con marcadores de posición:

```sql
SELECT l.*, AVG(r.valoracion) AS media
FROM libros l
LEFT JOIN resenas r ON r.libro_id = l.id
WHERE l.estado = 'publicado'
  AND (l.titulo LIKE ? OR l.autor LIKE ?)
  AND (? IS NULL OR l.genero_id = ?)
GROUP BY l.id
```

El `LEFT JOIN` es necesario para que los libros sin reseñas sigan apareciendo en el listado. El
filtro `estado = 'publicado'` está en todas las consultas públicas: un libro pendiente no se ve ni
buscándolo por su título exacto.

### Verificación de libros

Es el flujo que distingue a este proyecto y conviene saber explicarlo entero:

1. Cualquier usuario con sesión envía un libro desde el formulario de alta; se guarda con estado
   **pendiente** y con `creado_por` igual a su id.
2. En **«Mis libros»** el usuario ve sus envíos con su estado: pendiente, publicado o rechazado.
3. La **página de moderación** tiene dos listas. En la de pendientes, el administrador aprueba
   (pasa a *publicado*) o rechaza (pasa a *rechazado*). En la de publicados puede editar un libro
   con el mismo formulario del alta o borrarlo con confirmación.
4. Aprobar, rechazar, editar y borrar se hacen con **formularios POST** y consultas preparadas, y
   cada acción vuelve a comprobar en el servidor que quien la pide es el administrador.

---

## 6. Cliente frente a servidor

El reparto es explícito: **el cliente mejora la experiencia, el servidor decide**.

| Tarea | Cliente (JavaScript) | Servidor (PHP) |
|---|---|---|
| Validar un formulario | Sí, avisa antes de enviar | Sí, y es la que vale |
| Decidir si se guarda algo | No | Sí |
| Comprobar la sesión y los permisos | No | Sí |
| Cambiar el aspecto (tema) | Sí | No |
| Generar el HTML | No | Sí |

### Qué hace el JavaScript

- **Validación de los formularios** de registro, reseña y alta de libro con expresiones regulares y
  el evento `submit`: formato del correo, campos obligatorios, longitud del texto, ISBN y
  valoración dentro de rango. Si algo falla, `preventDefault()` y mensaje junto al campo.
- Un **objeto propio** (un validador de formularios) con propiedades y métodos, en vez de funciones
  sueltas: agrupa las reglas y permite reutilizarlas en los tres formularios.
- **`document` y `window`** para generar y modificar elementos: pintar los mensajes de error,
  marcar los campos, dibujar las estrellas interactivas y alternar el tema.
- **Cookie** con la preferencia de tema claro/oscuro. Es una preferencia no sensible; en la cookie
  no va nunca nada relacionado con credenciales.
- **Controles de audio y vídeo** y la animación a partir de imágenes fijas.

La validación del cliente se puede saltar desactivando JavaScript o enviando la petición a mano.
Por eso **todo lo que valida el cliente se vuelve a validar en el servidor**, y las comprobaciones
del servidor son las únicas que deciden si el dato se guarda.

---

## 7. Seguridad

| Riesgo | Medida |
|---|---|
| Inyección SQL | **Consultas preparadas** con PDO en todas las consultas, sin concatenar nunca datos del usuario |
| XSS | `htmlspecialchars()` al imprimir cualquier contenido escrito por usuarios |
| Contraseñas comprometidas | `password_hash()` y `password_verify()`; nunca en claro, nunca en la cookie |
| Fijación de sesión | `session_regenerate_id()` al iniciar sesión |
| Acceso a páginas restringidas | Comprobación de sesión y de tipo de cuenta en `auth.php`, al principio de cada página |
| Manipulación de ids en la URL | Las consultas de editar y borrar llevan el `usuario_id` de la sesión en el `WHERE` |
| Escalada de privilegios | `es_admin` solo se activa en `schema.sql`; el registro nunca lo toca |
| Reenvío de formularios | Redirección tras cada POST (POST–Redirect–GET) |
| Credenciales en el repositorio | `config.php` ignorado por Git; se sube `config.example.php` sin secretos |
| Datos reales expuestos | Todas las cuentas y libros del `schema.sql` son ficticios |
| Archivos subidos | No se suben portadas: se generan con CSS, así que no hay superficie de subida de archivos |

### Conexión PDO

```php
$pdo = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
    DB_USER,
    DB_PASS,
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);
```

`ATTR_EMULATE_PREPARES` a `false` hace que las consultas las prepare realmente el motor de la base
de datos, no una emulación de PHP. Las excepciones se capturan y se muestra un mensaje genérico:
los detalles del error nunca llegan al navegador.

---

## 8. Limitaciones conocidas

- Sin API propia: otra aplicación no puede consumir los datos sin pasar por el HTML.
- Cada acción recarga la página completa.
- Las portadas son generadas, no reales; se gana en simplicidad y licencias, se pierde en aspecto.
- Un único administrador, sin roles intermedios ni delegación de la moderación.
- Fuera de alcance: préstamos, mensajería, comentarios sobre reseñas y subida de portadas.