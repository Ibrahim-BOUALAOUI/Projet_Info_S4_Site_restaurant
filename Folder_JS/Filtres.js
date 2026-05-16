let TousLesPlats = [];

const FiltresActif = { 
    type: null,
    saveur : null,
    regime : null,
    prix : null
};

// Je récupère les données du fichier JSON
async function init(){
    const reponse = await fetch("../Folder_Data/Menus.json");
    const data = await reponse.json();
    TousLesPlats = data.plats; //Je Fill mon tableau
    afficherPlats(TousLesPlats);
    //Récpuere l'information des checkboxes
     document.getElementById("sandwich").addEventListener("change", function() {
    if (this.checked) {
      setFiltre("type", "sandwich");
    } else {
      setFiltre("type", null);
    }
  });

  document.getElementById("Menus").addEventListener("change", function() {
    if (this.checked) {
      setFiltre("type", "Menus");
    } else {
      setFiltre("type", null);
    }
  });

  document.getElementById("extra").addEventListener("change", function() {
    if (this.checked) {
      setFiltre("type", "extra");
    } else {
      setFiltre("type", null);
    }
  });

  document.getElementById("boisson").addEventListener("change", function() {
    if (this.checked) {
      setFiltre("type", "boisson");
    } else {
      setFiltre("type", null);
    }
  });
}
function setFiltre(type,valeur){ 
    //La fonction qui filtre en fonction du filtre en question
    FiltresActif[type] = valeur;
    filtrerPlats();
}

function filtrerPlats(){
    const resultats = TousLesPlats.filter(plat=> {
    if (FiltresActif.type && plat.type !== FiltresActif.type)
      return false;
    if (FiltresActif.saveur && plat.saveur !== FiltresActif.saveur)
      return false;
    if (FiltresActif.regime && !plat.regime.includes(FiltresActif.regime))
      return false;
    return true;
  });
  afficherPlats(resultats);
}

function afficherPlats(plats) {
  const conteneur = document.querySelector(".products-grid"); // Récupere les données d'un selecteur
  conteneur.innerHTML = ""; // Vide l'affichage

    plats.forEach(p => {
    const carte = document.createElement("article");
    carte.classList.add("product-card");

    carte.innerHTML = `
      <div class="product-image-container" style="position: relative;">
        <img src="${p.image}" alt="${p.nom}" class="product-image">
        <a href="ajouter_panier.php?nom=${encodeURIComponent(p.nom)}&prix=${p.prix}" class="add-button-link">+</a>
      </div>
      <div class="product-info">
        <h3 class="product-name">${p.nom}</h3>
        <p class="product-description">${p.description}</p>
        <p class="product-price">${p.prix.toFixed(2).replace('.', ',')} €</p>
      </div>
    `;

    conteneur.appendChild(carte);
  });
}
init();