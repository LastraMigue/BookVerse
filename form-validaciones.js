document.addEventListener('DOMContentLoaded', () => {
  initValidadorAcceso();
  initValidadorLibro();
});

function initValidadorAcceso() {
  const formulario = document.querySelector('.pagina-acceso .formulario-acceso');
  if (!formulario) return;

  const campoNombre = document.getElementById('nombre');
  const campoEmail = document.getElementById('email');
  const campoPassword = document.getElementById('password');
  const campoConfirmacion = document.getElementById('confirmacion-contrasena');
  const campoTerminos = document.getElementById('terms');
  const todosLosCampos = [campoNombre, campoEmail, campoPassword, campoConfirmacion, campoTerminos];

  const esRegistro = campoConfirmacion !== null;

  function mostrarErrorCampo(campo, mensaje) {
    if (!campo) return;
    campo.classList.add('is-invalid');

    const contenedorBase =
      campo.type === 'checkbox'
        ? campo.closest('.form-check') || campo.parentElement || campo
        : campo;

    let mensajeEl = contenedorBase.nextElementSibling;
    if (!mensajeEl || !mensajeEl.classList.contains('error-campo')) {
      mensajeEl = document.createElement('div');
      mensajeEl.className = 'error-campo text-danger small mt-1';
      contenedorBase.insertAdjacentElement('afterend', mensajeEl);
    }
    mensajeEl.textContent = mensaje;
  }

  function limpiarErroresCampos() {
    todosLosCampos.forEach(campo => {
      if (!campo) return;
      campo.classList.remove('is-invalid');

      const contenedorBase =
        campo.type === 'checkbox'
          ? campo.closest('.form-check') || campo.parentElement || campo
          : campo;

      const siguiente = contenedorBase.nextElementSibling;
      if (siguiente && siguiente.classList.contains('error-campo')) {
        siguiente.remove();
      }
    });
  }

  function validar() {
    limpiarErroresCampos();
    let hayErrores = false;

    if (campoNombre) {
      const nombre = typeof campoNombre.value === 'string' ? campoNombre.value.trim() : '';
      if (nombre === '' || !isNaN(nombre) || /\d/.test(nombre)) {
        mostrarErrorCampo(campoNombre, 'Introduce un nombre y que no sea numérico.');
        hayErrores = true;
      } else if (nombre.length < 2 || nombre.length > 100) {
        mostrarErrorCampo(campoNombre, 'El nombre debe tener entre 2 y 100 caracteres.');
        hayErrores = true;
      }
    }

    if (campoEmail) {
      const email = typeof campoEmail.value === 'string' ? campoEmail.value.trim() : '';
      const formatoInvalido = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      if (email === '') {
        mostrarErrorCampo(campoEmail, 'El correo electrónico es obligatorio.');
        hayErrores = true;
      } else if (formatoInvalido) {
        mostrarErrorCampo(campoEmail, 'El formato del correo electrónico no es válido.');
        hayErrores = true;
      } else if (email.length > 150) {
        mostrarErrorCampo(campoEmail, 'El correo electrónico no puede superar los 150 caracteres.');
        hayErrores = true;
      }
    }

    if (campoPassword) {
      const password = campoPassword.value;
      if (password === '') {
        mostrarErrorCampo(campoPassword, 'La contraseña es obligatoria.');
        hayErrores = true;
      } else if (esRegistro && password.length < 8) {
        mostrarErrorCampo(campoPassword, 'La contraseña debe tener al menos 8 caracteres.');
        hayErrores = true;
      }
    }

    if (campoConfirmacion && campoPassword && campoPassword.value !== campoConfirmacion.value) {
      mostrarErrorCampo(campoConfirmacion, 'Las contraseñas no coinciden.');
      hayErrores = true;
    }

    if (campoTerminos && !campoTerminos.checked) {
      mostrarErrorCampo(campoTerminos, 'Debes aceptar los términos para crear tu cuenta.');
      hayErrores = true;
    }

    return hayErrores;
  }

  formulario.addEventListener('submit', evento => {
    const hayErrores = validar();

    if (hayErrores) {
      evento.preventDefault();
      const primerError = formulario.querySelector('.is-invalid');
      if (primerError) primerError.focus();
    }
  });

  if (campoConfirmacion && campoPassword) {
    campoConfirmacion.addEventListener('input', () => {
      const desajuste =
        campoConfirmacion.value !== '' &&
        campoConfirmacion.value !== campoPassword.value;
      campoConfirmacion.classList.toggle('is-invalid', desajuste);
    });
  }
}

function initValidadorLibro() {
  const formulario = document.querySelector('.pagina-formulario #formulario-libro');
  if (!formulario) return;

  const campoTitulo = document.getElementById('titulo');
  const campoAutor = document.getElementById('autor');
  const campoGenero = document.getElementById('genero');
  const campoPublicacion = document.getElementById('publicacion');
  const campoSinopsis = document.getElementById('sinopsis');
  const campoPortada = document.getElementById('portada');

  let cajaErrores = null;

  function mostrarErrores(errores) {
    if (!cajaErrores) {
      cajaErrores = document.createElement('div');
      cajaErrores.className = 'alert alert-danger';
      cajaErrores.setAttribute('role', 'alert');
      formulario.parentNode.insertBefore(cajaErrores, formulario);
    }

    const lista = errores.map(error => `<li>${error}</li>`).join('');
    cajaErrores.innerHTML = `<ul class="mb-0">${lista}</ul>`;
    cajaErrores.hidden = false;
    cajaErrores.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function limpiarErrores() {
    if (cajaErrores) {
      cajaErrores.hidden = true;
      cajaErrores.innerHTML = '';
    }
  }

  function validar() {
    const errores = [];

    if (campoTitulo) {
      const titulo = typeof campoTitulo.value === 'string' ? campoTitulo.value.trim() : '';
      if (titulo === '' || !isNaN(titulo)) {
        errores.push('Introduce un título válido y que no sea solo numérico.');
      } else if (titulo.length < 2 || titulo.length > 200) {
        errores.push('El título debe tener entre 2 y 200 caracteres.');
      }
    }

    if (campoAutor) {
      const autor = typeof campoAutor.value === 'string' ? campoAutor.value.trim() : '';
      if (autor === '' || !isNaN(autor) || /\d/.test(autor)) {
        errores.push('Introduce el nombre del autor o autora y que no contenga números.');
      } else if (autor.length < 2 || autor.length > 120) {
        errores.push('El nombre del autor debe tener entre 2 y 120 caracteres.');
      }
    }

    if (campoGenero) {
      const genero = campoGenero.value.trim();
      if (genero === '') {
        errores.push('Selecciona un género para el libro.');
      }
    }

    if (campoPublicacion) {
      const anioTexto = campoPublicacion.value.trim();
      if (anioTexto !== '') {
        const anio = Number(anioTexto);
        const anioActual = new Date().getFullYear();
        if (!Number.isInteger(anio) || anio < 1 || anio > anioActual) {
          errores.push(`El año de publicación debe estar entre 1 y ${anioActual}.`);
        }
      }
    }

    if (campoSinopsis) {
      const sinopsis = typeof campoSinopsis.value === 'string' ? campoSinopsis.value.trim() : '';
      if (sinopsis === '') {
        errores.push('La sinopsis es obligatoria.');
      } else if (sinopsis.length < 20 || sinopsis.length > 1500) {
        errores.push('La sinopsis debe tener entre 20 y 1500 caracteres.');
      }
    }

    if (campoPortada && campoPortada.files && campoPortada.files.length > 0) {
      const archivo = campoPortada.files[0];
      const formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
      const pesoMaximoMB = 5;

      if (!formatosPermitidos.includes(archivo.type)) {
        errores.push('La portada debe ser una imagen JPG, PNG o WebP.');
      } else if (archivo.size > pesoMaximoMB * 1024 * 1024) {
        errores.push(`La portada no puede superar los ${pesoMaximoMB} MB.`);
      }
    }

    return errores;
  }

  formulario.addEventListener('submit', evento => {
    const errores = validar();

    if (errores.length > 0) {
      evento.preventDefault();
      mostrarErrores(errores);
    } else {
      limpiarErrores();
    }
  });
}