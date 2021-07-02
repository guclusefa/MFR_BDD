<!DOCTYPE html>
<html lang="fr">

<?php
require 'bdd.php';
require 'menu.php';
require 'mesfonctions.php';
try {
    if (isset($_SESSION['id']) AND $id_admin > 1) {
        if (isset($_GET['id'])) {
            $id_etab = $_GET['id'];
        } else {
            $id_etab = requeteSQL($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial", "etab_id");
        }
        if (isset($_GET['id_site'])) {
            $id_site = $_GET['id_site'];
            $id_etab = requeteSQL($pdo, "SELECT * FROM sites_entreprise WHERE site_id = $id_site", "site_idEtab");
        }

        if (isset($_POST['form_eleve_form'])) {
            if (!empty($_POST['newnom']) OR !empty($_POST['newprenom'])) {
                $newetab = htmlentities($_POST['newetab']);
                $newsite = htmlentities($_POST['newsite']);
                $newfonction = strtoupper(htmlentities($_POST['newfonction']));
                $newnom = strtoupper(htmlentities($_POST['newnom']));
                $newprenom = ucwords(htmlentities($_POST['newprenom']));
                $newtel = htmlentities($_POST['newtel']);
                $newmail = htmlentities($_POST['newmail']);
                $newactif = htmlentities($_POST['newactif']);
                $newpk = htmlentities($_POST['newpk']);

                $newportable = htmlentities($_POST['newportable']);
                $newportablepro = htmlentities($_POST['newportablepro']);

                $newtelpro = htmlentities($_POST['newtelpro']);
                $newmailpro = htmlentities($_POST['newmailpro']);

                $newsexe = htmlentities($_POST['newsexe']);

                $insertcontact = $pdo->prepare("INSERT INTO contacts(contact_idEtab, contact_idSite, contact_idFonction, contact_nom, contact_prenom, contact_tel, contact_mail, contact_actif, contact_raison, contact_telpro, contact_mailpro, contact_portable, contact_portablepro, contact_sexe) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $insertcontact->execute(array($newetab, $newsite, $newfonction, $newnom, $newprenom, $newtel, $newmail, $newactif, $newpk, $newtelpro, $newmailpro, $newportable, $newportablepro, $newsexe));
                $_SESSION['succes'] = "Contact ajouté avec succès";
            } else {
                $_SESSION['erreur'] = "Tous les champs requis doivent être complétés !";
                header("location: ajouter_contact?id=$id_etab");
                exit();
            }
            header("location: fiche_etablissement?id=$newetab&contacts=1");
            exit();
        }
?>

        <head>
            <title>MFR BDD - Ajouter un contact</title>
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
                        <h1>Ajouter un contact</h1>
                        <br>

                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="newetab" class="form-label required">Établissement</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><a class="hover-logo" href="#" onclick="clickEtab()"><i class="fas fa-building"></i></a></span>
                                    </div>
                                    <select class="form-control" name="newetab" id="newetab" onchange="window.location.href='ajouter_contact?id='+this.value">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM etablissements ORDER BY etab_raisonSocial ASC") as $unEtab) {
                                            $id_etab2 = $unEtab['etab_id'];
                                            $raisonsocial = $unEtab['etab_raisonSocial'];
                                            if ($id_etab2 == $id_etab) {
                                                echo ("<option value='$id_etab2' selected='selected'>$raisonsocial</option>");
                                            } else {
                                                echo ("<option value='$id_etab2'>$raisonsocial</option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newsite" class="form-label required">Site</label>
                                <select name="newsite" id="newsite" class="form-control">
                                    <option value="0">Site Principal</option>
                                    <?php
                                    foreach (lireLesUsers($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE site_idTypeSite = typeSite_id AND site_idEtab = '$id_etab' ORDER BY typeSite_intitule") as $unSite) {
                                        $id_etab_site = $unSite['site_id'];
                                        $id_etab_site_nom = "Site " . $unSite['typeSite_intitule'] . " de " . $unSite['site_ville'] . " (" . $unSite['site_adresse'] . ")";
                                        if ($id_etab_site == $id_site) {
                                            echo ("<option value='$id_etab_site' selected='selected'>$id_etab_site_nom</option>");
                                        } else {
                                            echo ("<option value='$id_etab_site'>$id_etab_site_nom</option>");
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="newfonction" class="form-label required">Fonction</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-user-tie"></i></span>
                                    </div>
                                    <select name="newfonction" id="newfonction" class="form-control">
                                        <?php
                                        foreach (lireLesUsers($pdo, "SELECT * FROM fonctions_contacts") as $uneFonction) {
                                            $fonction_id = $uneFonction['fonction_id'];
                                            $fonction_intitule = $uneFonction['fonction_intitule'];
                                            echo ("<option value='$fonction_id'>$fonction_intitule</option>");
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newsexe" class="form-label required">Civilité</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><i class="fas fa-mars"></i></span>
                                    </div>
                                    <select name="newsexe" id="newsexe" class="form-control">
                                        <option>M.</option>
                                        <option>Mme</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newnom" class="form-label required">Nom</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" placeholder="Nom" name="newnom" id="newnom" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newprenom" class="form-label required">Prénom</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" placeholder="Prénom" name="newprenom" id="newprenom" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newmail" class="form-label">Mail personnel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" placeholder="Mail personnel" name="newmail" id="newmail" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newmailpro" class="form-label">Mail professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" placeholder="Mail professionel" name="newmailpro" id="newmailpro" class="form-control">
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
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél personnel" name="newtel" id="newtel" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newtelpro" class="form-label">Tél professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Tél professionel" name="newtelpro" id="newtelpro" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="newportable" class="form-label">Portable personnel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Portable personnel" name="newportable" id="newportable" class="form-control" value="<?php if (isset($contact_portable)) echo $contact_portable ?>">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="newportablepro" class="form-label">Portable professionel</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-phone-alt"></i></span>
                                        </div>
                                        <input type="tel" minlength="14" maxlength="14" placeholder="Portable professionel" name="newportablepro" id="newportablepro" class="form-control" value="<?php if (isset($contact_portablepro)) echo $contact_portablepro ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newinteresse" class="form-label required">Accepte les appels</label>
                                <select name="newactif" id="newinteresse" class="form-control">
                                    <option value='1' selected="selected">Oui</option>
                                    <option value='0'>Non</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div id="hide-me">
                                    <label for="newraison" class="form-label">Raison</label>
                                    <textarea class="form-control" id="newraison" name="newpk" rows="4" cols="50" placeholder="Pourquoi ce contact n'accepte pas les appels..."></textarea>
                                </div>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary button_w100" id="form_eleve_form" name="form_eleve_form">Ajouter</button>
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