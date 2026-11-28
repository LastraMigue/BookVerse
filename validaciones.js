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

  const esRegistro = campoConfirmacion !== null;
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

    if (campoEmail) {
      campoEmail.focus();
    }
  }

  function limpiarErrores() {
    if (cajaErrores) {
      cajaErrores.hidden = true;
      cajaErrores.innerHTML = '';
    }
  }

  function validar() {
    const errores = [];

    if (campoNombre) {
      const nombre = typeof campoNombre.value === 'string' ? campoNombre.value.trim() : '';
      if (nombre === '' || !isNaN(nombre) || /\d/.test(nombre)) {
        errores.push('Introduce un nombre y que no sea numerico.');
      } else if (nombre.length < 2 || nombre.length > 100) {
        errores.push('El nombre debe tener entre 2 y 100 caracteres.');
      }
    }

    if (campoEmail) {
      const email = typeof campoEmail.value === 'string' ? campoEmail.value.trim() : '';
      const formatoInvalido = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      if (email === '') {
        errores.push('El correo electrónico es obligatorio.');
      } else if (formatoInvalido) {
        errores.push('El formato del correo electrónico no es válido.');
      } else if (email.length > 150) {
        errores.push('El correo electrónico no puede superar los 150 caracteres.');
      }
    }

    if (campoPassword) {
      const password = campoPassword.value;
      if (password === '') {
        errores.push('La contraseña es obligatoria.');
      } else if (esRegistro && password.length < 8) {
        errores.push('La contraseña debe tener al menos 8 caracteres.');
      }
    }

    if (campoConfirmacion && campoPassword && campoPassword.value !== campoConfirmacion.value) {
      errores.push('Las contraseñas no coinciden.');
    }

    if (campoTerminos && !campoTerminos.checked) {
      errores.push('Debes aceptar los términos para crear tu cuenta.');
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

  if (campoConfirmacion && campoPassword) {
    campoConfirmacion.addEventListener('input', () => {
      const desajuste =
        campoConfirmacion.value !== '' &&
        campoConfirmacion.value !== campoPassword.value;
      campoConfirmacion.classList.toggle('Invalido', desajuste);
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