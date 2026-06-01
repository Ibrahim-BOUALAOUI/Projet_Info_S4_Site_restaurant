<?php
// Vérifie si le rôle stocké en session correspond à un administrateur.
function isAdmin($state){
    return strtolower($state) === "admin";
}

// Vérifie si le rôle correspond à un livreur.
function isLivreur($state){
    return strtolower($state) === "livreur";
}

// Vérifie si le rôle correspond à un préparateur.
function isPreparateur($state){
    return strtolower($state) === "preparateur";
    }
