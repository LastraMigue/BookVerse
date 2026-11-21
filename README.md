# BookVerse — Club de Lectura

Aplicación web para amantes de los libros: consulta de catálogo, propuesta y verificación de títulos, publicación de reseñas valoradas y gestión de listas de lectura personales.

Proyecto integrador del 1.ᵉʳ Trimestre para los módulos **DWC** (Desarrollo Web Cliente), **DWS** (Desarrollo Web Servidor) y **DIW** (Diseño de Interfaces Web).

---

## Índice de Documentación

Toda la documentación detallada del proyecto se organiza en el directorio `docs/`:

* **Instalación y despliegue:** [docs/instalacion.md](docs/instalacion.md) *(Requisitos, entorno Apache/XAMPP, MySQL y verificación).*
* **Modelo de datos y SQL:** [docs/modelo-datos.md](docs/modelo-datos.md) *(Entidades, relaciones y script [docs/sql/schema.sql](docs/sql/schema.sql)).*
* **Diseño e interfaz:** [docs/diseno.md](docs/diseno.md) *(Público objetivo, guía de estilos, paleta cromática y wireframes).*
* **Prototipo en Figma:** *(Enlace al prototipo interactivo en Figma)*
* **Memoria técnica:** [docs/memoria-tecnica.md](docs/memoria-tecnica.md) *(Arquitectura tradicional PHP/MySQL/JS, seguridad y flujo de peticiones).*
* **Guía de uso:** [docs/guia-uso.md](docs/guia-uso.md) *(Manual para usuarios y moderación por administrador).*
* **Registro de pruebas:** [docs/pruebas.md](docs/pruebas.md) *(Evidencias funcionales, responsive, audio y vídeo).*
* **Inventario multimedia:** [docs/multimedia.md](docs/multimedia.md) *(Fuentes, autorías, licencias y optimización de recursos).*
* **Propuesta inicial:** [docs/propuesta.md](docs/propuesta.md) *(Alcance validado por el docente).*

---

## Política de Ramas y Flujo Git (Branching Policy)

El equipo aplica una metodología de trabajo basada en **GitFlow simplificado**:

```
main (estable y entregable: v0.1, v0.5, v1.0)
  ▲
  │ Pull Request al cerrar sprint (aprobada por Responsable de Git)
  │
develop (rama de integración)
  ▲
  │ Pull Request obligatoria + revisión de código
  │
feature/<rol>-<tarea> (ramas de trabajo individual)
```

### 1. Estructura de Ramas

* **`main`**: Contiene únicamente código estable, probado y listo para entrega. Las versiones se marcan mediante etiquetas Git:
  * `v0.1`: Cierre del Sprint 1 (esqueleto base, BD y auth inicial).
  * `v0.5`: Cierre del Sprint 2 (versión mínima completa y extras principales).
  * `v1.0`: Entrega final del proyecto.
  * *(Nota: Prohibido hacer push directo a `main`).*
* **`develop`**: Rama base de integración diaria. Recibe las funcionalidades finalizadas de las ramas `feature/`.
  * *(Nota: Prohibido hacer push directo a `develop`).*
* **`feature/<rol>-<tarea>`**: Ramas individuales y de corta duración creadas a partir de `develop`.
  * Ejemplos: `feature/bd-schema`, `feature/backend-auth`, `feature/diseno-plantilla`, `feature/multimedia-imagenes`.

### 2. Flujo de Trabajo Diario

1. **Sincronización:** Cada día, antes de empezar a programar, se actualiza la copia local de `develop` y se rebasea o fusiona en la rama de trabajo propia:
   ```bash
   git checkout develop
   git pull origin develop
   git checkout feature/<mi-rama>
   git merge develop
   ```
2. **Commits frecuentes y semánticos:** Se deben realizar commits pequeños y atómicos, utilizando los siguientes prefijos convencionales:
   * `feat:` para nuevas funcionalidades (ej. `feat: añade formulario de registro`).
   * `fix:` para corrección de errores (ej. `fix: corrige validación de contraseña`).
   * `docs:` para cambios o adiciones en documentación o README (ej. `docs: añade política de ramas`).
   * `style:` para ajustes de maquetación, CSS, formato o espaciado sin alterar lógica (ej. `style: mejora contraste en botones`).
3. **Pull Requests (PR):**
   * Todo cambio para entrar a `develop` debe realizarse a través de una Pull Request vinculada a su issue correspondiente del tablero Scrumban.
   * La PR es revisada y aprobada por el **Responsable de Git** (Integrante 1).
   * Las PR realizadas por el Integrante 1 son revisadas y aprobadas por el **Integrante 2**.
   * Una vez aprobada y verificada, la rama feature se fusiona en `develop` y puede eliminarse.

---

## Estructura del Proyecto

```text
BookVerse/
├── assets/
│   ├── css/       # Hojas de estilo personalizadas y Bootstrap
│   ├── js/        # Scripts de validación en cliente e interactividad
│   ├── img/       # Imágenes de la aplicación (optimizadas)
│   └── media/     # Archivos de audio y vídeo integrados
├── includes/      # Conexión PDO, utilidades de auth, plantillas (header/nav/footer)
├── docs/          # Documentación técnica, memorias, pruebas e inventario
│   ├── img/       # Wireframes, diagramas ER y capturas de evidencias
│   └── sql/       # Script schema.sql con DDL y datos ficticios de prueba
├── .gitignore     # Exclusión de archivos locales, temporales y credenciales
└── README.md      # Presentación del proyecto, índice y política de ramas
```
