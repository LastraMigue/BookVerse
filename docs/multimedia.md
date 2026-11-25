# Inventario multimedia

Registro de todos los recursos multimedia de **Club de lectura**: de dónde sale cada uno, quién lo
hizo, con qué licencia se usa, cómo hay que atribuirlo, en qué formato está y qué se le ha hecho.


## 1. Imágenes

| # | Archivo | Uso | Fuente | Autoría | Licencia | Atribución | Formato | Dimensiones | Peso final | Modificaciones | Herramienta |
|---|---|---|---|---|---|---|---|---|---|---|---|
| 1 | madera-hero.webp | Fondo del hero de la página de inicio | https://pixabay.com/es/photos/clásico-madera-textura-de-madera-1557993/ | AS_Photography | CC0 | No requiere | WebP | 1920 × 800 px | 127 KB | Recorte a 2,4:1, redimensión desde 3423 × 2282 px y conversión de PNG a WebP (calidad 80) | GIMP |
| 2 | cien-anos-de-soledad.webp | Portada del libro en el catálogo y en su página de detalle | https://commons.wikimedia.org/wiki/File:Cien_años_de_soledad.png | Luis Rivera | Dominio público (PD-textlogo) | No requiere | WebP | 250 × 344 px | 8 KB | Conversión de PNG a WebP (calidad 85) | GIMP |
| 3 | orgullo-y-prejuicio.webp | Portada del libro en el catálogo y en su página de detalle | https://commons.wikimedia.org/wiki/File:PrideAndPrejudiceTitlePage.jpg | Jane Austen (1775–1817) | Dominio público (autor fallecido hace más de 100 años) | No requiere; crédito recomendado: «Jane Austen, Public domain, via Wikimedia Commons» | WebP | 250 × 404 px | 20 KB | Conversión de JPG a WebP (calidad 85) | GIMP |
| 4 | assets/img/dracula.webp | Portada del libro en el catálogo y en su página de detalle | https://commons.wikimedia.org/wiki/File:Dracula-First-Edition-1897.jpg | Diseño anónimo; edición de Archibald Constable & Co. (1897) | Dominio público (publicado antes de 1930) | No requiere | WebP | 250 × 348 px | 20 KB | Conversión de JPG a WebP (calidad 85) | GIMP |
| 5 | assets/img/el-sabueso-de-los-baskerville.webp | Imagen del libro en el catálogo y en su página de detalle (ilustración interior de Sidney Paget) | https://commons.wikimedia.org/wiki/File:Houn-53_-_The_coal-black_Hound_(Hound_of_Baskervilles).jpg | Sidney Paget (1860–1908) | Dominio público (autor fallecido hace más de 100 años) | No requiere; crédito voluntario: «Sidney Paget, Public domain, via Wikimedia Commons» | WebP | 250 × 338 px | 24 KB | Redimensión desde 302 × 408 px y conversión de JPG a WebP (calidad 85) | GIMP |


---

## 2. Audio

| Archivo | Uso | Fuente | Autoría | Licencia | Atribución | Formato | Duración | Peso final | Modificaciones | Herramienta |
|---|---|---|---|---|---|---|---|---|---|---|
| assets/media/pasar-pagina.mp3 | Efecto sonoro al pulsar un libro del catálogo para abrir su detalle | https://pixabay.com/es/music/late-page-flip-557810/ | sumaga123 | Licencia de contenido de Pixabay | No requiere | MP3 | 1s | 50 KB | ninguna modificacion | Audacity |

---

## 3. Vídeo

| Archivo | Uso | Fuente | Autoría | Licencia | Atribución | Formato | Duración | Resolución | Peso final | Modificaciones | Herramienta |
|---|---|---|---|---|---|---|---|---|---|---|---|
| assets/media/Videopresentacion.mp4 | Vídeo de presentación del club en la página de inicio, con controles | https://pixabay.com/es/videos/libro-abierto-libro-naturaleza-329698/ | samsabt | Licencia de contenido de Pixabay | No requiere | MP4 (H.264, 24 fps) | 9 s | 1280 × 720 | 1,150 MB | Recorte a 9 s, reescalado a 720p, degradado oscuro, rótulo en PT Serif y fundidos | CapCut |

---

## 4. Animació

| Archivo | Uso | Imágenes de origen | Autoría | Licencia | Formato | Duración | Peso final | Cómo se hizo | Herramienta |
|---|---|---|---|---|---|---|---|---|---|
| assets/img/bv_1_libro_cerrado.svg, assets/img/bv_2_libro_entreabierto.svg, assets/img/bv_3_libro_abierto.svg + assets/js/animacion-logo.js | Animación de apertura del logo en la cabecera, al cargar la página de inicio | Los tres SVG del logo: libro cerrado → entreabierto → abierto | Manuel Jesús | Propio uso exclusivo del proyecto | SVG animado con CSS y JavaScript | *(pendiente: ~1,5 s)* | 43 KB (suma de los tres SVG) | Tres estados fijos del logo superpuestos; se alterna su opacidad con CSS para simular la apertura. Se reproduce una vez al cargar y respeta prefers-reduced-motion. | Figma (diseño de los tres estados) y código propio (CSS y JavaScript) |

---

## 5. Tipografías

| Familia | Uso | Fuente | Licencia | Atribución | Cómo se carga |
|---|---|---|---|---|---|
| PT Serif | Títulos (h1–h3), texto de los logos SVG y citas | https://fonts.google.com/specimen/PT+Serif | SIL Open Font License 1.1 (ParaType) | No requiere en la web; el archivo `OFL.txt` acompaña a las fuentes en `assets/fonts/` | Alojada en el proyecto (`assets/fonts/`) y declarada con `@font-face` |
| PT Sans | Texto corrido, interfaz, formularios y botones | https://fonts.google.com/specimen/PT+Sans | SIL Open Font License 1.1 (ParaType) | No requiere en la web; el archivo `OFL.txt` acompaña a las fuentes en `assets/fonts/` | Alojada en el proyecto (`assets/fonts/`) y declarada con `@font-face` |

---

## 6. Iconos y otros recursos

| Archivo o conjunto | Uso | Fuente | Autoría | Licencia | Atribución | Formato |
|---|---|---|---|---|---|---|
| bv_1_libro_cerrado.svg | Logo de la web; primer fotograma de la animación inicial (libro cerrado con las iniciales BV) | Creada por nosotros | Manuel Jesús | Propio — uso exclusivo del proyecto | No requiere | .svg |
| bv_2_libro_entreabierto.svg | Fotograma intermedio de la animación inicial (libro entreabierto) | Creada por nosotros | Manuel Jesús | Propio — uso exclusivo del proyecto | No requiere | .svg |
| bv_3_libro_abierto.svg | Fotograma final de la animación inicial y logo desplegado en la cabecera (libro abierto con «Book» y «Verse») | Creada por nosotros | Manuel Jesús | Propio — uso exclusivo del proyecto | No requiere | .svg |

---

## 7. Criterios de optimización aplicados

Rellenad lo que hayáis aplicado de verdad; sirve para justificar las decisiones en la defensa.

- [ ] Imágenes convertidas a **WebP** (con alternativa JPG/PNG si hiciera falta).
- [ ] Imágenes redimensionadas al tamaño real al que se muestran, sin servir un 4000 px para un
      hueco de 400 px.
- [ ] Compresión con pérdida ajustada hasta el punto en que no se nota a simple vista.
- [ ] Atributos `width` y `height` en el HTML, para evitar saltos de maquetación al cargar.
- [ ] `loading="lazy"` en las imágenes que están por debajo del primer pantallazo.
- [ ] Texto alternativo (`alt`) en todas las imágenes; vacío (`alt=""`) en las decorativas.
- [ ] Audio y vídeo con **controles visibles** y sin reproducción automática con sonido.
- [ ] Vídeo con una resolución y un bitrate razonables para web.
- [ ] Nombres de archivo en minúsculas, sin espacios ni acentos.

---

## 8. Comprobación de licencias

Antes de dar el inventario por cerrado:

- [ ] Cada recurso ajeno tiene su **URL de origen** anotada, no solo el nombre del sitio.
- [ ] Cada licencia permite **uso en un trabajo publicado en un repositorio**.
- [ ] Las que exigen atribución la tienen escrita **literal** en la columna correspondiente y
      visible en la web (pie de página o junto al recurso).
- [ ] Ningún recurso procede de una búsqueda de imágenes sin comprobar su licencia.
- [ ] Los recursos propios están marcados como tales, con el nombre de quien los hizo.