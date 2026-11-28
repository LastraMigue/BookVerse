document.addEventListener('DOMContentLoaded',() => {
  const formulario = document.querySelector('.pagina-acceso .formulario-acceso');
  if (!formulario) return;


    const campoNombre = document.getElementById('nombre');
    const campoEmail = document.getElementById('email');
    const campoPassword = document.getElementById('password');
    const campoConfirmacion = document.getElementById('confirmacion-contrasena');
    const campoTerminos = document.getElementById('terms');

    const esRegistro = campoConfirmacion !== null;

    let cajaErrores = null;

    function mostrarErrores(){
      if (!cajaErrores){
        cajaErrores=document.createElement('div');
        cajaErrores.className='alert alert-danger';
        cajaErrores.setAttribute('role', 'alert');
        cajaErrores.parentNode,insertbefore(cajaErrores,formulario);
      }

      const lista = errores.map(error => `<li>${error}</li>`).join('');
        cajaErrores.innerHTML = `<ul class="mb-0">${lista}</ul>`;
        cajaErrores.hidden = false;
        campoEmail.focus;
    }

    function limpiarErrores() {
        if (cajaErrores) cajaErrores.hidden = true;
    }

    function validar (){
      const error= [];

      if (campoNombre){
        const nombre=campoNombre;
        const limpio = typeof nombre === 'string' ? nombre.trim() : '';
        if (limpio === '' || !isNaN(limpio) || /\d/.test(limpio)){
          error.push('Introduce un nombre y que no sea numerico.');
        }else if (nombre.length < 2 || nombre.length > 100){
          error.push('El nombre debe tener entre 2 y 100 caracteres.');
        }
      }

      if (campoEmail){
        const email = campoEmail.value.trim();
        const validation=!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        if (email === '') {
                error.push('El correo electrónico es obligatorio.');
            } else if (validation) {
                error.push('El formato del correo electrónico no es válido.');
            } else if (email.length > 150) {
                error.push('El correo electrónico no puede superar los 150 caracteres.');
            }
      }
       if (campoPassword) {
            const password = campoPassword.value;
            if (password === '') {
                error.push('La contraseña es obligatoria.');
            } else if (esRegistro && password.length < 8) {
                error.push('La contraseña debe tener al menos 8 caracteres.');
            }
        }
      if (campoConfirmacion && campoPassword.value !== campoConfirmacion.value) {
          error.push('Las contraseñas no coinciden.');
      }
      if (campoTerminos && !campoTerminos.checked) {
          error.push('Debes aceptar los términos para crear tu cuenta.');
      }
      return error;
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
    if (campoConfirmacion) {
        campoConfirmacion.addEventListener('input', () => {
            const desajuste = campoConfirmacion.value !== '' &&
                              campoConfirmacion.value !== campoPassword.value;
            campoConfirmacion.classList.toggle('Invalido', desajuste);
        });
    }
});