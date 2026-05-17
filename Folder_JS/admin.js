document.querySelectorAll(".btn-bloquer").forEach(btn => {
    btn.addEventListener("click", async function() {
        const userId = this.dataset.id;
        const estBloque = this.dataset.bloque === "1";
        const action = estBloque ? "débloquer" : "bloquer";

        if (!confirm(`Voulez-vous vraiment ${action} cet utilisateur ?`)) return;

        try {
            const response = await fetch("bloquer_utilisateur.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id: userId,
                    bloquer: !estBloque
                })
            });

            const data = await response.json();

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