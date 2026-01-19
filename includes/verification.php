<?php

function verification_champs($login, $prenom, $nom, $password, $confirm_password){
    if (strlen($login) < 3 || strlen($login) > 255) {
        return "Le login doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($prenom) < 3 || strlen($prenom) > 255) {
        return "Le prenom doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($nom) < 3 || strlen($nom) > 255) {
        return "Le nom doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($password) < 6 || !preg_match('/[0-9]/', $password)) {
        return "Le mot de passe doit contenir au moins 6 caractères et au moins un chiffre";
    }
    elseif ($password != $confirm_password) {
        return "Les deux mots de passe doivent être égaux";
    }
    return true;
}


?>