<?php
//connection base de données et execution de la requete
require "connexion_bdd.php";
$db = connexionBase();

//liste select référence
$requete = "SELECT cat_id,cat_nom  FROM categories ORDER BY cat_id asc";
$result1 = $db->query($requete);
$acategorie = $result1->fetchAll(PDO::FETCH_OBJ);

// Recup données pour formulaire
$pro_id = htmlspecialchars($_GET['pro_id']);
$requete = "SELECT * FROM produits WHERE pro_id=" . $pro_id;
$result = $db->query($requete);
$produit = $result->fetch(PDO::FETCH_OBJ);

// recup données pour la liste deroulante categorie du formulaire
$requete2 = "SELECT cat_id,cat_nom FROM categories WHERE cat_id=" . $produit->pro_cat_id;
$result2 = $db->query($requete2);
$categorie2 = $result2->fetch(PDO::FETCH_OBJ);
$result2->closecursor();

// regex
$regLibelle = '/^[\ \/_ \-A-Za-z0-9êéèçàäëï]*$/';
$regRef = '/^[\ \/_ \-A-Za-z0-9êéèçàäëï]*$/';
$regPrix = '/^[0-9]{1,6}(.[0-9]{2})$/';
$regStock = '/^[0-9]{1,11}$/';
$regCouleur = '/^[\ \/_ \-A-Za-z0-9êéèçàäëï]*$/';
$regDescrip = '/^[^<>\/]+[\w\W]{1,999}$/';

// messages erreurs
$A = "champs vide";
$B = "saisie incorrecte";
$C = "trop long";

//initialisation du tableau erreur
$messError = array();

//refresh la page sur click effacer
if (isset($_POST['effacer'])) {
    header('Refresh: 0', 'url=form_modif.php');
}

// Vérif du formulaire
if (isset($_POST['modifier'])) {

    // champ pro_ref
    if (!empty($_POST['pro_ref'])) {
        if (!preg_match($regRef, ($_POST['pro_ref']))) {
            $messError['pro_ref'] = $B;
        }
        if (strlen($_POST['pro_ref']) > 10) {
            $messError['pro_ref'] = $C;
        }
    } else {
        $messError['pro_ref'] = $A;
    }

    //champ pro_libelle
    if (!empty($_POST['pro_libelle'])) {
        if (!preg_match($regLibelle, ($_POST['pro_libelle']))) {
            $messError['pro_libelle'] = $B;
        }
        if (strlen($_POST['pro_libelle']) > 200) {
            $messError['por_libelle'] = $C;
        }
    } else {
        $messError['pro_libelle'] = $A;
    }

    //champ pro_prix
    if (!empty($_POST['pro_prix'])) {
        if (!preg_match($regPrix, ($_POST['pro_prix']))) {
            $messError['pro_prix'] = $B;
        }
        if (strlen($_POST['pro_prix']) > 9) {
            $messError['pro_prix'] = $C;
        }
    } else {
        $messError['pro_prix'] = $A;
    }

    //champ pro_stock
    if (!empty($_POST['pro_stock'])) {
        if (!preg_match($regStock, ($_POST['pro_stock']))) {
            $messError['pro_stock'] = $B;
        }
        if (strlen($_POST['pro_stock']) > 11) {
            $messError['pro_stock'] = $C;
        }
    } else {
        $messError['pro_stock'] = $A;
    }

    //champ pro_couleur
    if (!empty($_POST['pro_couleur'])) {
        if (!preg_match($regCouleur, ($_POST['pro_couleur']))) {
            $messError['pro_couleur'] = $B;
        }
        if (strlen($_POST['pro_couleur']) > 30) {
            $messError['pro_couleur'] = $C;
        }
    } else {
        $messError['pro_couleur'] = $A;
    }

    //champ pro_description
    if (!empty($_POST['pro_descrip'])) {
        if (!preg_match($regDescrip, ($_POST['pro_descrip']))) {
            $messError['pro_descrip'] = $B;
        }
        if (strlen($_POST['pro_descrip']) > 999) {
            $messError['pro_descrip'] = $C;
        }
    } else {
        $messError['pro_descrip'] = $A;
    }


    //Controle telechargement photo	( pas de verif photo absente )
    if (!empty($_FILES['fichier']['tmp_name'])) {
        $aMimeTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", "image/tiff"); // On met les types autorisés dans un tableau (ici pour une image)		
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $_FILES["fichier"]['tmp_name']);    // On extrait le type du fichier via l'extension FILE_INFO 
        finfo_close($finfo);
        if (!in_array($mimetype, $aMimeTypes)) {   // Le type n'est pas autorisé, donc ERREUR 
            $messError['photo'] = "Type de fichier non autorisé";
        }
    }
    $extention = substr(strrchr($_FILES['fichier']['name'], '.'), 1); //recup extention photo

    // requete insertion et photo rename/deplace
    if (count($messError) == 0) {
        // recup des valeur du POST formulaire ajout et traitement avec htmlspecialchars 
        $Id = htmlspecialchars($_POST['pro_id']);
        $categorie = htmlspecialchars($_POST['pro_cat_id']);
        $reference = htmlspecialchars($_POST['pro_ref']);
        $libelle = htmlspecialchars($_POST['pro_libelle']);
        $descript = htmlspecialchars($_POST['pro_descrip']);
        $prix = htmlspecialchars($_POST['pro_prix']);
        $stock = htmlspecialchars($_POST['pro_stock']);
        $couleur = htmlspecialchars($_POST['pro_couleur']);
        $bloque = htmlspecialchars($_POST['pro_bloque']);
        $ext = htmlspecialchars($extention);

        // date ajout généré par le système
        date_default_timezone_set('Europe/Paris');
        $date = new datetime();
        $modif = $date->format('Y-m-d H:i:s');

        //preparation et execution de la requete

        $requete = $db->prepare('UPDATE produits SET pro_cat_id = :categorie, pro_ref = :reference, pro_libelle = :libelle, pro_description = :descript, pro_prix = :prix ,pro_stock = :stock, pro_couleur = :couleur, pro_d_modif = :modif, pro_bloque = :bloque, pro_photo = :photo WHERE pro_id=' . $Id); // prepartion de la requete

        //liaison position / variable
        $requete->bindValue(':categorie', $categorie, PDO::PARAM_INT);
        $requete->bindValue(':reference', $reference, PDO::PARAM_STR);
        $requete->bindValue(':libelle', $libelle, PDO::PARAM_STR);
        $requete->bindValue(':descript', $descript, PDO::PARAM_STR);
        $requete->bindValue(':prix', $prix, PDO::PARAM_STR);
        $requete->bindValue(':stock', $stock, PDO::PARAM_INT);
        $requete->bindValue(':couleur', $couleur, PDO::PARAM_STR);
        $requete->bindvalue(':modif', $modif, PDO::PARAM_STR);
        $requete->bindValue(':bloque', $bloque, PDO::PARAM_INT);
        $requete->bindValue(':photo',$ext, PDO::PARAM_STR);
        $requete->execute();

        $requete->closecursor();

        // si changement photo renome et deplace la photo dans assets/images/  
        $photo = $Id . '.' . $ext;                                             //concatenation " pro_id.extention "
        move_uploaded_file($_FILES['fichier']['tmp_name'], '../assets/images/' . $photo);  // deplace la photo dans dossier assets/images

        header('location: ../form_liste_detail.php'); //redirection liste_detail   
    }
}
