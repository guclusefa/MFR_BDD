<?php
try {
    date_default_timezone_set('Europe/Paris');
    $date = date('Y-m-d');
    $dateSQL = date("Y-m-d H:i:s");
    $date_annee = date('Y');
    $date_annee_2 = $date_annee + 2;
    $date_mois = date('m');

    $date1 = date('Y-m-d', strtotime('+1 year', strtotime($date)));
    $date2 = date('Y-m-d', strtotime('+2 years', strtotime($date)));
    $date3 = date('Y-m-d', strtotime('+3 years', strtotime($date)));

    $date_annee_1 = $date_annee + 1;
    $date_annee_m1 = $date_annee - 1;
    $date_annee_m2 = $date_annee - 2;
    $date_annee_m3 = $date_annee - 3;
    $date_annee_m4 = $date_annee - 4;

    function remove_accents($string) {
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;
    
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );
    
        $string = strtr($string, $chars);
    
        return $string;
    }

    function verifEspaces($text){
        return ctype_space($text) || $text === "" || $text === null;
    }

    //extraire une seule colonne d'une requete sql
    function requeteSQL($pdo, $sql, $colonne)
    {
        $requete = $pdo->prepare($sql);
        $requete->execute();
        $requete = $requete->fetch();
        $resultat = $requete[$colonne];
        return $resultat;
    }

    //lire toute les lignes d'une requete sql
    function lireLesUsers($pdo, $sql)
    {
        return ($pdo->query($sql)->fetchAll());
    }

    function dateFrToMySQL($date)
    { // jj/mm/aaaa vers aaaa-mm-jj
        if (strlen($date) != 10) die("Format de date incorrect");
        return substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
    };
    

    function dateMySQLToFr($date)
    { // aaaa-mm-jj vers jj/mm/aaaa
        return date("d/m/Y", strtotime($date));
    };

    function dateMySQLToFr2($date)
    { // aaaa-mm-jj vers jj/mm/aaaa h:i:s
        return date("H:i:s", strtotime($date));
    };

    function dateMySQLToFrLong($date)
    {
        //--- Les noms des jours en français 
        $jour[0] = "Dimanche";
        $jour[1] = "Lundi";
        $jour[2] = "Mardi";
        $jour[3] = "Mercredi";
        $jour[4] = "Jeudi";
        $jour[5] = "Vendredi";
        $jour[6] = "Samedi";
        //--- Les noms des mois en français 
        $mois[1] = "janvier";
        $mois[2] = "février";
        $mois[3] = "mars";
        $mois[4] = "avril";
        $mois[5] = "mai";
        $mois[6] = "juin";
        $mois[7] = "juillet";
        $mois[8] = "août";
        $mois[9] = "septembre";
        $mois[10] = "octobre";
        $mois[11] = "novembre";
        $mois[12] = "décembre";

        $d1 = date("w/j/n/Y", strtotime($date));
        $d2 = explode("/", $d1);
        return ($jour[$d2[0]] . " " . $d2[1] . " " . $mois[$d2[2]] . " " . $d2[3]);
    };

    function formaterDateFr($date)
    { // j/m/aa vers jj/mm/aaaa
        if (strpos($date, "/") < 2) $date = "0" . $date;
        $lg = strlen($date);
        $result = substr($date, 0, 3);
        $date = substr($date, 3, $lg);
        if (strpos($date, "/") < 2) $date = "0" . $date;
        $lg = strlen($date);
        $result = $result . substr($date, 0, 3);
        $date = substr($date, 3, $lg);
        if (strlen($date) == 2) $date = "20" . $date;
        $result = $result . $date;
        return $result;
    }
} catch (Exception $e) {
    die("erreur : " . $e->getMessage());
}
