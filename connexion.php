<!DOCTYPE html>
<html lang="fr">
<?php
require 'bdd.php';
require "menu.php";
require "mesfonctions.php";
try {
    //verification que personne n'est co
    if (!isset($_SESSION['id'])) {
        //form connexion
        if (isset($_POST['formconnexion'])) {
            $mailconnect = htmlentities($_POST['mailconnect']);
            $mdpconnect = hash("sha256", $_POST['mdpconnect']);
            //si données sont entrées
            if (!empty($mailconnect) and !empty($mdpconnect)) {
                $requser = $pdo->prepare("SELECT * FROM membres WHERE memb_mail = ? AND memb_mdp = ? OR memb_pseudo = ? AND memb_mdp = ?");
                $requser->execute(array($mailconnect, $mdpconnect, $mailconnect, $mdpconnect));
                $userexist = $requser->rowCount();
                //si user existe avec identifiant et mdp 
                if ($userexist == 1) {
                    $userinfo = $requser->fetch();
                    $_SESSION['id'] = $userinfo['memb_id'];
                    setcookie("id", $userinfo['memb_id'], time() + 60 * 60 * 24 * 100, '/');
                    $_SESSION['success'] = "Connexion avec succès !";
                    header("Location: index.php");
                    exit();
                } else {
                    $_SESSION['erreur'] = "Mauvais identifiant ou mot de passe !";
                }
            } else {
                $_SESSION['erreur'] = "Tous les champs doivent être complétés !";
            }
            header("location: connexion.php");
            exit();
        }

        //form inscription
        if (isset($_POST['forminscription'])) {
            $nom = strtoupper($_POST['nom']);
            $prenom = ucwords($_POST['prenom']);
            $prenompseudo = str_replace(' ', '', $_POST['prenom']);
            $nompseudo = str_replace(' ', '', $_POST['nom']);
            $pseudo = strtolower(remove_accents($prenompseudo)) . "." . strtolower(remove_accents($nompseudo));
            $mail = strtolower(htmlentities($_POST['mail']));
            $mail2 = strtolower(htmlentities($_POST['mail2']));
            $mdp = hash("sha256", $_POST['mdp']);
            $mdp2 = hash("sha256", $_POST['mdp2']);
            //si données sont entrées
            if (!ctype_space($nom) and !ctype_space($prenom)) {
                //si même mail
                if ($mail == $mail2) {
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        $reqmail = $pdo->prepare("SELECT * FROM membres WHERE memb_mail = ?");
                        $reqmail->execute(array($mail));
                        $mailexist = $reqmail->rowCount();

                        $reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE memb_pseudo = ?");
                        $reqpseudo->execute(array($pseudo));
                        $pseudoexist = $reqpseudo->rowCount();
                        //si mail et pseudo pas déjà utilisé
                        if ($mailexist == 0) {
                            $i = 1;
                            $pseudobase = $pseudo;
                            while ($pseudoexist != 0) {
                                $pseudo = $pseudobase . strval($i);

                                $reqpseudo = $pdo->prepare("SELECT * FROM membres WHERE memb_pseudo = ?");
                                $reqpseudo->execute(array($pseudo));
                                $pseudoexist = $reqpseudo->rowCount();
                                $i++;
                            }
                            //si mdp valide
                            if ($mdp == $mdp2) {
                                //si mdp min 6 charactere
                                if (strlen($_POST['mdp']) >= 6) {
                                    //si mdp pas d'espaces
                                    if (preg_match('/\s/', $_POST['mdp'])  == 0) {
                                        $insertmbr = $pdo->prepare("INSERT INTO membres(memb_nom, memb_prenom, memb_pseudo, memb_mail, memb_mdp) VALUES (?,?,?,?,?)");
                                        $insertmbr->execute(array($nom, $prenom, $pseudo, $mail, $mdp));
                                        $_SESSION['success'] = "Votre compte a bien été crée !";
                                    } else {
                                        $_SESSION['erreur'] = "Votre mot de passe ne doit pas contenir d'espaces";
                                    }
                                } else {
                                    $_SESSION['erreur'] = "Votre mot de passe doit avoir un minimum de 6 caractères";
                                }
                            } else {
                                $_SESSION['erreur'] = "Vos mots de passe ne correspondent pas";
                            }
                        } else {
                            $_SESSION['erreur'] = "Adresse mail déjà utilisée !";
                        }
                    } else {
                        $_SESSION['erreur'] = "Votre adresse mail n'est pas valide !";
                    }
                } else {
                    $_SESSION['erreur'] = "Vos adresses mail ne correspondent pas !";
                }
            } else {
                $_SESSION['erreur'] = "Tous les champs doivent être complétés !";
            }
            header("location: connexion.php");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Connexion</title>
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
                        if (isset($_SESSION['success'])) {
                            echo "<div class='alert alert-success' role='alert'>";
                            echo $_SESSION['success'];
                            echo "</div>";
                            unset($_SESSION['success']);
                        }
                        ?>
                    </div>
                    <div class="col-xl-1">
                    </div>

                    <div class="col-xl-1">
                    </div>
                    <div class="col-xl-5">
                        <h1>Connexion</h1>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="mailconnect" class="form-label required">Mail ou nom d'utilisateur</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="mailconnect" name="mailconnect" placeholder="Mail ou nom d'utilisateur" required>
                                </div>
                            </div>
                            <div class=" mb-3">
                                <label for="mdpconnect" class="form-label required">Mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="mdpconnect" name="mdpconnect" placeholder="Mot de passe" required>
                                </div>
                            </div>
<!--                             <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input">
                                <label class="form-check-label" for="exampleCheck1">Se souvenir de moi</label>
                            </div> -->
                            <button type="submit" class="btn btn-primary" id="formconnexion" name="formconnexion">Se connecter</button>
                        </form>
                    </div>
                    <div class="col-xl-5">
                        <h1>Inscription</h1>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nom" class="form-label required">Nom</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label required">Prénom</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mail" class="form-label required">Mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="mail" name="mail" placeholder="Mail" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mail2" class="form-label required">Confirmer mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="mail2" name="mail2" placeholder="Confirmer votre mail" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mdp" class="form-label required">Mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mdp2" class="form-label required">Confirmer mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="mdp2" name="mdp2" placeholder="Confirmer votre mot de passe" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="forminscription" name="forminscription">S'inscrire</button>
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
        header("location: index.php");
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
?>