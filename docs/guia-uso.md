# Guía de uso

Manual de **Club de lectura** para quien va a usar la aplicación: primero el recorrido de un usuario
normal y después el de la cuenta de administrador. No hace falta saber nada de programación para
seguirla.

---

## Índice

1. [Entrar en la aplicación](#1-entrar-en-la-aplicación)
2. [Crear una cuenta y acceder](#2-crear-una-cuenta-y-acceder)
3. [Buscar libros en el catálogo](#3-buscar-libros-en-el-catálogo)
4. [Ver el detalle de un libro](#4-ver-el-detalle-de-un-libro)
5. [Escribir, editar y borrar reseñas](#5-escribir-editar-y-borrar-reseñas)
6. [Tu lista de lecturas](#6-tu-lista-de-lecturas)
7. [Proponer un libro nuevo](#7-proponer-un-libro-nuevo)
8. [Mis libros: el estado de tus envíos](#8-mis-libros-el-estado-de-tus-envíos)
9. [Tu perfil](#9-tu-perfil)
10. [Cambiar el tema claro u oscuro](#10-cambiar-el-tema-claro-u-oscuro)
11. [Moderación (administrador)](#11-moderación-administrador)
12. [Preguntas frecuentes](#12-preguntas-frecuentes)

---

## 1. Entrar en la aplicación

Abre en el navegador:

```
http://localhost/club-de-lectura/
```

Lo primero que verás es el **catálogo**: los libros publicados, cada uno en una tarjeta con su
portada, título, autor, género y la media de estrellas.

Sin cuenta puedes mirar el catálogo, buscar y leer el detalle y las reseñas de cualquier libro. Para
escribir reseñas, llevar tu lista o proponer libros necesitas registrarte.

> Captura: `docs/img/listado.png`

---

## 2. Crear una cuenta y acceder

### Registro

1. Pulsa **Registrarse** en el menú.
2. Rellena nombre, correo, contraseña y confirmación.
3. Envía el formulario.

El correo no puede estar repetido: si ya existe una cuenta con él, aparece un aviso debajo del
campo. Los errores se señalan junto al campo que los provoca, sin perder lo que ya habías escrito.

### Iniciar sesión

Pulsa **Entrar**, escribe tu correo y tu contraseña. Si los datos no coinciden, el mensaje no dice
cuál de los dos falla, que es lo correcto para no dar pistas a quien intente adivinar cuentas.

Al entrar, el menú cambia y aparecen tus secciones: Mi lista de lecturas, Mis libros, Añadir libro
y Perfil.

### Cerrar sesión

**Cerrar sesión** en el menú. Conviene hacerlo si estás en un ordenador compartido, como el del
aula.

> Capturas: `docs/img/registro.png`, `docs/img/login.png`

---

## 3. Buscar libros en el catálogo

En la parte superior del catálogo tienes el buscador y los filtros:

| Herramienta | Qué hace |
|---|---|
| Buscador | Busca por **título o autor**; basta con una parte de la palabra |
| Filtro de género | Muestra solo los libros de un género |
| Filtro de autor | Muestra solo los libros de un autor |

Los filtros se pueden combinar con la búsqueda. La dirección del navegador guarda lo que has
buscado, así que puedes **copiar la URL del resultado** y recuperarlo más tarde o pasárselo a
alguien.

Para volver al catálogo completo, vacía el buscador y envía, o pulsa de nuevo **Catálogo** en el
menú.

> Si un libro no aparece aunque sepas que alguien lo ha enviado, es que todavía está pendiente de
> aprobación. Solo se ven los libros publicados.

---

## 4. Ver el detalle de un libro

Pulsa sobre una tarjeta. En el detalle encontrarás:

- Portada, título, autor, ISBN, género y descripción.
- **Media de estrellas** y el número de reseñas en las que se basa. Una media de 5 con una sola
  reseña no significa lo mismo que una media de 4,2 con veinte: por eso se muestran los dos datos.
- Las **reseñas** de otros usuarios, con quién las escribió y cuándo.
- Si tienes la sesión abierta: el botón para **añadir el libro a tu lista** y el formulario para
  **escribir tu reseña**.

> Captura: `docs/img/detalle.png`

---

## 5. Escribir, editar y borrar reseñas

### Escribir

En el detalle del libro, en el formulario de reseña:

1. Elige una **valoración de 1 a 5 estrellas**.
2. Escribe tu **texto**.
3. Pulsa enviar.

La página se recarga y tu reseña aparece ya en la lista, con la media recalculada.

**Puedes escribir una sola reseña por libro.** Si ya tienes una, en vez del formulario verás tu
reseña con las opciones de editarla o borrarla.

### Editar

Pulsa **Editar** en tu reseña, cambia la valoración o el texto y guarda. La media se vuelve a
calcular con el nuevo valor.

### Borrar

Pulsa **Borrar** y confirma. La reseña desaparece y la media se ajusta.


---

## 6. Tu lista de lecturas

Tu lista personal lleva la cuenta de lo que quieres leer, lo que estás leyendo y lo que ya has
leído.

### Añadir un libro

Desde el detalle de cualquier libro publicado, pulsa **Añadir a mi lista**. Entra con el estado
*quiero leer*.

### Gestionar la lista

En **Mi lista de lecturas** los libros aparecen agrupados por estado. En cada uno puedes:

| Acción | Resultado |
|---|---|
| Cambiar estado | Pasa a *quiero leer*, *leyendo* o *leído* |
| Quitar | Sale de tu lista (no borra el libro del catálogo ni tu reseña) |

**Un libro no puede estar dos veces en tu lista.** Si ya lo tienes, el botón de añadir te lo indica
en vez de duplicarlo.

> Captura: `docs/img/lista-lecturas.png`

---

## 7. Proponer un libro nuevo

Cualquier usuario con sesión puede proponer un libro, pero **no se publica directamente**: queda
pendiente hasta que el administrador lo revisa. Así el catálogo mantiene una calidad mínima.

1. Pulsa **Añadir libro** en el menú.
2. Rellena el formulario:

| Campo | Qué poner |
|---|---|
| Título | Obligatorio |
| Autor | Obligatorio |
| ISBN | Obligatorio y único: no puede estar ya en el catálogo |
| Género | Elígelo de la lista |
| Descripción | Breve resumen del libro |

3. Envía. Verás un mensaje confirmando que el libro ha quedado **pendiente de revisión**.

No hay que subir ninguna portada: se genera automáticamente a partir del género, con el título y el
autor.

> Captura: `docs/img/formulario-libro.png`

---

## 8. Mis libros: el estado de tus envíos

En **Mis libros** están todos los libros que has propuesto, con su estado:

| Estado | Qué significa |
|---|---|
| **Pendiente** | El administrador todavía no lo ha revisado. No aparece en el catálogo |
| **Publicado** | Aprobado: ya está en el catálogo y puede recibir reseñas |
| **Rechazado** | El administrador no lo ha aceptado. No aparece en el catálogo |

Es la página donde comprobar qué ha pasado con una propuesta, sin tener que preguntar a nadie.

> Captura: `docs/img/mis-libros.png`

---

## 9. Tu perfil

En **Perfil** se ven tus datos de cuenta y la lista de todas las reseñas que has escrito, con el
libro al que pertenecen y su valoración. Desde ahí puedes llegar a cualquiera de ellas para
editarla o borrarla.

> Captura: `docs/img/perfil.png`

---

## 10. Cambiar el tema claro u oscuro

En la cabecera hay un **conmutador de tema**. Al pulsarlo, toda la aplicación cambia entre claro y
oscuro.

La preferencia se guarda en una cookie del navegador, así que se mantiene al cambiar de página y al
volver otro día desde el mismo navegador. Si borras las cookies, vuelve al tema por defecto.

Esa cookie solo guarda la preferencia de aspecto: no contiene datos de tu cuenta.

---

## 11. Moderación (administrador)

El administrador es **una única cuenta**, con un correo concreto, y su trabajo es revisar los libros
que envían los usuarios. Sus credenciales de prueba están en
[instalacion.md](instalacion.md#7-credenciales-de-prueba).

### Qué ve el administrador

Al iniciar sesión, va **directamente a la página de moderación**. Su menú solo tiene Moderación y
Cerrar sesión: no navega por el catálogo ni escribe reseñas. Si intenta abrir cualquier otra página
de la aplicación, vuelve a moderación.

No es un usuario con permisos añadidos: es una cuenta con una sola función.

### La página de moderación

Tiene dos listas.

**Lista de pendientes** — libros enviados por usuarios, con todos sus datos para poder juzgarlos:

| Acción | Resultado |
|---|---|
| **Aprobar** | El libro pasa a *publicado*: aparece en el catálogo y admite reseñas y listas |
| **Rechazar** | El libro pasa a *rechazado*: no aparece en el catálogo |

En ambos casos, el usuario que lo envió ve el cambio en su página «Mis libros».

**Lista de publicados** — los libros que ya están en el catálogo:

| Acción | Resultado |
|---|---|
| **Editar** | Abre el mismo formulario del alta, con los datos cargados, para corregir erratas |
| **Borrar** | Elimina el libro del catálogo. **Pide confirmación antes** |

> Borrar un libro es definitivo y arrastra lo que cuelga de él. Antes de borrar, valora si basta con
> editarlo.

### Rutina recomendada

1. Entrar y revisar la lista de pendientes.
2. Aprobar los libros con los datos completos y correctos.
3. Rechazar los duplicados, los que vengan vacíos o los que no sean libros.
4. Repasar la lista de publicados y corregir erratas con **Editar**.
5. Cerrar sesión.

> Captura: `docs/img/moderacion.png`

---

## 12. Preguntas frecuentes

**He enviado un libro y no sale en el catálogo.**
Está pendiente de revisión. Compruébalo en «Mis libros»: aparecerá en el catálogo cuando el
administrador lo apruebe.

**No puedo escribir una segunda reseña del mismo libro.**
Es el comportamiento previsto: una reseña por usuario y libro. Edita la que ya tienes.

**No me deja registrarme con mi correo.**
Ya existe una cuenta con ese correo. Prueba a iniciar sesión.

**Me dice que el ISBN ya existe.**
Ese libro ya está en el catálogo, publicado o pendiente. Búscalo en el buscador.

**He añadido un libro a mi lista y quiero quitarlo.**
En «Mi lista de lecturas», pulsa **Quitar**. No borra el libro del catálogo ni tu reseña.

**¿Puedo subir la portada de un libro?**
No. Las portadas se generan automáticamente según el género, con el título y el autor.

**El tema oscuro no se mantiene.**
Se guarda en una cookie: si el navegador las bloquea o las borras al cerrar, vuelve al tema por
defecto.

**Soy administrador y no veo el catálogo.**
Es así por diseño. La cuenta de administrador solo accede a la página de moderación. Para ver la
aplicación como un lector, entra con una cuenta de usuario normal.