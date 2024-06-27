window.onload = function () {
  // Función para validar el formulario
  function validarFormulario(event) {
    // Obtener los valores de los campos del formulario
    var nombre = document.getElementById("nombre").value.trim();
    var direccion = document.getElementById("direccion").value.trim();
    var telefono = document.getElementById("telefono").value.trim();
    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value;

    // Expresión regular para validar teléfono (comienza con 6 o 7 y tiene 9 dígitos)
    var telefonoRegex = /^[6-7]\d{8}$/;
    // Expresión regular para validar email (formato básico)
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Validar email
    if (!emailRegex.test(email)) {
      alert("Por favor, introduce un email válido.");
      event.preventDefault();
      return false;
    }

    // Validar teléfono
    if (!telefonoRegex.test(telefono)) {
      alert(
        "Por favor, introduce un número de teléfono válido (que empiece con 6 o 7 y tenga 9 dígitos)."
      );
      event.preventDefault();
      return false;
    }

    return true; // Permitir el envío del formulario si todas las validaciones son correctas
  }

  // Agregar el evento submit al formulario
  var form = document.querySelector("form");
  form.addEventListener("submit", validarFormulario);
};
