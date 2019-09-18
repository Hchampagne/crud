<?php

require "connexion_bdd.php"; // Inclusion de notre bibliothèque de fonctions	
$db = connexionBase(); // Appel de la fonction de connexion
$Id = htmlspecialchars($_GET['pro_id']);
$requete = 'DELETE FROM produits WHERE Pro_id='.$Id;
$result = $db->query($requete);


header("location: ../form_liste_detail.php"); //redirection liste_detail
?>