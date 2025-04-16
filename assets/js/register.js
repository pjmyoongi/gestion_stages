/* assets/js/register.js */
function validateForm() {
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm_password").value;

  // Email format validation
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert("Veuillez entrer une adresse email valide.");
    return false;
  }

  // Password security
  if (password.length < 6) {
    alert("Le mot de passe doit contenir au moins 6 caractères.");
    return false;
  }

  if (password !== confirmPassword) {
    alert("Les mots de passe ne correspondent pas.");
    return false;
  }

  return true;
}
