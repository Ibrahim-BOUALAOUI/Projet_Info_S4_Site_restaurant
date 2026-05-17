function validerFormulaire() {
    const birthdate = document.getElementById('birthdate').value;
    const first_name = document.getElementById('name').value.trim();
    const last_name = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();
    const password = document.getElementById('password').value.trim();
    const erreurDiv = document.getElementById('erreur-client');

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const regexTelephone = /^0[1-9]\d{8}$/;

    let message = '';

    if (!birthdate) {
        message = "La date de naissance est obligatoire.";
    } else {
        const year = new Date(birthdate).getFullYear();
        if (year < 1900) {
            message = "Date de naissance incorrecte.";
        }
        else if (year > 2026) {
            message = "Date de naissance incorrecte.";
        }
    }

    if (!message && !first_name) {
        message = "Le prénom est obligatoire.";
    } else if (!message && !last_name) {
        message = "Le nom est obligatoire.";
    } else if (!message && !email) {
        message = "L'adresse e-mail est obligatoire.";
    } else if (!message && !regexEmail.test(email)) {
        message = "L'adresse e-mail n'est pas valide.";
    } else if (!message && !regexTelephone.test(phone)) {
        message = "Le numéro est invalide (ex: 0612345678).";
    } else if (!message && !address) {
        message = "L'adresse est obligatoire.";
    } else if (!message && !password) {
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