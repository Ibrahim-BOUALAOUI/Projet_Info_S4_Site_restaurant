function validerFormulaire() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const erreurDiv = document.getElementById('erreur-client');
    let message = '';
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email) {
        message = "L'adresse e-mail est obligatoire.";
    } else if (!regexEmail.test(email)) {
        message = "L'adresse e-mail n'est pas valide.";
    } else if (!password) {
        message = "Le mot de passe est obligatoire.";
    }

    if (message) {
        erreurDiv.textContent = message;
        erreurDiv.style.display = 'block';
        return false;
    }

    erreurDiv.style.display = 'none';
    return true;
}