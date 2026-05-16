let TousLesPlats = [];

const FiltresActif = { 
    type: [],
    saveur : [],
    regime : [],
    prix : []
};

// Je récupère les données du fichier JSON
async function init(){
    const reponse = await fetch("../Folder_Data/Menus.json");
    const data = await reponse.json();
    TousLesPlats = data.plats; //Je Fill mon tableau
    afficherPlats(TousLesPlats);
    //Récpuere l'information des checkboxes
    //Filtre sur le Type de nourriture
    document.querySelectorAll("#Menus, #sandwich, #extra, #boisson").forEach(checkboxe => {
      checkboxe.addEventListener("change", function() {
        FiltresActif.type = []; // On reconstruit le tableau à chaque changement
        
        document.querySelectorAll("#Menus, #sandwich, #extra, #boisson").forEach(cb=> {
        if (cb.checked){
          FiltresActif.type.push(cb.id); // Actualsation
        }
      });
      

      filtrerPlats();
      });
    });
    // Filtre sur le prix des menus
    document.querySelectorAll("#prix-0-5, #prix-5-10, #prix-10-plus").forEach(checkboxe =>{
      checkboxe.addEventListener("change", function() {
        FiltresActif.prix = []; // On reconstruit le tableau du prix
        document.querySelectorAll("#prix-0-5, #prix-5-10, #prix-10-plus").forEach(prix => {
          if (prix.checked){
            FiltresActif.prix.push(prix.id);
          }
        });
        filtrerPlats();
      });
    })
    document.querySelectorAll("#épicé, #doux, #salé, #sucré").forEach(checkbox => {
      checkbox.addEventListener("change", function() {
        FiltresActif.saveur = [];

        document.querySelectorAll("#épicé, #doux, #salé, #sucré").forEach(cb => {
          if (cb.checked) {
            FiltresActif.saveur.push(cb.id);
          }
        });

        filtrerPlats();
    });
  });

}
function setFiltre(type,valeur){ 
    //La fonction qui filtre en fonction du filtre en question
    FiltresActif[type] = valeur;
    filtrerPlats();
}

function filtrerPlats(){
  const resultats = TousLesPlats.filter(plat => {
    if (FiltresActif.type.length > 0 && !FiltresActif.type.includes(plat.type))
      return false;

    if (FiltresActif.saveur.length > 0 && !FiltresActif.saveur.includes(plat.saveur))
      return false;

    if (FiltresActif.regime.length > 0 && !FiltresActif.regime.some(r => plat.regime.includes(r)))
      return false;

    if (FiltresActif.prix.length > 0) {
      let prixOk = false;

      FiltresActif.prix.forEach(tranche => {
        if (tranche === "prix-0-5" && plat.prix >= 0 && plat.prix <= 5)
          prixOk = true;
        if (tranche === "prix-5-10" && plat.prix > 5 && plat.prix <= 10)
          prixOk = true;
        if (tranche === "prix-10-plus" && plat.prix > 10)
          console.log(plat.nom, plat.prix, plat.prix > 10);
        if (plat.prix > 10) prixOk = true;
      });

      if (!prixOk) return false;
    }

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