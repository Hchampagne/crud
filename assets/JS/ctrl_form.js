
$(document).ready(function () {                       // initialise JQUERY au chargement du document

    //REGEX
    var regLibelle = /^[\ \/_ \-A-Za-z0-9êéèçàäëï]*$/	
    var regRef = /^[\ \/_ \-A-Za-z0-9êéèçàäëï]*$/
    var regPrix = /^[0-9]{1,6}(.[0-9]{2})$/
    var regStock = /^[0-9]{1,11}$/
    var regCouleur = /^[\ \/_ \-A-Za-z0-9êéèçàäëï]*$/
    var regDescrip = /^[^<>\/]+[\w\W]{1,999}$/

    // champ pro_ref  
    $('#pro_ref').blur(function () {
        if ($('#pro_ref').val() == '') {
            $('#alertRef').text("Le champs n'est pas rempli");          
        } else if (regRef.test($('#pro_ref').val()) == false) {
            $('#alertRef').text("saisie incorrecte");                     
        } else if($('#pro_ref').val().length > 10){
            $('#alertRef').text("trop long");
       }
        else {               
           $('#alertRef').html('&nbsp');   
                }
     });

    //controle doublons référence (ajax_verifRef.php)
    $('#pro_ref').change(function(){
        $.post({
             url: "../controleur/ajax_verifRef.php",
             data: {verifRef: $("#pro_ref").val()},
                success:   function(data){
                if (data == 1){
                    $("#alertRef").text("dèjà utilisée");
                }else{
                    $("#alertRef").html("&nbsp");
               }
            }
        });
    });

    // champ pro_libelle 
    $('#pro_libelle').blur(function () {
        if ($('#pro_libelle').val() == '') {
            $('#alertLibelle').text("Le champs n'est pas rempli");
        } else if (regLibelle.test($('#pro_libelle').val()) == false) {
            $('#alertLibelle').text("saisie incorrecte");
        } else if ($('#pro_libelle').val().length > 200) {
            $('#alertLibelle').text("trop long")
        } else {
            $('#alertLibelle').html('&nbsp');
        }
    });

     // champ pro_prix 
     $('#pro_prix').blur(function () {
         if ($('#pro_prix').val() == '') {
             $('#alertPrix').text("Le champs n'est pas rempli");
         } else if (regPrix.test($('#pro_prix').val()) == false) {
             $('#alertPrix').text("saisie incorrecte");
         } else if ($('#pro_prix').val().length > 9) {
             $('#alertPrix').text("trop long")
         } else {
             $('#alertPrix').html('&nbsp');
         }
     });

      // champ pro_stock
      $('#pro_stock').blur(function () {
          if ($('#pro_stock').val() == '') {
              $('#alertStock').text("Le champs n'est pas rempli");
          } else if (regStock.test($('#pro_stock').val()) == false) {
              $('#alertStock').text("saisie incorrecte");
          } else if ($('#pro_stock').val().length > 11) {
              $('#alertStock').text("trop long")
          } else {
              $('#alertStock').html('&nbsp');
          }
      });

     // champ pro_couleur
     $('#pro_couleur').blur(function () {
         if ($('#pro_couleur').val() == '') {
             $('#alertCouleur').text("Le champs n'est pas rempli");
         } else if (regCouleur.test($('#pro_couleur').val()) == false) {
             $('#alertCouleur').text("saisie incorrecte");
         } else if ($('#pro_couleur').val().length > 30) {
             $('#alertCouleur').text("trop long")
         } else {
             $('#alertCouleur').html('&nbsp');
         }
     });

      // champ pro_description
      $('#pro_descrip').blur(function () {
          if ($('#pro_descrip').val() == '') {
              $('#alertDescrip').text("Le champs n'est pas rempli");
          } else if (regDescrip.test($('#pro_descrip').val()) == false) {
              $('#alertDescrip').text("saisie incorrecte");
          } else if ($('#pro_descrip').val().length > 999) {
              $('#alertDescrip').text("trop long")
          } else {
              $('#alertDescrip').html('&nbsp');
          }
      });

});


