<!DOCTYPE html>
<html lang="fr">
<head>
       <link rel="stylesheet" id="style-sombre" href="">
    <script src="../Folder_JS/affichage.js" defer></script>
    <link rel="stylesheet" href="../Folder CSS/Avis.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis</title>
</head>
<body>
    <header>
        <img src="../Folder img/129.png" alt="Logo" width="250">
    </header>


    <section class="avis-form">
        <div class="card">
            <h2>Donnez votre avis</h2>
            <p class="subtitle">
                Votre expérience compte pour nous.
            </p>

            <form>
                <div class="form-group">
                    <label class="label-title">Votre note du plat</label>
                    <div class="rating">
                        <input value="5" name="rate" id="star5" type="radio">
                        <label title="5 étoiles" for="star5"></label>
                        <input value="4" name="rate" id="star4" type="radio">
                        <label title="4 étoiles" for="star4"></label>
                        <input value="3" name="rate" id="star3" type="radio" checked>
                        <label title="3 étoiles" for="star3"></label>
                        <input value="2" name="rate" id="star2" type="radio">
                        <label title="2 étoiles" for="star2"></label>
                        <input value="1" name="rate" id="star1" type="radio">
                        <label title="1 étoile" for="star1"></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-title">Note de la livraison</label>
                    <div class="livraison-rating">
                        <div class="radio-option">
                            <input type="radio" name="livraison" id="liv1" value="1">
                            <label for="liv1">1</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="livraison" id="liv2" value="2">
                            <label for="liv2">2</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="livraison" id="liv3" value="3">
                            <label for="liv3">3</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="livraison" id="liv4" value="4">
                            <label for="liv4">4</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="livraison" id="liv5" value="5">
                            <label for="liv5">5</label>
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="label-title" for="commentaire">Votre avis</label>
                    <textarea id="commentaire" placeholder="Décrivez votre expérience..."></textarea>
                </div>

                <div class="form-group">
                    <label class="label-title" for="prenom">Prénom (facultatif)</label>
                    <input type="text" id="prenom" placeholder="Claire">
                </div>

                <button class="btn" type="submit">Envoyer mon avis</button> 
            </form>
        </div>
    </section>
</body>
</html>
