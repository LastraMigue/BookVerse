const botonTema = document.getElementById('boton-tema');
const modoOscuroGuardado = document.cookie
    .split(';')
    .some(cookie => cookie.trim() === 'temaVisual=oscuro');
const textoTema = botonTema.querySelector('.texto-tema');

document.body.classList.toggle("modo-oscuro", modoOscuroGuardado);
textoTema.textContent = modoOscuroGuardado ? "Modo Claro" : "Modo Oscuro";

botonTema.addEventListener('click', function () {
    const modoOscuroActivo = document.body.classList.toggle("modo-oscuro");
    textoTema.textContent = modoOscuroActivo ? 'Modo Claro' : "Modo Oscuro";
    document.cookie = "temaVisual=" + (modoOscuroActivo ? "oscuro" : "claro");
});
