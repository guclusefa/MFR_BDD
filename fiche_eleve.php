<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) and $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_eleve = $_GET['id'];

            $requete_eleve = $pdo->prepare("SELECT * FROM eleves WHERE eleve_id = ?");
            $requete_eleve->execute(array($id_eleve));
            $requete_eleve = $requete_eleve->fetch();
            $eleve_alt = requeteSQL($pdo, "SELECT * FROM alternances WHERE alt_idEleve = $id_eleve", "alt_id");

            $eleve_nom = $requete_eleve['eleve_nom'];
            $eleve_prenom = $requete_eleve['eleve_prenom'];
            $eleve_mailperso = $requete_eleve['eleve_mailperso'];
            $eleve_mailpro = $requete_eleve['eleve_mailpro'];
            $eleve_telperso = $requete_eleve['eleve_telperso'];
            $eleve_telpro = $requete_eleve['eleve_telpro'];
        } else {
            $id_etab = requeteSQL($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial", "etab_id");
        }
        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM sites_entreprise WHERE site_id = $id_site", "site_idEtab");
        }
        if (isset($_POST['form_eleve_form'])) {
            if (!empty($_POST['newnom']) and !empty($_POST['newprenom'])) {
                $newnom = htmlentities($_POST['newnom']);
                $newprenom = htmlentities($_POST['newprenom']);
                $newtel = htmlentities($_POST['newtel']);
                $newtelpro = htmlentities($_POST['newtelpro']);
                $newmail = htmlentities($_POST['newmail']);
                $newmailpro = htmlentities($_POST['newmailpro']);

                $insert_eleve = $pdo->prepare("UPDATE eleves SET eleve_nom = ?, eleve_prenom = ?, eleve_mailperso = ?, eleve_mailpro = ?, eleve_telperso = ?, eleve_telpro = ? WHERE eleve_id = $id_eleve");
                $insert_eleve->execute(array($newnom, $newprenom, $newmail, $newmailpro, $newtel, $newtelpro));
                $_SESSION['succes'] = "Informations de l'élève modifiée avec succèes";
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: fiche_eleve?id=$id_eleve");
                exit();
            }
            header("location: fiche_eleve?id=$id_eleve");
            exit();
        }
?>

        <head>
            <title>MFR BDD - <?php echo $eleve_nom . " " . $eleve_prenom ?></title>
        </head>

        <body>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-1">
                    </div>
                    <div class="col-xl-10">
                        <?php
                        if (isset($_SESSION['erreur'])) {
                            echo "<div class='alert alert-danger' role='alert'>";
                            echo $_SESSION['erreur'];
                            echo "</div>";
                            unset($_SESSION['erreur']);
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['succes'])) {
                            echo "<div class='alert alert-success' role='alert'>";
                            echo $_SESSION['succes'];
                            echo "</div>";
                            unset($_SESSION['succes']);
                        }
                        ?>
                        <h1><?php echo $eleve_nom . " " . $eleve_prenom ?></h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newnom" class="form-label required">Nom</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" placeholder="Nom" name="newnom" id="newnom" class="form-control" value="<?php if (isset($eleve_nom)) echo $eleve_nom ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newprenom" class="form-label required">Prénom</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" placeholder="Prénom" name="newprenom" id="newprenom" class="form-control" value="<?php if (isset($eleve_prenom)) echo $eleve_prenom ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newmail" class="form-label">Mail personnel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="mailto:<?php echo $eleve_mailperso ?>"><i class="fas fa-envelope"></i></a></span>
                                        </div>
                                        <input type="email" placeholder="Mail personnel" name="newmail" id="newmail" class="form-control" value="<?php if (isset($eleve_mailperso)) echo $eleve_mailperso ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newmailpro" class="form-label">Mail professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="mailto:<?php echo $eleve_mailpro ?>"><i class="fas fa-envelope"></i></a></span>
                                        </div>
                                        <input type="email" placeholder="Mail professionel" name="newmailpro" id="newmailpro" class="form-control" value="<?php if (isset($eleve_mailpro)) echo $eleve_mailpro ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newtel" class="form-label">Tél personnel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél personnel" name="newtel" id="newtel" class="form-control" value="<?php if (isset($eleve_telperso)) echo $eleve_telperso ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newtelpro" class="form-label">Tél professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél professionel" name="newtelpro" id="newtelpro" class="form-control" value="<?php if (isset($eleve_mailperso)) echo $eleve_telpro ?>">
                                    </div>
                                </div>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_eleve_form" name="form_eleve_form">Modifier</button>
                        </form>

                        <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer cet élève</button>

                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cet élève d'alternance et toutes les données qui lui sont liées ?<br><br>
                                        Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                        <form action="delete.php" method="GET">
                                            <button name="id_alt" value="<?php echo $eleve_alt ?>" type="submit" class="btn btn-danger">Oui</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-1">
                    </div>
                </div>
            </div>
        </body>
        <?php
        require 'footer.php';
        ?>

</html>
<?php
    } else {
        $_SESSION['erreur'] = "Vous n'avez pas les droits !";
        header("location: index.php");
        exit();
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
?>