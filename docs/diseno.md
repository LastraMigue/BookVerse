# Diseño e interfaz

Documento de diseño de **Club de lectura**: a quién va dirigida la aplicación, cómo se navega por
ella, qué decisiones de presentación se han tomado y la guía de estilo (paleta, tipografías y
componentes) que después se traduce a CSS.

Responsable: **Integrante 3 — Interfaz y diseño (DIW)**.
Los wireframes y la maqueta de alta fidelidad viven en Figma; las exportaciones y capturas están en
[`docs/img/`](img/).

- Enlace de solo lectura al Figma del equipo: *(pendiente — issue I3-3)*

---

## 1. Público objetivo

Personas aficionadas a la lectura que quieren llevar la cuenta de lo que leen y ver qué opinan
otras de un libro antes de empezarlo. No se presupone ningún conocimiento técnico.

| Perfil | Qué busca | Qué implica para el diseño |
|---|---|---|
| Lector habitual | Añadir libros a su lista, cambiar estados y escribir reseñas rápido | Acciones a un clic desde el detalle; estados visibles sin entrar en menús |
| Lector ocasional | Descubrir qué leer: buscar, filtrar y mirar valoraciones | Buscador visible en el listado; estrellas y media legibles de un vistazo |
| Usuario que propone libros | Enviar un libro y saber en qué estado está su envío | Formulario de alta claro y página «Mis libros» con el estado de cada envío |
| Administrador | Revisar lo pendiente y publicarlo o rechazarlo | Página de moderación sin distracciones: dos listas y acciones explícitas |

**Contexto de uso:** sobre todo móvil para consultar y escritorio para escribir reseñas o moderar.
El diseño se trabaja en las dos anchuras desde el principio, no solo al final.

---

## 2. Navegación

La navegación es tradicional: enlaces y formularios GET/POST, con las páginas generadas por el
servidor. No hay SPA ni carga parcial con `fetch()`.

### Menú según el tipo de cuenta

El `nav.php` cambia según quién tenga la sesión abierta:

| Sin sesión | Usuario con sesión | Administrador |
|---|---|---|
| Catálogo | Catálogo | Moderación |
| Entrar | Mi lista de lecturas | Cerrar sesión |
| Registrarse | Mis libros | |
| | Añadir libro | |
| | Perfil | |
| | Cerrar sesión | |

El administrador **solo** ve Moderación y Cerrar sesión: no es un usuario con permisos añadidos,
es una cuenta con una única función. Ocultar enlaces no es la protección: el servidor comprueba la
sesión y el tipo de cuenta en cada página y redirige cuando no corresponde.

### Recorridos principales

```
Catálogo ──▶ Detalle del libro ──▶ Escribir reseña ──▶ Detalle (con la reseña publicada)
    │              │
    │              └──▶ Añadir a mi lista ──▶ Mi lista de lecturas (cambiar estado / quitar)
    │
    └──▶ Buscador y filtros ──▶ Catálogo filtrado

Añadir libro ──▶ Mis libros (pendiente) ──▶ [el administrador aprueba] ──▶ Catálogo

Login del administrador ──▶ Moderación ──▶ Aprobar / Rechazar / Editar / Borrar
```

Tras cada formulario que guarda algo hay una redirección, para que al recargar no se repita el
envío, y un mensaje de confirmación o de error en la página de destino.

---

## 3. Vistas y wireframes

Las vistas principales se bocetan primero en baja fidelidad (issue I3-1) y después se maquetan en
alta fidelidad (issue I3-3), siempre en móvil y escritorio.

| Vista | Contenido | Captura |
|---|---|---|
| Listado / catálogo | Buscador, filtros por género y autor, rejilla de tarjetas de libro | `docs/img/listado.png` |
| Detalle del libro | Portada, datos, media de estrellas, botón de lista, reseñas y formulario | `docs/img/detalle.png` |
| Login | Correo, contraseña y enlace a registro | `docs/img/login.png` |
| Registro | Nombre, correo, contraseña y confirmación | `docs/img/registro.png` |
| Formulario de libro | Título, autor, ISBN, género y descripción | `docs/img/formulario-libro.png` |
| Mis libros | Envíos del usuario con su estado (pendiente, publicado, rechazado) | `docs/img/mis-libros.png` |
| Mi lista de lecturas | Libros agrupados por estado, con cambiar estado y quitar | `docs/img/lista-lecturas.png` |
| Perfil | Datos del usuario y sus reseñas | `docs/img/perfil.png` |
| Moderación | Lista de pendientes (aprobar / rechazar) y lista de publicados (editar / borrar) | `docs/img/moderacion.png` |

*(Las capturas se exportan a `docs/img/` conforme se cierran las issues de maquetación.)*

### Plantilla reutilizable

Todas las vistas se montan sobre la misma plantilla, con includes de PHP coordinados con el
Integrante 1:

- `header.php` — `<head>`, hojas de estilo, conmutador de tema.
- `nav.php` — menú según el tipo de cuenta.
- `footer.php` — créditos y enlaces.

---

## 4. Decisiones de presentación de la información

- **Portadas generadas con CSS.** No se suben imágenes de portada: cada tarjeta dibuja un
  rectángulo con un color asignado por género y encima el título y el autor. Así no hay que
  gestionar archivos de usuarios ni licencias de portadas, y el catálogo se ve uniforme.
- **Tarjetas en rejilla** para el listado: una columna en móvil, varias en escritorio. Cada tarjeta
  muestra portada, título, autor, género y media de estrellas.
- **Valoración con estrellas** de 1 a 5. En el detalle se muestra la media (calculada con `AVG`)
  junto al número de reseñas, para que una sola reseña de 5 no se lea como un consenso.
- **Estados con etiquetas de color** y, además, con texto: pendiente, publicado, rechazado; quiero
  leer, leyendo, leído. El color nunca es el único portador del significado.
- **Mensajes claros** de confirmación y de error, en la misma página de destino tras la
  redirección, con un estilo propio y diferenciado entre éxito y error.
- **Todo el texto que escriben los usuarios se escapa al mostrarlo.** Las decisiones de diseño no
  pueden dar por bueno un contenido sin escapar.
- **Jerarquía tipográfica** sencilla: un solo `h1` por página, `h2` para las secciones y texto base
  con una medida de línea cómoda (alrededor de 65 caracteres).

---

## 5. Guía de estilo

### Paleta cromática

*(Pendiente de decidir en el equipo — sección 12 del documento de organización, issue I3-2.
Requisito: contraste suficiente, comprobado con un medidor de contraste, mínimo AA.)*

| Uso | Variable CSS | Tema claro | Tema oscuro |
|---|---|---|---|
| Fondo | `--color-fondo` | *(pendiente)* | *(pendiente)* |
| Superficie / tarjeta | `--color-superficie` | *(pendiente)* | *(pendiente)* |
| Texto principal | `--color-texto` | *(pendiente)* | *(pendiente)* |
| Texto secundario | `--color-texto-suave` | *(pendiente)* | *(pendiente)* |
| Color de marca | `--color-principal` | *(pendiente)* | *(pendiente)* |
| Acento / foco | `--color-acento` | *(pendiente)* | *(pendiente)* |
| Éxito | `--color-exito` | *(pendiente)* | *(pendiente)* |
| Error | `--color-error` | *(pendiente)* | *(pendiente)* |
| Estrellas | `--color-estrella` | *(pendiente)* | *(pendiente)* |

Además, un color por género para las portadas generadas, elegido dentro de la misma paleta y con
contraste suficiente frente al texto del título.

### Tipografías

*(Pendiente — dos tipografías con licencia abierta, por ejemplo de Google Fonts: una para títulos y
otra para texto corrido.)*

| Uso | Familia | Pesos |
|---|---|---|
| Títulos | *(pendiente)* | *(pendiente)* |
| Texto | *(pendiente)* | *(pendiente)* |

### Espaciado y escala

Escala de espaciado basada en múltiplos de `0.25rem` (4, 8, 16, 24, 32, 48 px) en variables CSS,
para que los márgenes sean coherentes entre vistas.

### Componentes

- **Botones:** primario, secundario y de peligro (borrar). Estados de reposo, `:hover`, `:focus-visible`
  y activo, con transiciones sutiles.
- **Campos de formulario:** etiqueta siempre visible, estado de foco marcado y estado de error con
  mensaje debajo del campo.
- **Tarjeta de libro:** portada CSS, título, autor, género y estrellas.
- **Estrellas:** versión de solo lectura (media) y versión interactiva (formulario de reseña).
- **Etiqueta de estado:** fondo suave y texto.
- **Mensajes:** confirmación y error.

---

## 6. Responsive

Enfoque *mobile first*: los estilos base son los de móvil y los puntos de ruptura añaden columnas.

| Punto de ruptura | Anchura | Cambios |
|---|---|---|
| Móvil | hasta 575 px | Una columna; menú plegado; tarjetas a ancho completo |
| Tablet | 576–991 px | Dos columnas en el catálogo; filtros en línea |
| Escritorio | 992 px en adelante | Tres o cuatro columnas; menú desplegado; detalle a dos columnas |

Se usa **Bootstrap** para la rejilla y los componentes base, más un CSS propio externo con las
clases reutilizables del proyecto. SCSS es opcional: si se entrega, va junto con el CSS compilado.

Comprobación obligatoria en dos navegadores y en móvil, con capturas en `docs/img/` (issue del
Sprint 3).

---

## 7. Tema claro y oscuro

Hay un conmutador de tema en la cabecera. La preferencia se guarda en una **cookie** (una
preferencia no sensible; nunca credenciales) y se aplica al cargar la página.

- Implementación: las variables CSS de color se redefinen bajo un atributo en `<html>`
  (por ejemplo `data-tema="oscuro"`), y el JavaScript solo cambia ese atributo y escribe la cookie.
- Los dos temas deben cumplir el contraste mínimo, no solo el claro.

---

## 8. Accesibilidad

- Contraste AA en ambos temas.
- Foco visible en todo lo que se pueda enfocar (`:focus-visible`), nunca `outline: none` sin
  sustituto.
- HTML semántico: `header`, `nav`, `main`, `footer`, listas para las listas y encabezados en orden.
- Todas las imágenes con `alt`; las decorativas con `alt=""`.
- Formularios con `label` asociado a cada campo, no solo `placeholder`.
- El color no es nunca el único indicador: los estados llevan texto.
- Audio y vídeo con controles visibles.

---

## 9. Pendientes

- [ ] Elegir la paleta cromática y comprobar su contraste (issue I3-2).
- [ ] Elegir las dos tipografías con licencia abierta (issue I3-2).
- [ ] Enlazar el Figma de solo lectura en el README y en este documento (issue I3-3).
- [ ] Exportar las capturas de las vistas a `docs/img/`.
- [ ] Decidir el color asignado a cada género para las portadas generadas.