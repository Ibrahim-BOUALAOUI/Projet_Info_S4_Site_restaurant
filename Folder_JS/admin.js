document.querySelectorAll(".btn-bloquer").forEach(btn => {
    btn.addEventListener("click", async function() {
        // Récupere l'état du compte
        const userId = this.dataset.id;
        const estBloque = this.dataset.bloque === "1";
        const action = estBloque ? "débloquer" : "bloquer";

        if (!confirm(`Voulez-vous vraiment ${action} cet utilisateur ?`)) return; // Si l'admin clique sur annuler ca s'arrete 

        try {
            const response = await fetch("../Folder HTML/blocage.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" }, //indique qu'on lui envoie un JSON
                body: JSON.stringify({
                    id: userId,
                    bloquer: !estBloque
                })
            });

            const data = await response.json(); // Attend la réponse du JSON
            // Si le JSON renvoie les bonnes valeurs on met à jour notre affichage
            if (data.succes) {
                // Mettre à jour le bouton sans recharger la page
                this.dataset.bloque = estBloque ? "0" : "1";
                this.textContent = estBloque ? "🔒 Bloquer" : "🔓 Débloquer";

                // Changer la couleur de la ligne
                this.closest("tr").classList.toggle("user-bloque");

                alert(data.message);
            } else {
                alert("Erreur : " + data.message);
            }
        } catch (erreur) {
            console.error("Erreur :", erreur);
            alert("Erreur de connexion au serveur");
        }
    });
});