<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id'])) {
        if ($requete['memb_admin'] == 3) {
            $statut = "Super Administrateur";
        } else if ($requete['memb_admin'] == 2) {
            $statut = "Administrateur";
        } else if ($requete['memb_admin'] == 1) {
            $statut = "Utilisateur";
        } else {
            $statut = "Visiteur";
        }
        // changement sur profil
        if (isset($_POST['form_profil'])) {
            $newnom = strtoupper($_POST['newnom']);
            $newprenom = ucwords($_POST['newprenom']);
            //changement nom
            if (isset($_POST['newnom']) and $_POST['newnom'] != $requete['memb_nom']) {
                if (!ctype_space($newnom)) {
                    $insertnom = $pdo->prepare("UPDATE membres SET memb_nom = ? WHERE memb_id = ?");
                    $insertnom->execute(array($newnom, $_SESSION['id']));
                    $_SESSION['succes'] = "Changement(s) effectué(s)";
                } else {
                    $_SESSION['erreur'] = "Entrez un nom valide !";
                }
            }

            //changement prenom
            if (isset($_POST['newprenom']) and $_POST['newprenom'] != $requete['memb_prenom']) {
                if (!ctype_space($newprenom)) {
                    $insertprenom = $pdo->prepare("UPDATE membres SET memb_prenom = ? WHERE memb_id = ?");
                    $insertprenom->execute(array($newprenom, $_SESSION['id']));
                    $_SESSION['succes'] = "Changement(s) effectué(s)";
                } else {
                    $_SESSION['erreur'] = "Entrez un nom valide !";
                }
            }

            // changement pseudo si changement de nom ou prenom
            if ($_POST['newnom'] != $requete['memb_nom'] or $_POST['newprenom'] != $requete['memb_prenom']) {
                $newnom = str_replace(' ', '', $_POST['newnom']);
                $newprenom = str_replace(' ', '', $_POST['newprenom']);
                $pseudo = strtolower(remove_accents($newprenom)) . "." . strtolower(remove_accents($newnom));
                if ($requete['memb_pseudo'] != $pseudo) {
                    $reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE memb_pseudo = ?");
                    $reqpseudo->execute(array($pseudo));
                    $pseudoexist = $reqpseudo->rowCount();
                    $i = 1;
                    $pseudobase = $pseudo;
                    while ($pseudoexist != 0) {
                        $pseudo = $pseudobase . strval($i);

                        $reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE memb_pseudo = ?");
                        $reqpseudo->execute(array($pseudo));
                        $pseudoexist = $reqpseudo->rowCount();
                        $i++;
                    }
                    $insertpseudo = $pdo->prepare("UPDATE membres SET memb_pseudo = ? WHERE memb_id = ?");
                    $insertpseudo->execute(array($pseudo, $_SESSION['id']));
                    $_SESSION['succes'] = "Changement(s) effectué(s)";
                }
            }

            //changement mail
            if (isset($_POST['newmail']) and $_POST['newmail'] != $requete['memb_mail']) {
                if (filter_var($_POST['newmail'], FILTER_VALIDATE_EMAIL)) {
                    $mail = htmlentities($_POST['newmail']);
                    $reqmail = $pdo->prepare("SELECT * FROM membres WHERE memb_mail = ?");
                    $reqmail->execute(array($mail));
                    $mailexist = $reqmail->rowCount();
                    if ($mailexist == 0) {
                        $newmail = htmlentities($_POST['newmail']);
                        $insertmail = $pdo->prepare("UPDATE membres SET memb_mail = ? WHERE memb_id = ?");
                        $insertmail->execute(array($newmail, $_SESSION['id']));
                        $_SESSION['succes'] = "Changement(s) effectué(s)";
                    } else {
                        $_SESSION['erreur'] = "Mail indisponible";
                    }
                } else {
                    $_SESSION['erreur'] = "Votre adresse mail n'est pas valide !";
                }
            }

            //changement mdp
            if (isset($_POST['newmdp0']) and !empty($_POST['newmdp0']) or isset($_POST['newmdp1']) and !empty($_POST['newmdp1']) or isset($_POST['newmdp2']) and !empty($_POST['newmdp2 '])) {
                $mdp0 = hash("sha256", $_POST['newmdp0']);
                $mdp1 = hash("sha256", $_POST['newmdp1']);
                $mdp2 = hash("sha256", $_POST['newmdp2']);
                if (!empty($mdp0) and !empty($mdp1) and !empty($mdp2)) {
                    if ($mdp0 == $requete['memb_mdp']) {
                        if ($mdp1 == $mdp2) {
                            $mdplength = strlen($_POST['newmdp1']);
                            if ($mdplength >= 6) {
                                if (preg_match('/\s/', $_POST['newmdp1'])  == 0) {
                                    $insertmdp = $pdo->prepare("UPDATE membres SET memb_mdp = ? WHERE memb_id = ?");
                                    $insertmdp->execute(array($mdp1, $_SESSION['id']));
                                    $_SESSION['succes'] = "Changement(s) effectué(s)";
                                } else {
                                    $_SESSION['erreur'] = "Votre mot de passe ne doit pas contenir d'espaces";
                                }
                            } else {
                                $_SESSION['erreur'] = "Votre mot de passe doit avoir un minimum de 6 caractères";
                            }
                        } else {
                            $_SESSION['erreur'] = "Vos deux mdp ne correspondent pas !";
                        }
                    } else {
                        $_SESSION['erreur'] = "Mauvais mot de passe actuel !";
                    }
                } else {
                    $_SESSION['erreur'] = "Tous les champs doivent être complétés !";
                }
            }
            header("location: profil.php");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Mon profil</title>
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
                        <h1>Mon profil</h1>
                        <br>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="newstatut" class="form-label">Statut</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user-shield"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="newstatut" name="newstatut" placeholder="Statut" value="<?php echo $statut ?>" readonly>
                                </div>
                            </div>
                            <div class=" mb-3">
                                <label for="newpseudo" class="form-label">Nom d'utilisateur</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="newpseudo" name="newpseudo" placeholder="Nom d'utilisateur" value="<?php echo $requete['memb_pseudo'] ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="newnom" class="form-label required">Nom</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="newnom" name="newnom" placeholder="Nom" value="<?php echo $requete['memb_nom'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="newprenom" class="form-label required">Prénom</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="newprenom" name="newprenom" placeholder="Prénom" value="<?php echo $requete['memb_prenom'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="newmail" class="form-label required">Mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="newmail" name="newmail" placeholder="Mail" value="<?php echo $requete['memb_mail'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="newmdp0" class="form-label required">Mot de passe actuel</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="newmdp0" name="newmdp0" placeholder="Mot de passe actuel">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="newmdp1" class="form-label required">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="newmdp1" name="newmdp1" placeholder="Nouveau mot de passe">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="newmdp2" class="form-label required">Confirmer nouveau mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="newmdp2" name="newmdp2" placeholder="Confirmer nouveau mot de passe">
                                </div>
                            </div>
                            <div class="mb-3">
                                <br>
                                <button type="submit" class="btn btn-primary button_w100" id="form_profil" name="form_profil">Modifier</button>
                            </div>
                        </form>
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