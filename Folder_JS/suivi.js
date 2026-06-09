// L'ordre des étapes, du début à la fin
const ORDRE_STATUTS = ["a preparer", "en cours de livraison", "livree"];

// Les textes affichés selon le statut
const LABELS = {
    "a preparer": "👨‍🍳 En préparation",
    "en cours de livraison": "🛵 En cours de livraison",
    "livree": "✅ Commande livrée !"
};

// On récupère l'id de la commande stocké dans le data-id du HTML
const conteneur = document.getElementById("suivi-etapes");
const idCommande = conteneur.dataset.id;

// Cette fonction interroge le serveur et met à jour l'affichage
async function verifierStatut() {
    try {
        const reponse = await fetch("statut_commande.php?id=" + idCommande);
        const data = await reponse.json();

        if (!data.succes) {
            document.getElementById("suivi-message").textContent = "Commande introuvable.";
            return;
        }

        // On trouve à quelle étape on en est (0, 1 ou 2)
        const etapeActuelle = ORDRE_STATUTS.indexOf(data.statut);

        // On parcourt chaque étape pour l'allumer ou non
        document.querySelectorAll(".etape").forEach(etape => {
            const indexEtape = ORDRE_STATUTS.indexOf(etape.dataset.statut);

            // Toutes les étapes jusqu'à l'étape actuelle sont "validées"
            if (indexEtape <= etapeActuelle) {
                etape.classList.add("active");
            } else {
                etape.classList.remove("active");
            }
        });

        document.getElementById("suivi-message").textContent = LABELS[data.statut];

    } catch (erreur) {
        console.error("Erreur :", erreur);
    }
}

// On vérifie tout de suite au chargement
verifierStatut();

// Puis on revérifie automatiquement toutes les 5 secondes
setInterval(verifierStatut, 5000);