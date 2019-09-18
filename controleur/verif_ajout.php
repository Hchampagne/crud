<?php
//connection base de données et execution de la requete
require "connexion_bdd.php"; // Inclusion de notre bibliothèque de fonctions	
$db = connexionBase(); // Appel de la fonction de connexion

// liste select pour référence
$requete = "SELECT cat_id,cat_nom  FROM categories ORDER BY cat_id asc";
$result = $db->query($requete);
$acategorie = $result->fetchAll(PDO::FETCH_OBJ);

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

//Initialisation du tableau erreurs
$messError = array();

//refresh la page sur click effacer
if(isset($_POST['effacer'])){
    header('Refresh: 0','url=form_ajout.php');
}

// Vérif du formulaire
if (isset($_POST['ajouter'])){

    // REQUETE DB verif ref déjà utiliser
    $pro_ref = htmlspecialchars($_POST['pro_ref']);
    $req = $db->prepare("SELECT COUNT(pro_ref) as nb FROM produits WHERE  pro_ref= :proref"); //prep. requete 
    //liaison position variable
    $req->bindValue(':proref', $pro_ref, PDO::PARAM_STR);
    $req->execute();
    $nb = $req->fetch(PDO::FETCH_OBJ);
    $verifref = $nb->nb;
    
    // champ pro_ref
    if (!empty($_POST['pro_ref'] )) { 
        if (!preg_match($regRef, ($_POST['pro_ref']))) {
            $messError['pro_ref'] = $B;
        }
        if (strlen($_POST['pro_ref']) > 10  ){
            $messError['pro_ref'] = $C;
        }
        if ( $verifref != false){
            $messError['pro_ref'] = "Dejà utilisée";
        }
    }else {
        $messError['pro_ref'] = $A;
    }
    
    //champ pro_libelle
    if (!empty($_POST['pro_libelle'])){
        if (!preg_match($regLibelle, ($_POST['pro_libelle']))) {
        $messError['pro_libelle'] = $B;
        }  
        if (strlen($_POST['pro_libelle']) > 200) {
        $messError['por_libelle'] = $C;
        } 
    }else{
        $messError['pro_libelle'] = $A;
        }

    //champ pro_prix
    if (!empty($_POST['pro_prix'])){       
        if (!preg_match($regPrix, ($_POST['pro_prix']))) {
        $messError['pro_prix'] = $B;
        }
        if (strlen($_POST['pro_prix']) > 9){
        $messError['pro_prix'] = $C;
        } 
    }else{
        $messError['pro_prix'] = $A;
    }  

    //champ pro_stock
    if(!empty($_POST['pro_stock'])) {      
        if (!preg_match($regStock, ($_POST['pro_stock']))) {
        $messError['pro_stock'] = $B;
        }
        if (strlen($_POST['pro_stock']) > 11) {
        $messError['pro_stock'] = $C;
        } 
    }else {
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
    }else{
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
    }else{
        $messError['pro_descrip'] = $A;
    }

   //Controle telechargement photo	
    if (!empty($_FILES['fichier']['tmp_name'])) {
      
        $aMimeTypes = array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", "image/tiff"); // On met les types autorisés dans un tableau (ici pour une image)		
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $_FILES["fichier"]['tmp_name']);    // On extrait le type du fichier via l'extension FILE_INFO 
        finfo_close($finfo);
        if (!in_array($mimetype, $aMimeTypes)) {   // Le type n'est pas autorisé, donc ERREUR 
            $messError['photo'] = "Type de fichier non autorisé";
        }
    }else{
        $messError['photo'] = "photo absente";
    }
    $extention = substr(strrchr($_FILES['fichier']['name'], '.'), 1); 
     
    // requete insertion et photo rename/deplace
    if (count($messError) == 0){ // si tableau erreur vide 
        
        // recup des valeur du POST formulaire ajout
        $categorie = htmlspecialchars($_POST['pro_cat_id']);
        $reference = htmlspecialchars($_POST['pro_ref']);
        $libelle = htmlspecialchars($_POST['pro_libelle']);
        $descript = htmlspecialchars($_POST['pro_descrip']);
        $prix = htmlspecialchars($_POST['pro_prix']);
        $stock = htmlspecialchars($_POST['pro_stock']);
        $couleur = htmlspecialchars($_POST['pro_couleur']);
        $bloque = htmlspecialchars($_POST['pro_bloque']);
        $ext =htmlspecialchars($extention);

        // date ajout généré par le système
        date_default_timezone_set('Europe/Paris');
        $date = new datetime();
        $ajout = $date->format('Y-m-d');

        //prepare la requete
        $requete = $db->prepare("INSERT INTO produits (pro_cat_id,pro_ref,pro_libelle,pro_description,pro_prix,pro_stock,pro_couleur,pro_photo,pro_d_ajout,pro_bloque)
        VALUES(:categorie,:reference,:libelle,:descript,:prix,:stock,:couleur,:photo,:ajout,:bloque)"); 

        //liaison position variable
        $requete->bindValue(':categorie', $categorie, PDO::PARAM_INT);
        $requete->bindValue(':reference', $reference, PDO::PARAM_STR);
        $requete->bindValue(':libelle', $libelle, PDO::PARAM_STR);
        $requete->bindValue(':descript', $descript, PDO::PARAM_STR);
        $requete->bindValue(':prix', $prix, PDO::PARAM_STR);
        $requete->bindValue(':stock', $stock, PDO::PARAM_INT);
        $requete->bindValue(':couleur', $couleur, PDO::PARAM_STR);
        $requete->bindValue(':ajout', $ajout, PDO::PARAM_STR);
        $requete->bindValue(':bloque', $bloque, PDO::PARAM_INT);
        $requete->bindValue(':photo',$ext, PDO::PARAM_STR);
        $requete->execute();
        $requete->closecursor();

        //renome et deplace la photo ds assets/images/
        $lastId = $db->lastInsertId(); //recup id photo
         //Extention vérifier on renome la photo
        $photo = $lastId . '.' . $extention;                                             //concatenation " pro_id.extention "
        move_uploaded_file($_FILES['fichier']['tmp_name'], '../assets/images/' . $photo);  // deplace la photo dans dossier assets/images

       header('location: ../form_liste_detail.php'); //redirection liste_detail   
    }
}
?>  