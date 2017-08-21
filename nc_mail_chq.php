<?php
// pour vérifier la liste des emails, lancer gpao2/aaa.php
session_start();

require 'lib_mail/class.phpmailer.php';
include "/var/www.cache/dgconn.inc";


$matricule_tlc     = $_REQUEST['matricule_tlc'];

$id_client         = $_REQUEST['id_client'];
$id_prestation     = $_REQUEST['id_prestation'];

$type_traitement   = $_REQUEST['type_traitement'];
$id_tlc            = utf8_decode($_REQUEST['id_tlc']);
$id_fichier        = $_REQUEST['id_fichier'];
$date_traitement   = $_REQUEST['date_traitement'];
$date_evaluation   = $_REQUEST['date_evaluation'];
$categorie_si      = $_REQUEST['categorie_si'];
$description_ecart = utf8_decode($_REQUEST['description_ecart']);
$exigence_client   = utf8_decode($_REQUEST['exigence_client']);
$idclient          = $_REQUEST['idclient'];

$ref_nc            = $_REQUEST['ref_nc'];

function get_info_sup_tlc($matricule)
{

    $matricule = (int) $matricule;

    global $conn;
    //requête pour recuperer le N+1 du TLC
    $sql_sup   = " 
                    SELECT
                        P.MATRICULE
                    ,   P.PRENOMPERSONNEL
                    ,   P.EMAILPERS
                    FROM
                        GPAO_HIERARCHIE GH
                    INNER JOIN
                        PERSONNEL   P
                    ON
                        GH.MATRICULE_PARENT =   P.MATRICULE
                    WHERE
                        GH.MATRICULE    =  " . $matricule . "
                    AND P.ACTIFPERS ILIKE   'Active'
    ";

    $query_sup = pg_query($conn, $sql_sup) or die(pg_last_error()." ".$sql_sup);
    /*$rws_sup = pg_fetch_array( $query_sup );

    //prenom N+1, mail N+1 , prenom  ACC, mail ACC
    return $rws_sup[0].'#'.$rws_sup[1].'#'.$rws_acc[0].'#'.$rws_acc[1];*/
    return $query_sup;
}

function get_info_acc_tlc($matricule_acc)
{
    global $conn;
    $sql_acc   = "SELECT prenompersonnel,emailpers FROM personnel WHERE  matricule=" . $matricule_acc;
    $query_acc = pg_query($conn, $sql_acc) or die(pg_last_error());
    $rws_acc   = pg_fetch_row($query_acc);
    //prenom N+1, mail N+1 , prenom  ACC, mail ACC
    return $rws_acc[0] . '#' . $rws_acc[1];
}

function get_client_id($name)
{
    global $conn;
    $clients   = array();
    $sql_acc   = "SELECT id_client FROM gu_client WHERE  nom_client ='" . $name . "' ";
    $query_acc = pg_query($conn, $sql_acc) or die(pg_last_error());
    $nb_acc    = pg_num_rows($query_acc);

    while ($lg = @pg_fetch_array($query_acc)) {
        if ($lg["id_client"] != '' && $lg["id_client"] != null && !empty($lg["id_client"])) {
            array_push($clients, $lg["id_client"]);
        }
    }

    return $clients;
}

$sortant       = 0;




$mail_k = 'tolotra_si@vivetic.mg';
$nom_k  = 'Tolotsoa';

if (trim($_REQUEST['type_traitement']) == 'appels sortants') {
    $sortant = 1;
}

/*
$matricule_tlc = 6568;
$id_client = 'NACRE SOFTWARE';
$id_prestation = 'NCE';

$type_traitement = 'Appel entrant';
$id_tlc = 'Nj';
$id_fichier = 'test notation';
$date_traitement = '12/02/2015';
$date_evaluation = '12/02/2015';
$categorie_si = 'Conclusion';
$description_ecart = 'ceci est un test SI';
$exigence_client = 'Ceci est un test SI';
$idclient = '919';
 */

list($jr_fnc, $mois_fnc, $annee_fnc) = explode("/", $date_evaluation);
$date_fnc                            = $annee_fnc . '-' . $mois_fnc . '-' . $jr_fnc;
$matricule_acc                       = $_SESSION['matricule'];
$info_acc                            = get_info_acc_tlc($matricule_acc);

list($prenom_acc, $mail_acc) = explode("#", $info_acc);

//$mail_sup = 'njivaniaina@vivetic.mg';
//$mail_cc = 'tsilavina.si@vivetic.mg';
//$prenom_sup = 'Njivaniaina';

$mail = new PHPmailer();
$mail->IsHTML(true);
$mail->From     = $mail_acc; // votre adresse
$mail->FromName = $prenom_acc; // votre nom
$mail->Subject  = utf8_decode('NF 345 - FNC critique ouverte sur '.$id_client); // sujet de votre message

$id_tlc_fnc = str_replace(" ", "_", $id_tlc) . "_" . $matricule_tlc;

$header_mail = "";
$message     = "
<style type='text/css'>
.table_style{
border-width: 0.5pt;
border-style: solid;
border-color: windowtext;
width:400px;
font-family: Arial;
font-size:12px;
padding:3px;
}
.table_style_left{
//border-width: 0.5pt;
//border-style: solid;
//border-color: windowtext;
width:150px;
font-family: Arial;
font-size:12px;
color: #00007f;
}
.link{
color:#4398DE;
text-decoration:none;
}
#id_div_corps_mail{
font-family: Arial;
font-size:12px;
}
</style>
<div id='id_div_corps_mail'>
<p>Bonjour, </p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Une FNC critique vient d'&ecirc;tre ouverte :</p>
<br />
<!--<table  cellspacing='0' cellpadding='0' border='0' >-->
<table>

<tr>
<td class='table_style_left'>Nom du client:</td><td class='table_style'>&nbsp;" . $id_client . "</td>
</tr>

<tr>
<td class='table_style_left'>Prestation:</td><td class='table_style'>&nbsp;" . $id_prestation . "</td>
</tr>

<tr>
<td class='table_style_left'>Type de traitement:</td><td class='table_style'>&nbsp;" . $type_traitement . "</td>
</tr>

<tr>
<td class='table_style_left'>CC:</td><td class='table_style'>&nbsp;" . $id_tlc . " - " . $matricule_tlc . "</td>
</tr>

<tr>
<td class='table_style_left'>Fichier:</td><td class='table_style'>&nbsp;" . $id_fichier . "</td>
</tr>

<tr>
<td class='table_style_left'>Date de traitement:</td><td class='table_style'>&nbsp;" . $date_traitement . "</td>
</tr>

<tr>
<td class='table_style_left'>Date &eacute;valuation:</td><td class='table_style'>&nbsp;" . $date_evaluation . "</td>
</tr>

<tr>
<td class='table_style_left'>Cat&eacute;gorie SI:</td><td class='table_style'>&nbsp;" . $categorie_si . "</td>
</tr>

<tr height='90px;' >
<td class='table_style_left'>Description de l'&eacute;cart:</td><td class='table_style' valign='top' >&nbsp;" . $description_ecart . "</td>
</tr>

<tr height='90px;'>
<td class='table_style_left'>Exigence client / r&eacute;f&eacute;rentiel:</td><td class='table_style' valign='top'>&nbsp;" . $exigence_client . "</td>
</tr>

</table>
<br />

<p><b>Comme l'exige notre SMQ, une FNC critique doit faire l'objet d'analyse imm&eacute;diate, une r&eacute;ponse &agrave; ce mail est attendue sous 48 heures.</b></p>

<p><b>Une fois l'analyse effectu&eacute;e, veuillez consulter la r&eacute;f&eacute;rence <span class='link'>" . $ref_nc . "</span> pour l'enregistrement des &eacute;l&eacute;ments <b></p>
<p><b>d'analyses de l'occurrence et des actions que vous avez d&eacute;finies.</b></p>

</div>";
$footer_mail = "<br /><div style='padding-bottom: 15px;'>Cordialement,<br/> <span class='link' > La Direction qualit&eacute; </span><br/>
</div>";


/* commenter5
$info_sup   = get_info_sup_tlc($matricule_tlc);

$parent_sup = "";
$nb_sup     = pg_num_rows($info_sup);
$i          = 0;

if(!empty($info_sup)){
    while ($res_sup = pg_fetch_array($info_sup)) {
        $parent_sup .= $res_sup['matricule'];
        if ($i < ($nb_sup - 1)) {
            $parent_sup .= ',';
            $i++;
        }
    }
}


$info_sup = get_info_sup_tlc($matricule_tlc);


if(!empty($info_sup)){
    while ($res_sup = pg_fetch_array($info_sup)) {

        $mail->AddAddress($res_sup['emailpers'], $res_sup['prenompersonnel']); // adresse du destinataire
        // $mail->AddAddress($mail_k, $nom_k);     // adresse du destinataire

    }
}*/

//$mail->AddAddress($mail_k, $nom_k);     // adresse du destinataire

//if ($parent_sup == '' ) //$parent_sup=7795;

/* commenter4

$info_parent_sup = get_info_sup_tlc($parent_sup);

if(!empty($info_parent_sup)){
    while ($res_sup = pg_fetch_array($info_parent_sup)) {
        $mail->AddCC($res_sup['emailpers'], $res_sup['prenompersonnel']); // adresse du destinataire
        // $mail->AddCC($mail_k, $nom_k);     // adresse du destinataire

    }
}*/

$mail->AddAddress('direction_tana@vivetic.mg', 'Direction Tana');     // adresse du destinataire
//

$mail->AddCC($mail_k, $nom_k); // copie adresse SI

/**
 * Mail chq
 */

//$mail->AddCC('direction_tana@vivetic.mg', 'Direction Tana');
$mail->AddCC('tovo.randriamihaja@vivetic.mg', 'tovo.randriamihaja@vivetic.mg');
$mail->AddCC('si@vivetic.mg', 'si@vivetic.mg');
$mail->AddCC('tolotra@vivetic.mg', 'tolotra@vivetic.mg');
$mail->AddCC('celestine@vivetic.mg', 'celestine@vivetic.mg');
$mail->AddCC('mbola@vivetic.mg', 'mbola@vivetic.mg');
$mail->AddCC('sc_qualite@vivetic.mg', 'sc_qualite@vivetic.mg'); 
$mail->AddCC('chq_vivetic@vivetic.mg', 'chq_vivetic@vivetic.mg');
$mail->AddCC('rd@vivetic.mg', 'rd@vivetic.mg');

/**
 *
 */

/* commenter3
$tab_client_id = array();
$tab_client_id = get_client_id($id_client);
*/

/*
$tab_index = array('643' => 'Delamaison',
'2254' => 'Eprokom',
'919' => 'Digitick',
'3122' => 'Nacre',
'767' => 'Nacre',
'942' => 'PCM',
'624' => 'RDC',
'458' => 'RGP_OLL',
'475' => 'sdvp',
'1006' => 'sogec',
'495' => 'sogec',
'3782' => 'willemse',

'1474'=>'CRITIZR',
'847'=>'NOMINATION',
'940'=>'NOMINATION',
'2726'=>'123PRESTA',
'384'=>'MEDIAMETRIE',
'3780'=>'ASPEC',
'393'=>'MILAN PRESSE - BAYARD',
'22'=>'ADLP_AS',
'2596'=>'VANEXPORT',
'4000'=>'EXTENSO',
'3080'=>'CHOISIR.COM',
'3260'=>'OFFICIEL CE',
'3858'=>'BATIACTU',
'475'=>'SDVP_AS',
'802'=>'ORANGE',
);
//
 */

/* commenter2
$Liste_destinataire = array(
    '643'  => 'faraniaina_cc@vivetic.mg||Faraniaina##dalhy_cc@vivetic.mg||Dalhy##mickaela_cc@vivetic.mg||Mickaela##kenny_cc@vivetic.mg||Kenny##rajo_cc@vivetic.mg||Rajo##si@vivetic.mg||SI',
// WFE
    '3782' => 'ando_cc@vivetic.mg||Ando##nomentsoa_cc@vivetic.mg||Faniry##mickaela_cc@vivetic.mg##si@vivetic.mg||SI',
    '3782' => 'loic_cc@vivetic.mg||Loic##si@vivetic.mg||SI', 'elie_cc@vivetic.mg||Elie##si@vivetic.mg||SI', 'judy_cc@vivetic.mg||judy##si@vivetic.mg||SI',
    '1006' => 'tahiana_cc@vivetic.mg||Tahiana##poussie_cc@vivetic.mg||Poussie##si@vivetic.mg||SI',
    '767'  => 'faraniaina_cc@vivetic.mg||Faraniaina##si@vivetic.mg||SI',
    '4220' => 'manou_cc@vivetic.mg||Faraniaina##pounah_cc@vivetic.mg||Pounah##si@vivetic.mg||SI',
    '624'  => 'soa_cc@vivetic.mg||Soa##lucia_cc@vivetic.mg||Lucia##fanilo_cc@vivetic.mg||Fanilo##lovasoa_cc@vivetic.mg||Lovasoa##si@vivetic.mg||SI',
    '919'  => 'andry_cc@vivetic.mg||Andry##si@vivetic.mg||SI',
    '4227' => 'andry_cc@vivetic.mg||Andry##si@vivetic.mg||SI',
    '2258' => 'andry_cc@vivetic.mg||Andry##si@vivetic.mg||SI',
    '2702' => 'andry_cc@vivetic.mg||Andry##si@vivetic.mg||SI',
    '1148' => 'tahiana_cc@vivetic.mg||Tahiana##homery_cc@vivetic.mg||Homery##si@vivetic.mg||SI',

    '475'  => 'andrianina_cc@vivetic.mg||Andrianina##si@vivetic.mg||SI',
    '4230' => 'andrianina_cc@vivetic.mg||Andrianina##si@vivetic.mg||SI',
    '4230' => 'jeanyves_cc@vivetic.mg||JeanYves##si@vivetic.mg||SI',
    '4230' => 'volanirina_cc@vivetic.mg||Volanirina##si@vivetic.mg||SI',
    '475'  => 'volanirina_cc@vivetic.mg||Volanirina##si@vivetic.mg||SI',
    '4239' => 'volanirina_cc@vivetic.mg||Volanirina##si@vivetic.mg||SI',

    '662'  => 'endrika_cc@vivetic.mg||Endrika##si@vivetic.mg||SI',
// A75 SDE - SDA
    '475'  => 'tojo_cc@vivetic.mg||Tojo##si@vivetic.mg||SI',
    '475'  => 'endrika_cc@vivetic.mg||Endrika##si@vivetic.mg||SI', 'antoine.devos@vivetic.mg||Antoine##si@vivetic.mg||SI',

// 4239
    '4239' => 'endrika_cc@vivetic.mg||Endrika##si@vivetic.mg||SI', 'antoine.devos@vivetic.mg||Antoine##si@vivetic.mg||SI',
    '4239' => 'raharinoro_cc@vivetic.mg||raharinoro##si@vivetic.mg||SI',

// 458 RGR
    '458'  => 'tahiana_cc@vivetic.mg||Tahiana##homery_cc@vivetic.mg||Homery##si@vivetic.mg||SI',
    '458'  => 'antoine.devos@vivetic.mg||Antoine##si@vivetic.mg||SI', 'raharinoro_cc@vivetic.mg||raharinoro##si@vivetic.mg||SI',
// 4234 MIM
    '4234' => 'antoine.devos@vivetic.mg||Antoine##si@vivetic.mg||SI',
    '4234' => 'volanirina_cc@vivetic.mg||Volanirina##si@vivetic.mg||SI',

);

$liste_sortant = array('mickaelle_cc@vivetic.mg||Mickaelle', 'miora_cc@vivetic.mg||Mioara,karen@vivetic.mg||Karen');

for ($o = 0; $o < count($tab_client_id); $o++) {
    foreach ($Liste_destinataire as $k => $list_copy) {

        $client_id = $tab_client_id[$o];
        if ($k == $client_id) {
            $mail_dest = explode('##', $list_copy);

            for ($cpt = 0; $cpt < count($mail_dest); $cpt++) {
                $mail_dest_pers                            = $mail_dest[$cpt];
                $mail_adress_dest                          = $mail_prenom_dest                          = '';
                list($mail_adress_dest, $mail_prenom_dest) = explode('||', $mail_dest_pers);

                if ($mail_adress_dest != '' && $mail_prenom_dest != '' && test_mail_active($mail_adress_dest) != '') {

                    $mail->AddCC($mail_adress_dest, $mail_prenom_dest);
                    // $mail->AddCC($mail_k, $nom_k);
                    if ($sortant == 1) {
                        for ($ii = 0; $ii < 3; $ii++) {
                            list($adr, $nom) = explode('||', $liste_sortant[$ii]);
                            if (test_mail_active($adr) != '') {

                                $mail->AddCC($adr, $nom);
                            }

                        }
                    }
                    // $sortant = 1
                }
            }

        }

    }

}*/



$mail->Body = $header_mail . $message . $footer_mail;
// $mail->AddEmbeddedImage('lib_mail/img/logo_vivetic_mail.png', 'logo_vvt');

/* comenter 1
$directory = "reporting/";

$target_path = $directory;

if (file_exists($target_path)) {
    $mail->AddAttachment($target_path);
} else {
    echo $target_path . ' inexistant';
}
*/

///in boucle ra mis fichier bdb o attachena

if (!$mail->Send()) { // on teste la fonction Send() -> envoyer
    echo $mail->ErrorInfo; //Affiche le message d'erreur
} else {
    echo 'mail sent';
}

unset($mail);

function test_mail_active($mail)
{
    global $conn;
    $res_mail = "";

    if (trim($mail) != '') {
        $sql_test   = "SELECT distinct matricule,emailpers   FROM personnel where emailpers ilike '" . $mail . "%' and actifpers ='Active'";
        $query_test = pg_query($conn, $sql_test) or die(pg_last_error());
        $nb         = pg_num_rows($query_test);

        if ($nb > 0) {
            $res_mail = $mail;
        }
    }
    return $res_mail;
}
