<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_GET['id'])) {
        $id_membre = $_GET['id'];
        if($id_membre == $id) {
            exit(header('location: profil.php'));
        }
    }
    if (isset($_SESSION['id']) and $id_admin > 2) {
        if (isset($_GET['id'])) {
            $id_membre = $_GET['id'];

            $requete_membre = $pdo->prepare("SELECT * FROM membres WHERE memb_id = ?");
            $requete_membre->execute(array($id_membre));
            $requete_membre = $requete_membre->fetch();

            $memb_admin = $requete_membre['memb_admin'];
            $memb_pseudo = $requete_membre['memb_pseudo'];
            $memb_nom = $requete_membre['memb_nom'];
            $memb_prenom = $requete_membre['memb_prenom'];
            $memb_mail = $requete_membre['memb_mail'];

            if ($memb_admin == 3) {
                $memb_admin = "Super Administrateur";
            } else if ($memb_admin == 2) {
                $memb_admin = "Administrateur";
            } else if ($memb_admin == 1) {
                $memb_admin = "Utilisateur";
            } else {
                $memb_admin = "Visiteur";
            }
        }
?>

        <head>
            <title>MFR BDD - <?php echo $memb_nom . " " . $memb_prenom ?></title>
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
                        <h1><?php echo $memb_nom . " " . $memb_prenom ?></h1>
                        <br>

                        <div class="mb-3">
                            <label for="newstatut" class="form-label">Statut</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-user-shield"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newstatut" name="newstatut" placeholder="Statut" value="<?php echo $memb_admin ?>" readonly>
                            </div>
                        </div>
                        <div class=" mb-3">
                            <label for="newpseudo" class="form-label">Nom d'utilisateur</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newpseudo" name="newpseudo" placeholder="Nom d'utilisateur" value="<?php echo $requete_membre['memb_pseudo'] ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newnom" class="form-label required">Nom</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newnom" name="newnom" placeholder="Nom" value="<?php echo $requete_membre['memb_nom'] ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newprenom" class="form-label required">Prénom</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" id="newprenom" name="newprenom" placeholder="Prénom" value="<?php echo $requete_membre['memb_prenom'] ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newmail" class="form-label required">Mail</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" id="newmail" name="newmail" placeholder="Mail" value="<?php echo $requete_membre['memb_mail'] ?>" readonly>
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger button_w100 margin_100" data-toggle="modal" data-target="#exampleModal">Supprimer ce membre</button>

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
                                        Êtes-vous sûr de vouloir supprimer ce membre toutes les données qui lui sont liées ?<br><br>Cette action est irréversible
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                        <form action="delete.php" method="GET">
                                            <button name="id_membre" value="<?php echo $_GET['id'] ?>" type="submit" class="btn btn-danger">Oui</button>
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