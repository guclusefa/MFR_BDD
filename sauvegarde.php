<?php
require 'bdd.php';
require 'mesfonctions.php';
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $requete = $pdo->prepare("SELECT * FROM membres WHERE memb_id = ?");
    $requete->execute(array($id));
    $requete = $requete->fetch();
    $id_admin = $requete['memb_admin'];
}
if (isset($_SESSION['id']) and $id_admin > 2) {

    if (isset($_GET['etabs'])) {
        $select = $pdo->prepare('
        SELECT etab_id, etab_raisonSocial, secteur_nom, origineEnt_intitule, departement_code, etab_adresse, etab_ville, etab_cp, etab_tel, etab_mail, etab_donnateur, etab_interest, etab_raison, etab_url 
        FROM etablissements, secteurs_entreprise, types_entreprise, origines_entreprise, departement WHERE etab_idSecteur = secteur_id AND etab_idType = typeEnt_id AND etab_idOrigine = origineEnt_id AND etab_idDepartement = departement_id
        ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "ETAB ID\tETAB RS\tETAB SECTEUR\tETAB ORIGINE\tETAB DEPARTEMENT\tETAB ADRESSE\tETAB VILLE\tETAB CP\tETAB TEL\tETAB MAIL\tETAB DONATEUR\tETAB INTERESSE\tETAB RAISON\tETAB SITE\n";

        foreach ($newReservations as $row) {
            $excel .= "$row[etab_id]\t$row[etab_raisonSocial]\t$row[secteur_nom]\t$row[origineEnt_intitule]\t$row[departement_code]\t$row[etab_adresse]\t$row[etab_ville]\t$row[etab_cp]\t$row[etab_tel]\t$row[etab_mail]\t$row[etab_donnateur]\t$row[etab_interest]\t$row[etab_raison]\t$row[etab_url]\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_etablissements.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['sites'])) {
        $select = $pdo->prepare('
        SELECT site_id, etab_id, etab_raisonSocial, typeSite_intitule, departement_code, site_adresse, site_ville, site_cp, site_tel, site_mail FROM sites_entreprise, etablissements, types_sites, departement WHERE site_idEtab = etab_id AND site_idTypeSite = typeSite_id AND site_idDepartement = departement_id
        ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "SITE ID\tSITE ETAB ID\tSITE ETAB RS\tSITE TYPE\tSITE DEPARTEMENT\tSITE ADRESSE\tSITE VILLE\tSITE CP\tSITE TEL\tSITE MAIL\n";

        foreach ($newReservations as $row) {
            $excel .= "$row[site_id]\t$row[etab_id]\t$row[etab_raisonSocial]\t$row[typeSite_intitule]\t$row[departement_code]\t$row[site_adresse]\t$row[site_ville]\t$row[site_cp]\t$row[site_tel]\t$row[site_mail]\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_sites_scondaires.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['collabes'])) {
        $select = $pdo->prepare('
        SELECT collab_id, collab_idEtab, etab_raisonSocial, collab_idCfa, formationCfa_intitule, collab_raison FROM collab_cfa, participation_collab, etablissements, formation_cfa WHERE collab_idEtab = etab_id AND participationCollab_idCollab = collab_id AND participationCollab_idFormationCfa = formationCfa_id        ');
        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "COLLAB ID\tCOLLAB ETAB ID\tCOLLAB ETAB RS\tCOLLAB ID CFA\tCOLLAB CFA RS\tCOLLAB FORMATION\tCOLLAB COMMENTAIRE\n";

        foreach ($newReservations as $row) {
            $cfa = $row['collab_idCfa'];
            $cfa = requeteSQL($pdo, "SELECT * FROM etablissements WHERE etab_id = $cfa", "etab_raisonSocial");
            $excel .= "$row[collab_id]\t$row[collab_idEtab]\t$row[etab_raisonSocial]\t$row[collab_idCfa]\t$cfa\t$row[formationCfa_intitule]\t$row[collab_raison]\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_collaborations_cfa.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['alternances'])) {
        $select = $pdo->prepare('
        SELECT alt_ruptureDate, alt_id, alt_idEleve, eleve_nom, eleve_prenom, eleve_mailperso, eleve_mailpro, eleve_telperso, eleve_telpro, alt_idEtab, etab_raisonSocial, alt_idSite, formation_niv, alt_idContact, contact_nom, contact_prenom, alt_debut, alt_fin, alt_actif, alt_raison FROM alternances, eleves, etablissements, sites_entreprise, formations, contacts WHERE alt_idEleve = eleve_id AND alt_idEtab = etab_id AND alt_idSite = site_id AND alt_idFormation = formation_id AND alt_idContact = contact_id          ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "ALT ID\tALT ID ELEVE\tALT ELEVE NOM\tALT ELEVE PRENOM\tALT ELEVE MAILPERSO\tALT ELEVE MAILPRO\tALT ELEVE TELPERSO\tALT ELEVE TELPRO\tALT ID ETAB\tALT ETAB RS\tALT ID SITE\tALT SITE\tALT FORMATION\tALT ID CONTACT\tALT CONTACT NOM\tALT CONTACT PRENOM\tALT DATE DEBUT\tALT DATE FIN\tALT RUPTURE CONTRAT\tALT RAISON\tALT DATE RUPTURE\n";

        foreach ($newReservations as $row) {
            $site = $row['alt_idSite'];
            if ($site == 0) {
                $site = 'Principal';
            } else {
                $site = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE typeSite_id = site_idTypeSite AND site_id = $site", "typeSite_intitule");
            }
            $contact = $row['alt_idContact'];
            if ($contact == 0) {
                $contact_nom = " ";
                $contact_prenom = " ";
            } else {
                $contact_nom = $row['contact_nom'];
                $contact_prenom = $row['contact_prenom'];
            }
            $alt_debut = dateMySQLToFr($row['alt_debut']);
            $alt_fin = dateMySQLToFr($row['alt_fin']);
            if(!empty($row['alt_ruptureDate'])) {
                $dateRupture = dateMySQLToFr($row['alt_ruptureDate']);
            } else {
                $dateRupture = ' ';
            }

            $excel .= "$row[alt_id]\t$row[alt_idEleve]\t$row[eleve_nom]\t$row[eleve_prenom]\t$row[eleve_mailperso]\t$row[eleve_mailpro]\t$row[eleve_telperso]\t$row[eleve_telpro]\t$row[alt_idEtab]\t$row[etab_raisonSocial]\t$row[alt_idSite]\t$site\t$row[formation_niv]\t$row[alt_idContact]\t$contact_nom\t$contact_prenom\t$alt_debut\t$alt_fin\t$row[alt_actif]\t$row[alt_raison]\t$dateRupture\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_alternances.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['offres'])) {
        $select = $pdo->prepare('
        SELECT offreAlt_id, offreAlt_idEtab, etab_raisonSocial, offreAlt_idSite, formation_niv, offreAlt_anneeFormation, offreAlt_nom, offreAlt_prenom, offreAlt_mailperso, offreAlt_mailpro, offreAlt_telperso, offreAlt_telpro, offreAlt_idContact, contact_nom, contact_prenom, offreAlt_debut, offreAlt_fin, offreAlt_idAlt, offreAlt_raison FROM offres_alternances, etablissements, sites_entreprise, formations, contacts WHERE etab_id = offreAlt_idEtab AND site_id = offreAlt_idSite AND offreAlt_idFormation = formation_id AND offreAlt_idContact = contact_id AND offreAlt_idSite = site_id AND offreAlt_demande = 0               ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "OFFRE ID\tOFFRE ID ETAB\tOFFRE ETAB RS\tOFFRE SITE ID\tOFFRE SITE\tOFFRE FORMATION\tOFFRE ANNEE DE FORMATION INITIAL\tOFFRE ELEVE NOM\tOFFRE ELEVE PRENOM\tOFFRE ELEVE MAILPERSO\tOFFRE ELEVE MAILPRO\tOFFRE ELEVE TELPERSO\tOFFRE ELEVE TELPRO\tOFFRE ID CONTACT\tOFFRE CONTACT NOM\tOFFRE CONTACT PRENOM\tOFFRE DATE DEBUT\tOFFRE DATE FIN\tOFFRE ID ALT\tOFFRE ETAT\n";

        foreach ($newReservations as $row) {
            $site = $row['offreAlt_idSite'];
            if ($site == 0) {
                $site = 'Principal';
            } else {
                $site = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE typeSite_id = site_idTypeSite AND site_id = $site", "typeSite_intitule");
            }
            $contact = $row['offreAlt_idContact'];
            if ($contact == 0) {
                $contact_nom = " ";
                $contact_prenom = " ";
            } else {
                $contact_nom = $row['contact_nom'];
                $contact_prenom = $row['contact_prenom'];
            }
            $offreAlt_debut = dateMySQLToFr($row['offreAlt_debut']);
            $offreAlt_fin = dateMySQLToFr($row['offreAlt_fin']);

            $alt = $row['offreAlt_idAlt'];
            if ($alt == -1) {
                $etat = "Refusee";
            } else if ($alt == 0) {
                $etat = "En attente";
                if ($row['offreAlt_nom'] != '' or $row['offreAlt_prenom'] != '') {
                    $etat = "CV envoye";
                }
            } else {
                $etat = "Acceptee";
            }


            $excel .= "$row[offreAlt_id]\t$row[offreAlt_idEtab]\t$row[etab_raisonSocial]\t$row[offreAlt_idSite]\t$site\t$row[formation_niv]\t$row[offreAlt_anneeFormation]\t$row[offreAlt_nom]\t$row[offreAlt_prenom]\t$row[offreAlt_mailperso]\t$row[offreAlt_mailpro]\t$row[offreAlt_telperso]\t$row[offreAlt_telpro]\t$row[offreAlt_idContact]\t$contact_nom\t$contact_prenom\t$offreAlt_debut\t$offreAlt_fin\t$alt\t$etat\t$row[offreAlt_raison]\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_offres.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['demandes'])) {
        $select = $pdo->prepare('
        SELECT offreAlt_id, offreAlt_idEtab, etab_raisonSocial, offreAlt_idSite, formation_niv, offreAlt_anneeFormation, offreAlt_nom, offreAlt_prenom, offreAlt_mailperso, offreAlt_mailpro, offreAlt_telperso, offreAlt_telpro, offreAlt_idContact, contact_nom, contact_prenom, offreAlt_debut, offreAlt_fin, offreAlt_idAlt, offreAlt_raison FROM offres_alternances, etablissements, sites_entreprise, formations, contacts WHERE etab_id = offreAlt_idEtab AND site_id = offreAlt_idSite AND offreAlt_idFormation = formation_id AND offreAlt_idContact = contact_id AND offreAlt_idSite = site_id AND offreAlt_demande = 1               ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "DEMANDE ID\tDEMANDE ID ETAB\tDEMANDE ETAB RS\tDEMANDE SITE ID\tDEMANDE SITE\tDEMANDE FORMATION\tDEMANDE ANNEE DE FORMATION INITIAL\tDEMANDE ELEVE NOM\tDEMANDE ELEVE PRENOM\tDEMANDE ELEVE MAILPERSO\tDEMANDE ELEVE MAILPRO\tDEMANDE ELEVE TELPERSO\tDEMANDE ELEVE TELPRO\tDEMANDE ID CONTACT\tDEMANDE CONTACT NOM\tDEMANDE CONTACT PRENOM\tDEMANDE DATE DEBUT\tDEMANDE DATE FIN\tDEMANDE ID ALT\tDEMANDE ETAT\n";

        foreach ($newReservations as $row) {
            $site = $row['offreAlt_idSite'];
            if ($site == 0) {
                $site = 'Principal';
            } else {
                $site = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE typeSite_id = site_idTypeSite AND site_id = $site", "typeSite_intitule");
            }
            $contact = $row['offreAlt_idContact'];
            if ($contact == 0) {
                $contact_nom = " ";
                $contact_prenom = " ";
            } else {
                $contact_nom = $row['contact_nom'];
                $contact_prenom = $row['contact_prenom'];
            }
            $offreAlt_debut = dateMySQLToFr($row['offreAlt_debut']);
            $offreAlt_fin = dateMySQLToFr($row['offreAlt_fin']);

            $alt = $row['offreAlt_idAlt'];
            if ($alt == -1) {
                $etat = "Refusee";
            } else if ($alt == 0) {
                $etat = "En attente";
                if ($row['offreAlt_nom'] != '' or $row['offreAlt_prenom'] != '') {
                    $etat = "CV envoye";
                }
            } else {
                $etat = "Acceptee";
            }


            $excel .= "$row[offreAlt_id]\t$row[offreAlt_idEtab]\t$row[etab_raisonSocial]\t$row[offreAlt_idSite]\t$site\t$row[formation_niv]\t$row[offreAlt_anneeFormation]\t$row[offreAlt_nom]\t$row[offreAlt_prenom]\t$row[offreAlt_mailperso]\t$row[offreAlt_mailpro]\t$row[offreAlt_telperso]\t$row[offreAlt_telpro]\t$row[offreAlt_idContact]\t$contact_nom\t$contact_prenom\t$offreAlt_debut\t$offreAlt_fin\t$alt\t$etat\t$row[offreAlt_raison]\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_demandes.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['contacts'])) {
        $select = $pdo->prepare('
        SELECT contact_id, contact_idEtab, etab_raisonSocial, contact_idSite, contact_sexe, contact_nom, contact_prenom, fonction_intitule, contact_tel, contact_telpro, contact_portable, contact_portablepro, contact_mail, contact_mailpro, contact_actif, contact_raison FROM contacts, etablissements, sites_entreprise, fonctions_contacts WHERE contact_idEtab = etab_id AND contact_idSite = site_id AND contact_idFonction = fonction_id        ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "CONTACT ID\tCONTACT ID ETAB\tCONTACT ETAB RS\tCONTACT SITE ID\tCONTACT SITE\tCONTACT CIVILITE\tCONTACT NOM\tCONTACT PRENOM\tCONTACT FONCTION\tCONTACT TELPERSO\tCONTACT TELPRO\tCONTACT PORTABLEPERSO\tCONTACT PORTABLEPERSO\tCONTACT MAILPERSO\tCONTACT MAILPERSO\tCONTACT ACCEPETE APPELS\tCONTACT RAISON\n";

        foreach ($newReservations as $row) {
            $site = $row['contact_idSite'];
            if ($site == 0) {
                $site = 'Principal';
            } else {
                $site = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE typeSite_id = site_idTypeSite AND site_id = $site", "typeSite_intitule");
            }
            $contact = $row['contact_id'];
            if ($contact == 0) {
                $contact_nom = " ";
                $contact_prenom = " ";
            } else {
                $contact_nom = $row['contact_nom'];
                $contact_prenom = $row['contact_prenom'];
            }

            $actif = $row['contact_actif'];
            if ($actif) {
                $actif = 'Oui';
            } else {
                $actif = 'Non';
            }

            $excel .= "$row[contact_id]\t$row[contact_idEtab]\t$row[etab_raisonSocial]\t$row[contact_idSite]\t$site\t$row[contact_sexe]\t$contact_nom\t$contact_prenom\t$row[fonction_intitule]\t$row[contact_tel]\t$row[contact_telpro]\t$row[contact_portable]\t$row[contact_portablepro]\t$row[contact_mail]\t$row[contact_mailpro]\t$actif\t$row[contact_raison]\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_contacts.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['entretiens'])) {
        $select = $pdo->prepare('
        SELECT entretien_id, etab_id, etab_raisonSocial, entretien_idContact, contact_nom, contact_prenom, entretien_idMembre, memb_nom, memb_prenom, entretien_contenu, entretien_dateAppel, entretien_dateRappel, entretien_dateRappel2, entretien_reponse, entretien_idNextEntretien FROM entretiens, contacts, membres, etablissements WHERE entretien_idContact = contact_id AND entretien_idMembre = memb_id AND contact_idEtab = etab_id                ');

        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "ENTRETIEN ID\tENTRETIEN ID ETAB\tENTRETIEN ETAB RS\tENTRETIEN ID CONTACT\tENTRETIEN CONTACT NOM\tENTRETIEN CONTACT PRENOM\tENTRETIEN ID INTERLOCUTEUR\tENTRETIEN INTERLOCUTEUR NOM\tENTRETIEN INTERLOCUTEUR PRENOM\tENTRETIEN CONTENU\tENTRETIEN DATE APPEL\tENTRETIEN DATE RAPPEL 1\tENTRETIEN DATE RAPPEL 2\tENTRETIEN REPONSE\tENTRETIEN ETAT\n";

        foreach ($newReservations as $row) {
            $contact = $row['entretien_idContact'];
            if ($contact == 0) {
                $contact_nom = " ";
                $contact_prenom = " ";
            } else {
                $contact_nom = $row['contact_nom'];
                $contact_prenom = $row['contact_prenom'];
            }


            $dateAppel = dateMySQLToFr($row['entretien_dateAppel']) . '-' . dateMySQLToFr2($row['entretien_dateAppel']);
            $dateRappel1 = dateMySQLToFr($row['entretien_dateRappel']) . '-' . dateMySQLToFr2($row['entretien_dateRappel']);
            $dateRappel2 = dateMySQLToFr($row['entretien_dateRappel2']) . '-' . dateMySQLToFr2($row['entretien_dateRappel2']);

            $entretien_reponse = $row['entretien_reponse'];
            if ($entretien_reponse) {
                $entretien_reponse = 'Oui';
            } else {
                $entretien_reponse = 'Non';
            }

            $entretien_etat = $row['entretien_idNextEntretien'];
            if ($entretien_etat >= 0) {
                $entretien_etat = 'Finie';
            } else if ($entretien_etat = -1 and $dateSQL > $row['entretien_dateRappel2']) {
                $entretien_etat = 'Rappel manque';
            } else {
                $entretien_etat = 'A rappeler';
            }

            $excel .= "$row[entretien_id]\t$row[etab_id]\t$row[etab_raisonSocial]\t$row[entretien_idContact]\t$contact_nom\t$contact_prenom\t$row[entretien_idMembre]\t$row[memb_nom]\t$row[memb_prenom]\t$row[entretien_contenu]\t$dateAppel\t$dateRappel1\t$dateRappel2\t$entretien_reponse\t$entretien_etat\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_entretiens.xls");

        print $excel;
        exit;
    }

    if (isset($_GET['events'])) {
        $select = $pdo->prepare('
        SELECT event_id, event_idEtab, etab_raisonSocial, event_idSite, typeEvent_intitule, contact_id, contact_nom, contact_prenom, event_commentaire, event_date FROM evenements, etablissements, sites_entreprise, types_evenements, participation_event, contacts WHERE event_idEtab = etab_id AND event_idSite = site_id AND event_idTypeEvent = typeEvent_id AND participation_idContact = contact_id AND participation_idEvent = event_id            ');
        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();

        $newReservations = $select->fetchAll();

        $excel = "";
        $excel .=  "EVENT ID\tEVENT ETAB ID\tEVENT ETAB RS\tEVENT ID SITE\tEVENT SITE\tEVENT TYPE\tEVENT ID CONTACT\tEVENT CONTACT NOM\tEVENT CONTACT PRENOM\tEVENT COMMENTAIRE\tEVENT DATE\n";

        foreach ($newReservations as $row) {
            $site = $row['event_idSite'];
            if ($site == 0) {
                $site = 'Principal';
            } else {
                $site = requeteSQL($pdo, "SELECT * FROM sites_entreprise, types_sites WHERE typeSite_id = site_idTypeSite AND site_id = $site", "typeSite_intitule");
            }
            $contact = $row['contact_id'];
            if ($contact == 0) {
                $contact_nom = " ";
                $contact_prenom = " ";
            } else {
                $contact_nom = $row['contact_nom'];
                $contact_prenom = $row['contact_prenom'];
            }
            $date = dateMySQLToFr($row['event_date']);

            $excel .= "$row[event_id]\t$row[event_idEtab]\t$row[etab_raisonSocial]\t$row[event_idSite]\t$site\t$row[typeEvent_intitule]\t$row[contact_id]\t$contact_nom\t$contact_prenom\t$row[event_commentaire]\t$date\n";
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=mfr_bdd_evenements.xls");

        print $excel;
        exit;
    }
} else {
    $_SESSION['erreur'] = "Vous n'avez pas les droits !";
    header("location: index.php");
    exit();
}
