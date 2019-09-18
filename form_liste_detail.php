<?php
include 'controleur/liste.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>form_Liste_détail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="row text-center">
            <div class="col table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>

                            <th class="align-middle">Photo</th>
                            <th class="align-middle">Id</th>
                            <th class="align-middle">Référence</th>
                            <th class="align-middle">Libellé</th>
                            <th class="align-middle">Prix</th>
                            <th class="align-middle">Stock</th>
                            <th class="align-middle">Couleur</th>
                            <th class="align-middle">Date ajout</th>
                            <th class="align-middle">Date modif</th>
                            <th class="align-middle">Produit bloqué</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch(PDO::FETCH_OBJ)) {

                        ?>
                            <tr>

                                <td class="align-middle">
                                    <img class="img-responsive" style="width: 80px; height: 80px;" src="assets/images/<?= $row->pro_id . '.' . $row->pro_photo ?>" alt="" title="<?= $row->pro_libelle ?>">
                                </td>
                                <td class="align-middle"><?= $row->pro_id ?></td>
                                <td class="align-middle"><?= $row->pro_ref ?></td>
                                <td class="align-middle"><a href="view/form_detail.php?pro_id=<?= $row->pro_id ?>" title="<?= $row->pro_libelle ?>"><?= $row->pro_libelle ?></a></td>
                                <td class="align-middle"><?= $row->pro_prix ?></td>
                                <td class="align-middle"><?= $row->pro_stock ?></td>
                                <td class="align-middle"><?= $row->pro_couleur ?></td>
                                <td class="align-middle"><?= date("d/m/Y", strtotime($row->pro_d_ajout)); ?></td>
                                <td class="align-middle"><?= $row->pro_d_modif ? date("d/m/Y H:i:s", strtotime($row->pro_d_modif)):''; ?></td>
                                <td class="align-middle"><?= $row->pro_bloque ? "oui" : "non" ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>