<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Revisa las propuestas de libros de BookVerse.">
    <title>Administración | BookVerse</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pagina-catalogo pagina-administracion">
    <?php include 'navbar.php'; ?>

    <section class="marco-catalogo marco-administracion">
        <main class="panel-catalogo panel-administracion">
            <header class="navegacion-catalogo">
                <a class="titulo-catalogo" href="inicio.php" aria-label="Ir a BookVerse">
                    <span>BV</span>
                    <strong>BookVerse</strong>
                </a>
                <p class="etiqueta-panel-admin">Panel de administración</p>
            </header>

            <div class="cuerpo-administracion">
                <aside class="barra-lateral-biblioteca barra-lateral-administracion">
                    <p class="rotulo-biblioteca">BookVerse</p>
                    <h2>Administración</h2>
                    <p class="descripcion-biblioteca">Gestiona las propuestas de la comunidad.</p>
                    <nav aria-label="Secciones de administración">
                        <a class="seleccionado" href="#pendientes">
                            <span class="simbolo-menu" aria-hidden="true"></span>
                            <span>Por revisar</span>
                            <b>3</b>
                        </a>
                        <a href="#publicados">
                            <span class="simbolo-menu-cuadrado" aria-hidden="true"></span>
                            <span>Libros publicados</span>
                            <b>3</b>
                        </a>
                    </nav>
                </aside>

                <div class="contenido-moderacion">
                    <header class="encabezado-moderacion">
                        <div>
                            <p class="rotulo-listas">Revisión de contenido</p>
                            <h1>Panel de moderación</h1>
                            <p class="descripcion-moderacion">Revisa las propuestas nuevas y mantén al día el catálogo.</p>
                        </div>
                        <div class="cuenta-pendientes" aria-label="3 propuestas pendientes">
                            <strong>3</strong>
                            <span>pendientes</span>
                        </div>
                    </header>

                    <p class="nota-moderacion">Vista de ejemplo: las acciones todavía no están conectadas.</p>

                    <nav class="pestanas-reportes" aria-label="Listas de moderación">
                        <a class="activo" href="#pendientes">Pendientes <span>3</span></a>
                        <a href="#publicados">Publicados <span>3</span></a>
                    </nav>

                    <section class="lista-moderacion" id="pendientes" aria-labelledby="titulo-pendientes">
                        <div class="encabezado-lista-moderacion">
                            <div>
                                <h2 id="titulo-pendientes">Propuestas pendientes</h2>
                                <p>Comprueba los datos antes de aprobar o rechazar cada libro.</p>
                            </div>
                            <span class="estado-moderacion estado-pendiente">Pendiente de revisión</span>
                        </div>

                        <article class="tarjeta-moderacion">
                            <div class="datos-moderacion">
                                <div class="titulo-y-estado">
                                    <h3>Neuromante</h3>
                                    <span class="estado-moderacion estado-pendiente">Pendiente</span>
                                </div>
                                <p class="autor-moderacion">William Gibson</p>
                                <p class="detalle-moderacion"><strong>ISBN</strong> 978-8445074091</p>
                                <p class="descripcion-libro-moderacion">Case era el mejor vaquero del ciberespacio hasta que cometió un error fatal; ahora le ofrecen una última oportunidad de redención.</p>
                                <p class="origen-moderacion">Propuesto por <strong>Lector de prueba</strong><span aria-hidden="true">·</span>15 nov. 2026</p>
                            </div>
                            <div class="acciones-moderacion" aria-label="Acciones para Neuromante">
                                <button class="boton-moderacion boton-aprobar" type="button">Aprobar</button>
                                <button class="boton-moderacion boton-rechazar" type="button">Rechazar</button>
                            </div>
                        </article>

                        <article class="tarjeta-moderacion">
                            <div class="datos-moderacion">
                                <div class="titulo-y-estado">
                                    <h3>Revolución en la granja</h3>
                                    <span class="estado-moderacion estado-pendiente">Pendiente</span>
                                </div>
                                <p class="autor-moderacion">George Orwell</p>
                                <p class="detalle-moderacion"><strong>ISBN</strong> 978-8499890944</p>
                                <p class="descripcion-libro-moderacion">Sátira alegórica en la que los animales de una granja se rebelan contra los humanos, solo para caer bajo la tiranía de los cerdos.</p>
                                <p class="origen-moderacion">Propuesto por <strong>Clara Gómez</strong><span aria-hidden="true">·</span>16 nov. 2026</p>
                            </div>
                            <div class="acciones-moderacion" aria-label="Acciones para Revolución en la granja">
                                <button class="boton-moderacion boton-aprobar" type="button">Aprobar</button>
                                <button class="boton-moderacion boton-rechazar" type="button">Rechazar</button>
                            </div>
                        </article>

                        <article class="tarjeta-moderacion">
                            <div class="datos-moderacion">
                                <div class="titulo-y-estado">
                                    <h3>El problema de los tres cuerpos</h3>
                                    <span class="estado-moderacion estado-pendiente">Pendiente</span>
                                </div>
                                <p class="autor-moderacion">Cixin Liu</p>
                                <p class="detalle-moderacion"><strong>ISBN</strong> 978-8466659734</p>
                                <p class="descripcion-libro-moderacion">Un proyecto militar secreto en China contacta con una civilización extraterrestre al borde del colapso planetario.</p>
                                <p class="origen-moderacion">Propuesto por <strong>Marcos Ruiz</strong><span aria-hidden="true">·</span>17 nov. 2026</p>
                            </div>
                            <div class="acciones-moderacion" aria-label="Acciones para El problema de los tres cuerpos">
                                <button class="boton-moderacion boton-aprobar" type="button">Aprobar</button>
                                <button class="boton-moderacion boton-rechazar" type="button">Rechazar</button>
                            </div>
                        </article>
                    </section>

                    <section class="lista-moderacion lista-publicados" id="publicados" aria-labelledby="titulo-publicados">
                        <div class="encabezado-lista-moderacion">
                            <div>
                                <h2 id="titulo-publicados">Libros publicados</h2>
                                <p>Corrige los datos del catálogo o elimina un libro si es necesario.</p>
                            </div>
                            <span class="estado-moderacion estado-publicado">En catálogo</span>
                        </div>

                        <article class="tarjeta-moderacion tarjeta-publicada">
                            <div class="datos-moderacion">
                                <div class="titulo-y-estado">
                                    <h3>Cien años de soledad</h3>
                                    <span class="estado-moderacion estado-publicado">Publicado</span>
                                </div>
                                <p class="autor-moderacion">Gabriel García Márquez</p>
                                <p class="detalle-moderacion"><strong>ISBN</strong> 978-0307474728</p>
                            </div>
                            <div class="acciones-moderacion" aria-label="Acciones para Cien años de soledad">
                                <button class="boton-moderacion boton-editar" type="button">Editar</button>
                                <button class="boton-moderacion boton-eliminar" type="button">Eliminar</button>
                            </div>
                        </article>

                        <article class="tarjeta-moderacion tarjeta-publicada">
                            <div class="datos-moderacion">
                                <div class="titulo-y-estado">
                                    <h3>El nombre del viento</h3>
                                    <span class="estado-moderacion estado-publicado">Publicado</span>
                                </div>
                                <p class="autor-moderacion">Patrick Rothfuss</p>
                                <p class="detalle-moderacion"><strong>ISBN</strong> 978-8401352836</p>
                            </div>
                            <div class="acciones-moderacion" aria-label="Acciones para El nombre del viento">
                                <button class="boton-moderacion boton-editar" type="button">Editar</button>
                                <button class="boton-moderacion boton-eliminar" type="button">Eliminar</button>
                            </div>
                        </article>

                        <article class="tarjeta-moderacion tarjeta-publicada">
                            <div class="datos-moderacion">
                                <div class="titulo-y-estado">
                                    <h3>Dune</h3>
                                    <span class="estado-moderacion estado-publicado">Publicado</span>
                                </div>
                                <p class="autor-moderacion">Frank Herbert</p>
                                <p class="detalle-moderacion"><strong>ISBN</strong> 978-0441172719</p>
                            </div>
                            <div class="acciones-moderacion" aria-label="Acciones para Dune">
                                <button class="boton-moderacion boton-editar" type="button">Editar</button>
                                <button class="boton-moderacion boton-eliminar" type="button">Eliminar</button>
                            </div>
                        </article>
                    </section>
                </div>
            </div>
        </main>
    </section>
</body>
</html>
