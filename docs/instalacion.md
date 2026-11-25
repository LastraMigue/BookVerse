# Instalación y despliegue

Guía para poner en marcha **Club de lectura** desde una copia limpia del repositorio, en un
equipo de desarrollo con XAMPP. Si al seguir estos pasos algo no funciona, abre una issue con la
etiqueta `docs` indicando el paso y el mensaje de error.

---

## 1. Requisitos

| Componente | Versión mínima | Notas |
|---|---|---|
| XAMPP | 8.2 | Incluye Apache, PHP y MariaDB/MySQL |
| PHP | 8.1 | Con las extensiones `pdo_mysql` y `mbstring` activadas |
| MySQL / MariaDB | 10.4 (MariaDB) o 8.0 (MySQL) | El incluido en XAMPP sirve |
| Apache | 2.4 | Con `mod_rewrite` (no imprescindible, pero recomendado) |
| Navegador | Chrome / Firefox actualizados | Las pruebas se hacen en dos navegadores |
| Git | 2.30 | Para clonar el repositorio |

Anotad aquí la versión exacta de XAMPP que usa el equipo, para que todos trabajemos sobre lo mismo:

- Versión usada por el equipo: `XAMPP 8.2.x` *(confirmar en la reunión de arranque)*

---

## 2. Obtener el código

Clona el repositorio **dentro de la carpeta pública de Apache** (`htdocs`):

```bash
cd C:\xampp\htdocs          # Windows
# cd /opt/lampp/htdocs      # Linux
# cd /Applications/XAMPP/htdocs   # macOS

git clone <URL-DEL-REPOSITORIO> club-de-lectura
cd club-de-lectura
git checkout develop
```

La rama `main` es la estable y entregable; `develop` es la de integración y es la que se usa
durante el desarrollo.

### Estructura de carpetas

```
club-de-lectura/
├── index.php
├── includes/        # conexion.php, auth.php, header.php, nav.php, footer.php, consultas
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── media/       # audio, vídeo y animación
├── docs/
│   ├── img/         # capturas y diagramas
│   └── sql/
│       └── schema.sql
└── README.md
```

---

## 3. Arrancar Apache y MySQL

1. Abre el **Panel de control de XAMPP**.
2. Pulsa **Start** en **Apache** y en **MySQL**.
3. Comprueba que ambos quedan en verde. Si Apache no arranca, suele ser que el puerto 80 está
   ocupado (Skype, IIS u otro servidor): cámbialo en `httpd.conf` a 8080 y usa
   `http://localhost:8080/...` en todas las URL de esta guía.

---

## 4. Crear la base de datos

El script `docs/sql/schema.sql` crea la base de datos, las tablas, las claves y las restricciones,
e inserta datos ficticios suficientes para probar todos los flujos (géneros, entre 15 y 20 libros
publicados, algún libro pendiente, la cuenta de administrador, una cuenta de usuario y algunas
reseñas).

### Opción A — phpMyAdmin

1. Entra en `http://localhost/phpmyadmin`.
2. Pestaña **Importar** → **Seleccionar archivo** → `docs/sql/schema.sql`.
3. **Continuar**. No debe aparecer ningún error.

### Opción B — línea de comandos

```bash
cd C:\xampp\mysql\bin
mysql -u root -p < C:\xampp\htdocs\club-de-lectura\docs\sql\schema.sql
```

En una instalación de XAMPP recién hecha el usuario `root` no tiene contraseña: basta con pulsar
Intro cuando la pida.

> El script se puede volver a ejecutar sobre una base vacía tantas veces como haga falta. Si
> quieres partir de cero, borra antes la base de datos desde phpMyAdmin.

---

## 5. Configurar la conexión

En el repositorio solo está `config.example.php`, **sin credenciales**. Cada uno crea su copia
local, que Git ignora:

```bash
cp includes/config.example.php includes/config.php     # Linux / macOS
copy includes\config.example.php includes\config.php   # Windows
```

Edita `includes/config.php` con los datos de tu equipo:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'club_de_lectura');
define('DB_USER', 'root');
define('DB_PASS', '');          // vacía en XAMPP por defecto
define('DB_CHARSET', 'utf8mb4');
```

**Nunca** subas `config.php` al repositorio: está en `.gitignore` y no debe salir de ahí.

---

## 6. Abrir la aplicación

Con Apache y MySQL en marcha:

```
http://localhost/club-de-lectura/
```

(o `http://localhost:8080/club-de-lectura/` si cambiaste el puerto).

Debe cargarse el listado del catálogo con los libros publicados del `schema.sql`.

---

## 7. Credenciales de prueba

Las cuentas del `schema.sql` son **ficticias**, creadas solo para probar la aplicación. Los correos
no existen y las contraseñas están guardadas con `password_hash`.

| Tipo de cuenta | Correo | Contraseña | Qué ve |
|---|---|---|---|
| Administrador | *(pendiente de decidir — sección 12 del documento de organización)* | *(pendiente)* | Solo la página de moderación y el cierre de sesión |
| Usuario normal | *(pendiente)* | *(pendiente)* | Catálogo, detalle, reseñas, lista de lecturas, «Mis libros» y perfil |

> Completar esta tabla en cuanto el equipo fije el correo ficticio del administrador y su
> contraseña de prueba. Las credenciales deben coincidir exactamente con las del `schema.sql`.

La cuenta de administrador **no se puede conseguir registrándose**: el formulario de registro nunca
activa `es_admin` y el correo es único en la base de datos.

---

## 8. Verificación de la instalación

Recorrido mínimo para dar la instalación por buena. Hazlo con una copia limpia, en este orden:

1. **Catálogo** — `http://localhost/club-de-lectura/` muestra los libros publicados, con su portada
   generada por CSS (color según el género, con título y autor).
2. **Detalle** — al abrir un libro se ven sus datos, la media de estrellas y sus reseñas.
3. **Registro** — crea un usuario nuevo; los mensajes de error son claros y el correo repetido se
   rechaza.
4. **Login / logout** — entra y sal con el usuario de prueba.
5. **Reseña** — escribe una reseña con valoración; aparece en el detalle y recalcula la media. No
   se puede enviar vacía ni con una valoración fuera de 1–5.
6. **Lista de lecturas** — añade un libro, cambia su estado (quiero leer / leyendo / leído) y
   quítalo. El mismo libro no se puede añadir dos veces.
7. **Alta de libro** — envía un libro desde el formulario; queda **pendiente** y no aparece en el
   catálogo. En «Mis libros» se ve su estado.
8. **Moderación** — entra con el administrador: va directo a la página de moderación. Aprueba el
   libro pendiente (pasa a publicado y aparece en el catálogo) y rechaza otro.
9. **Control de acceso** — con el usuario normal, escribe a mano la dirección de la página de
   moderación: el servidor redirige al listado. Con el administrador, abre cualquier otra página:
   redirige a moderación. Sin sesión, cualquier página restringida redirige al login.
10. **Multimedia y tema** — las imágenes, el audio y el vídeo cargan con sus controles, y el
    conmutador de tema claro/oscuro mantiene la preferencia al recargar (cookie).

Si los diez pasos funcionan desde una copia limpia, la instalación está verificada.

---

## 9. Problemas frecuentes

| Síntoma | Causa probable | Solución |
|---|---|---|
| `Access denied for user 'root'@'localhost'` | Usuario o contraseña mal en `config.php` | Revisa `DB_USER` y `DB_PASS` |
| `could not find driver` | `pdo_mysql` desactivado | Descomenta `extension=pdo_mysql` en `php.ini` y reinicia Apache |
| Página en blanco | Error de PHP sin mostrar | Mira `xampp/apache/logs/error.log`; activa `display_errors` solo en local |
| Apache no arranca | Puerto 80 ocupado | Cambia a 8080 en `httpd.conf` |
| Acentos como `Ã±` | Juego de caracteres | Comprueba `utf8mb4` en `config.php`, en la conexión PDO y en el `<meta charset>` |
| Tablas que no existen | El `schema.sql` no se importó entero | Borra la base de datos y vuelve a importarlo |
| El catálogo sale vacío | Los libros están en estado pendiente | Apruébalos desde moderación o reimporta el `schema.sql` |

---

## 10. Despliegue en otro equipo

El proceso es el mismo: clonar, importar `docs/sql/schema.sql`, copiar `config.example.php` a
`config.php` con los datos de ese equipo y comprobar el recorrido de la sección 8. No hay nada
instalado fuera de la carpeta del proyecto ni archivos subidos por los usuarios: las portadas se
generan con CSS y los recursos multimedia están en `assets/media/`.

