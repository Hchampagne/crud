<?php
require "../controleur/connexion_bdd.php"; // Inclusion de notre bibliothèque de fonctions
$db = connexionBase(); // Appel de la fonction de connexion
$pro_id = htmlspecialchars($_GET["pro_id"]);
$requete = "SELECT * FROM produits WHERE pro_id=" . $pro_id;
$result = $db->query($requete);
// Renvoi de l'enregistrement sous forme d'un objet
$produit = $result->fetch(PDO::FETCH_OBJ);

$requete2 = "SELECT cat_id,cat_nom FROM categories where cat_id=".$produit->pro_cat_id;
$result2 = $db->query($requete2);
$categorie2 = $result2->fetch(PDO::FETCH_OBJ);

$result->closecursor();

?>