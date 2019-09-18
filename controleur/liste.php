<?php	
    require "connexion_bdd.php"; // Inclusion de notre bibliothèque de fonctions	
    $db = connexionBase(); // Appel de la fonction de connexion	
    $requete = "SELECT * FROM produits  ORDER BY pro_id asc";		
    $result = $db->query($requete);		
?>