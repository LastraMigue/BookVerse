const form = document.getElementById("loginForm");
const error = document.getElementById("error");

form.addEventListener("submit", (e) => {
  e.preventDefault(); // evita que se recargue la página

  const email = document.getElementById("email").value.trim();
  const pass = document.getElementById("pass").value;
  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (email === "" || pass === "") {
    error.textContent = "Todos los campos son obligatorios.";
    return;
  }
  if (!regexEmail.test(email)) {
    error.textContent = "El correo no tiene un formato válido.";
    return;
  }
  if (pass.length < 8) {
    error.textContent = "La contraseña debe tener al menos 8 caracteres.";
    return;
  }

  error.textContent = "";
  form.submit(); // o enviar con fetch al servidor
});