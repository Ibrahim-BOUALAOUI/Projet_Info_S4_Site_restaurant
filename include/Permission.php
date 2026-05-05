<?php
function isAdmin($state){
    return strtolower($state) === "admin";
}

function isLivreur($state){
    return strtolower($state) === "livreur";
}

function isPreparateur($state){
    return strtolower($state) === "preparateur";
    }