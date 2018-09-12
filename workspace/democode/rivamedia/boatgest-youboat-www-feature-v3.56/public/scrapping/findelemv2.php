<?php
/**************************************************/
/* FONCTIONS DIVERSES
/**************************************************/

// Fonction de Scraping pour les sites type Philibert ou AYC qui va retrouner l'URL d'un code HTML <a href>
function get_urls($string, $strict=true)
{
    $innerT = $strict?'[a-z0-9:?=&@/._-]+?':'.+?';
    @preg_match_all("|href\=([\"'`])(".$innerT.")\\1|i", $string, $matches);
    return $matches[2];
}

function get_urls_img($string, $strict=true)
{
$innerT = $strict?'[a-z0-9:?=&@/._-]+?':'.+?';
preg_match_all("|src\=([\"'`])(".$innerT.")\\1|i", $string, $matches);
   return $matches[2];
}

// Ca vire les accents
function stripAccents($string)
{
    return strtr($string,'√†√°√¢√£√§√ß√®√©√™√´√¨√≠√Æ√Ø√±√≤√≥√¥√µ√∂√π√∫√ª√º√Ω√ø√Ä√Å√Ç√É√Ñ√á√à√â√ä√ã√å√ç√é√è√ë√í√ì√î√ï√ñ√ô√ö√õ√ú√ù', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

// Ca affiche joliemnt un tableau
function var_print($Var) {
    echo "<pre>" . print_r($Var, true) . "</pre>";
}

// Envoi un mail ) $mail si le $seuilalerte (nombre ‡ partir duquel l'alerte s'envoi) est > 90% a $nbdel (nombre d'annocne efface) pour l'$idautomat en cours, sur l'$idcli en cours
// $infoplus c pour rajouter du texte dans l'objet si besoin de mieux comprendre
function alerte_effacement($mail,$seuilalerte,$nbdel,$idautomat,$idcli,$infoplus){
    $SQL = mysql_fetch_row(mysql_query("SELECT NOM_AUTOMAT from automat_liste WHERE ID_AUTOMAT = ".$idautomat));
    $SQL2 = mysql_fetch_row(mysql_query("SELECT NOM_CLIENT_BATEAU from client_bateau WHERE ID_CLIENT_BATEAU = ".$idcli));
    if ($nbdel >= $seuilalerte) {
        mail($mail,"ALERTE ".$SQL[0]." ".$infoplus,"Le nombre d'annonces effacÈes lors de l'Èxecution du script ".$SQL[0]." pour le client ".$SQL2[0]." terminÈe le ".date('Y-m-d G:i:s')." a dÈpassÈ les 90%.");
    }
}

// Envoi un mail si y a eu trop d'erreur d'URL de photo
function alerte_photo($mail,$idautomat,$idcli){
    $SQL = mysql_fetch_row(mysql_query("SELECT NOM_AUTOMAT from automat_liste WHERE ID_AUTOMAT = ".$idautomat));
    $SQL2 = mysql_fetch_row(mysql_query("SELECT NOM_CLIENT_BATEAU from client_bateau WHERE ID_CLIENT_BATEAU = ".$idcli));

    mail($mail,"[Passerelle ".$SQL[0]."] Trop d'URL echouÈe !","Trop d'URL de photos pÈtÈ chez ".$SQL2[0]." avec la passerelle ".$SQL[0]);
}

// GROS MENAGE D'UNE CHAINE DE CARACTERE
function traitement_string($brut){

    $brut = strtolower($brut); // On le met en Miniscule
    $brut = str_replace("-"," ",$brut); // On vire les tirets
    $brut = str_replace("'","`",$brut); // On vire les quote
    $brut = str_replace("\"","",$brut); // On vire les guillement
    $tabmat = explode("/",$brut); // On prend les premiËres parti de chantier tyep : Benteau / Berret
    $brut = trim($tabmat[0]);    // On enleve les espaces superflues
    $tabmat2 = explode("(",$brut);  // On prend les premiËres parti de chantier tyep : Benteau ( place dispo)
    $brut = trim($tabmat2[0]);    // On enleve les espaces superflues
    $brut = traitement_string_light($brut);
    return $brut;
}

function traitement_string_light($brut){
    $brut = strtr($brut,'¿¡¬√ƒ≈«»… ÀÃÕŒœ“”‘’÷Ÿ⁄€‹Ø‡‚„‰ÂÁËÈÍÎÏÌÓÔ©£ÚÛÙıˆ˘˙˚¸~ˇ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaceeeeiiiioooooouuuuyyy');
    return $brut;
}













/**************************************************/
/* FONCTIONS ON CHERCHE LA MARQUE
/**************************************************/
// Passage des mots de la chaine de caractËre. Trouve la marque entre plusieurs mots d'une chaine ou la marque est prÈsente.
function TrouveMarque($tab_marque,$marque) {
	//echo 'modele:<br><pre>';print_r($tab_modele);echo '</pre><br>';

	foreach ($tab_marque as $key=>$value) {
		$compchaine=$value['NOM_MARQUE_BATEAU'];
		$cpt=0;
		//echo "'".$marque."'<br>";
		//echo "'".$compchaine."'<br>";
		if(strlen($marque)>=strlen($compchaine)) {
			$len1=strlen($marque);
			$len2=strlen($compchaine);
			$str1=$marque;
			$str2=$compchaine;
		}
		else {
			$len1=strlen($compchaine);
			$len2=strlen($marque);
			$str1=$compchaine;
			$str2=$marque;
		}
		$dif=$len1-$len2;

		for($i=0;$i<$len1;$i++) {
			if($str1[$i]==$str2[$i]) $cpt++;
			//echo $str1[$i].'+'.$str2[$i].'  |'.$cpt.'|  ';
		}
		//echo '<br>';
		for($i=$len1;$i>=0;$i--) {
			if($str1[$i]==$str2[$i-$dif]) $cpt++;
			//echo $str1[$i].'-'.$str2[$i-$dif].'  |'.$cpt.'|  ';
		}
		//echo $modele.'-'.$compchaine.'-'.$cpt.'<br>';
		$tab[$cpt]=$value['ID_MODELE_BATEAU'];
	}
	krsort($tab);
	$tab_final = array_values($tab);
	return $tab_final[0];
}

// On recursif les marques juska temps de trouver le bon truc.
function RecursiveMarque($marque, $r = 0, $dechet = '') { //$r permet de savoir si la fonction est en mode recursive
	//echo '----MAR----';
	$select_marque="select id_marque_bateau from marque_bateau where nom_marque_bateau like '".$marque."'";

	$result_marque=mysql_query($select_marque);
	while ($tab_marque_temp=mysql_fetch_array($result_marque, MYSQL_ASSOC)) {
	   $tab_marque[]=$tab_marque_temp;
	}
	$nb_marque=mysql_num_rows($result_marque);
	//echo '<br><pre>';print_r($tab_marque);echo '</pre>';

	//PREMIERE RECHERCHE TROUVE
	if($nb_marque==1 && $r==0) { return $tab_marque[0]['id_marque_bateau'];}//trouvÈ
	if($nb_marque>1 && $r==0) { //trouvÈ mais plusieurs marque
		//return $tab_marque[0]['id_marque_bateau']; //on retourne la marque la plus clickÈ, donc plus populaire
		return TrouveMarque($tab_marque,$marque.$dechet);
	}

	//SUITE RECHERCHE TANT QUE PAS TROUVE
	elseif($nb_marque==0){	//on recherche avec moins d'info et on indique qu'on passe en mode recursive
		if(strlen($marque)>1) {
			$marque2 = substr($marque,0,-1);
			$dechet = substr($marque,-1,1).$dechet;
			return RecursiveMarque($marque2,1,$dechet);
		}
		else return 0;
	}
}

// Va nous retourner un id_marque selon les methodes appelÈ soit en recursif soit en synonyme
function return_id_marque($string_marque,$utf8 = 0){

    global $nommarktemp;

    // On traite physiquement le chantier recu dans le fichier pour lui enlever possible parasite
    if ($utf8 == 1) $string_marque = utf8_decode($string_marque);
    $traitemarque = traitement_string(trim($string_marque));
    // On initialise la var $nommarktemp de la valeur nettoyÈ.
    $nommarktemp = $traitemarque;
    // ON Tente une premiËre fois de rÈcuperer l'id marque selon le chantier ecris en lui appliquant une simple requete de reconnaissance
    $id_marque=RecursiveMarque($traitemarque);
    // SI Pas trouvÈ, on le passe dans le dictionnaire des synonymes en Base
    if($id_marque == '') $id_marque = synonyme_marque_bdd ($traitemarque);

    return $id_marque;
}




















/**************************************************/
/* FONCTIONS ON CHERCHE LE MODELE
/**************************************************/
function TrouveModele($tab_modele,$modele) {
	//echo 'modele:<br><pre>';print_r($tab_modele);echo '</pre><br>';

	foreach ($tab_modele as $key=>$value) {
		$compchaine=$value['NOM_MODELE_BATEAU'];
		$cpt=0;
		//echo "'".$modele."'<br>";
		//echo "'".$compchaine."'<br>";
		if(strlen($modele)>=strlen($compchaine)) {
			$len1=strlen($modele);
			$len2=strlen($compchaine);
			$str1=$modele;
			$str2=$compchaine;
		}
		else {
			$len1=strlen($compchaine);
			$len2=strlen($modele);
			$str1=$compchaine;
			$str2=$modele;
		}
		$dif=$len1-$len2;

		for($i=0;$i<$len1;$i++) {
			if($str1[$i]==$str2[$i]) $cpt++;
			//echo $str1[$i].'+'.$str2[$i].'  |'.$cpt.'|  ';
		}
		//echo '<br>';
		for($i=$len1;$i>=0;$i--) {
			if($str1[$i]==$str2[$i-$dif]) $cpt++;
			//echo $str1[$i].'-'.$str2[$i-$dif].'  |'.$cpt.'|  ';
		}
		//echo $modele.'-'.$compchaine.'-'.$cpt.'<br>';
		$tab2[$key]=$cpt;
	}
	arsort($tab2);
	$max=0;
	preg_match("(\d+)",$modele,$result);
	//echo '<pre>';print_r($result);echo '</pre><br>';

	$id_modele='';
	if($result[0]!='') {
		foreach($tab2 as $key=>$value) {
			if($value>=$max) {
				$max=$value;
			}
			if($value==$max) {
				//echo $tab_modele[$key]['NOM_MODELE_BATEAU'].'<br>';
				$max=$value;
				if(preg_match("/".$result[0]."/",$tab_modele[$key]['NOM_MODELE_BATEAU'],$resultat)==1)
					$id_modele=$tab_modele[$key]['ID_MODELE_BATEAU'];
			}
		}
	}
	return $id_modele;
}



function return_id_modele($string_modele,$id_marque,$id_automat,$utf8=0){

     global $nommarktemp,$nommodtemp,$id_marque;

    // On traite physiquement le modele recu dans le fichier pour lui enlever possible parasite
    if ($utf8 == 1) $string_modele = utf8_decode($string_modele);
    $traitemodele = traitement_string(trim($string_modele));

    // On dÈcoupe en tableau d'espace les mots du modele a des fins d'aide a trouver les IDs grace au premier mot du modËle souvent rÈpÈtÈ de la marque
    $tabmodt = explode(" ",$traitemodele);

    /* Cas ou dans le fichier texte, le chantier n'a pas ÈtÈ renseignÈ */
    if ($nommarktemp == ""){

        // On parcourt les mots du modele
        foreach ($tabmodt as $valq){

            // Tant que $id_marque non trouvÈe on y retourne
            if($id_marque==''){
                $id_marque = return_id_marque($valq);
            }
        }
    /* Cas classique ou le chantier a bien ÈtÈ ecris dnas le fichier texte */
    } else {
        // Si le premier mot du modele est egal ‡ la marque (Type : quicksilver / quicksilver 400)
        if ($tabmodt[0] == $nommarktemp){
            // Si le deuxiËme mot n'est pas numÈrique
            // Cas concrt : Beneteau / Beneteau Antares 800 => On va dÈgager beneteau dans le modËle qui ne sert a rien
            // Par contre si : Beneteau / Beneteau 800 => La on ne fait rien car le deuxiËme mot est numÈrique
            if (!is_numeric(trim($tabmodt[1]))){
                array_shift ($tabmodt); // on supprime le premier mot
                $traitemodele = implode (" ",$tabmodt); // et on reconstitue le modele dans le doublon de marque
            }
        }
    }

    $nommodtemp = $traitemodele;

    // Si idmarque a ÈtÈ trouvÈe, on cherche le modele dÈsormais
    // Si idmarque a ÈtÈ trouvÈe, on cherche le modele dÈsormais
    if ($id_marque > 0) {

        $vraimrq = mysql_fetch_row(mysql_query("SELECT NOM_MARQUE_BATEAU from marque_bateau WHERE ID_MARQUE_BATEAU = ".$id_marque));

        $id_modele = RecursiveModele($id_marque, $traitemodele); // Reconnaissance classique

        if ($id_modele == '')
            $id_modele = synonyme_modele_bdd($id_marque, $traitemodele); // Si pas efficace, dictionnaire des synonymes BDD

        if ($id_modele == '')  // Si on a pas trouvÈ le modËle on tente de le trouver en rajoutant la marque devant le modele
            $id_modele = RecursiveModele($id_marque, $nommarktemp . ' ' . $traitemodele);

        if ($id_modele == '')
            $id_modele = synonyme_modele_bdd($id_marque, $nommarktemp . ' ' . $traitemodele); // Si pas efficace, dictionnaire des synonymes BDD

        if ($id_modele == '')  // Si on a pas trouvÈ le modËle on tente de le trouver en rajoutant la marque devant le modele
            $id_modele = RecursiveModele($id_marque, $vraimrq[0] . ' ' . $traitemodele);

        if ($id_modele == '')
            $id_modele = synonyme_modele_bdd($id_marque, $vraimrq[0] . ' ' . $traitemodele); // Si pas efficace, dictionnaire des synonymes BDD

        if ($id_modele == '') {
            $traitemodele2 = str_replace (array("-","."),array(" ",""),$traitemodele);

            $id_modele = RecursiveModele($id_marque, $traitemodele2); // Reconnaissance classique

            if ($id_modele == '')
                $id_modele = synonyme_modele_bdd($id_marque, $traitemodele2); // Si pas efficace, dictionnaire des synonymes BDD (07/2012)

            if ($id_modele == '')  // Si on a pas trouvÈ le modËle on tente de le trouver en rajoutant la marque devant le modele
                $id_modele = RecursiveModele($id_marque, $nommarktemp . ' ' . $traitemodele2);

            if ($id_modele == '')
                $id_modele = synonyme_modele_bdd($id_marque, $nommarktemp . ' ' . $traitemodele2); // Si pas efficace, dictionnaire des synonymes BDD (07/2012)

            if ($id_modele == '')  // Si on a pas trouvÈ le modËle on tente de le trouver en rajoutant la marque devant le modele
                $id_modele = RecursiveModele($id_marque, $vraimrq[0] . ' ' . $traitemodele2);

            if ($id_modele == '')
                $id_modele = synonyme_modele_bdd($id_marque, $vraimrq[0] . ' ' . $traitemodele2); // Si pas efficace, dictionnaire des synonymes BDD (07/2012)
        }
    }

    // GESTION ERREUR
    // Si marque ou modele non trouvÈ, on a joute ‡ la base une ligne d'erreur
    if (($id_marque == "")||($id_modele == "")){
        
        // Sauf si la marque / modele est en Blacklist (Ex : Mise a l'eau tartanpion) qui n'est pas du tout un chantier ou un modele
        $verifbl = mysql_num_rows(mysql_query("SELECT ID from automat_blacklist WHERE NOM = '".stripslashes($nommarktemp." ".$nommodtemp)."' AND ID_AUTOMAT = ".ID_AUTOMAT));
        if ($verifbl == 0) insert_erreur($nommarktemp." ".$nommodtemp, $id_marque, $nommarktemp, $id_modele, $nommodtemp, ID_AUTOMAT);
    } else {
        return $id_modele;
    }
        
}

function RecursiveModele($idmarque,$modele, $r = 0, $dechet = '') { //$r permet de savoir si la fonction est en mode recursive

	/*$select_marque="select * from marqe_bateau M where id_marque=".$idmarque;
	$result_marque=mysql_query($select_modele);
	$nom_marque=mysql_result($result_marque,0,1);
	*/
	$tab_modele=array();
	switch ($r) {
		case 0:
			//recherche simple en premiere passe
			$select_modele="select * from modele_bateau A where nom_modele_bateau like '".$modele."' and id_marque_bateau=".$idmarque." order by nom_modele_bateau asc";
			$result_modele=mysql_query($select_modele);
			$nb_modele=@mysql_num_rows($result_modele);
			//echo $select_modele.'<br>';
			//PREMIERE RECHERCHE TROUVE
			if($nb_modele==1) { //trouvÈ
				//echo '<font color="green">'.mysql_result($result_modele,0,0).'</font><br>';
				return mysql_result($result_modele,0,0);
			}
			if($nb_modele>1) { //trouvÈ mais plusieurs modele
				//echo '<font color="black">r=0 - plusieurs modËles trouvÈes avec "'.$modele.'"</font><br>';
				while ($tab_modele_temp=mysql_fetch_array($result_modele, MYSQL_ASSOC)) {
				   $tab_modele[]=$tab_modele_temp;
				   //print_r($tab_modele_temp);echo '<br>';
				}
				return TrouveModele($tab_modele,$modele);
			}

		break;

		case 1:
			//recursion avec modele modifiÈ
			$select_modele="select * from modele_bateau where nom_modele_bateau like '".$modele."%' and id_marque_bateau=".$idmarque." order by nom_modele_bateau asc";
			$result_modele=mysql_query($select_modele);
			$nb_modele=@mysql_num_rows($result_modele);
			//echo $select_modele.'<br>';
			//PREMIERE RECHERCHE TROUVE
			if($nb_modele==1) { //trouvÈ
				//echo '<font color="green">'.mysql_result($result_modele,0,0).'</font><br>';
				return mysql_result($result_modele,0,0);
			}
			if($nb_modele>1) { //trouvÈ mais plusieurs modele
				//echo '<font color="black">r=1 - plusieurs modËles trouvÈes avec "'.$modele.'%"</font><br>';
				while ($tab_modele_temp=mysql_fetch_array($result_modele, MYSQL_ASSOC)) {
				   $tab_modele[]=$tab_modele_temp;
				   //print_r($tab_modele_temp);echo '<br>';
				}
				return TrouveModele($tab_modele,$modele.$dechet);
			}
			else {
				if(strlen($modele)>2) {
					$modele2 = substr($modele,0,-1);
					$dechet = substr($modele,-1,1).$dechet;
					return RecursiveModele($idmarque,$modele2,1,$dechet);
				}
				else return 0;
			}
		break;
	}
}



















/**************************************************/
/* FONCTIONS DE RECHERCHE DE MARQUE MOTEUR
/**************************************************/
// Choppe l'idmarquemoteur, si possible l'energie,sinon ces valeurs sont en default.
function recup_marque_moteur($brut)
{
    global $idmarquemoteur,$nrj;

    $idmarquemoteur = '';

    $brut = traitement_string($brut);
    $tabmotor = explode(" ",$brut);
    foreach ($tabmotor as $value){
        $SQL = "SELECT ID_MARQUE_MOTEUR_BATEAU from marque_moteur_bateau WHERE NOM_MARQUE_MOTEUR_BATEAU LIKE '".$value."%'";
        $RES = mysql_query($SQL);
        if ((mysql_num_rows($RES) > 0)&& ($idmarquemoteur == '')){
            $VAL = mysql_fetch_row($RES);
            $idmarquemoteur = $VAL[0];
            break;
        }
    }
    foreach ($tabmotor as $value){
        $SQL2 = "SELECT ID_ENERGIE_BATEAU from energie_bateau WHERE NOM_ENERGIE_BATEAU = '".$value."'";
        $RES2 = mysql_query($SQL2);
        if (mysql_num_rows($RES2) > 0){
            $VAL2 = mysql_fetch_row($RES2);
            $nrj = $VAL2[0];
            break;
        }
    }

    if ($nrj == "") $nrj = 3;
    if ($idmarquemoteur == "") $idmarquemoteur = 1;
}




















/**************************************************/
/* FONCTIONS INSERT D'AUTOMAT
/**************************************************/
// Insertion d'une erreur de marque/modele dans la table
function insert_erreur($annonceTrouve,$idmarque,$nommarque,$idmodele,$nommodele,$automat)
{
    $instantdate = date("Y-m-d G:i:s");

    $INS = "INSERT INTO automat_erreur VALUES (
            '',
            ".$automat.",
            '".addslashes($annonceTrouve)."',
            '".$idmarque."',
            '".addslashes($nommarque)."',
            '".$idmodele."',
            '".addslashes($nommodele)."',
            '".$instantdate."')

    ";
    mysql_query($INS);
}


// INSERT UNE ANNONCE
function insert_automat($tab,$prefixe,$replog,$argv){

    $ms = date('m');
    $year = date('Y');

    global $cptERROR,$cptOK,$intitule,$exit_flood;


    if (($tab[paysvis] == "")||(!$tab[paysvis])) $tab[paysvis] = 100;
    if ($tab[idtypeann] == "") $tab[idtypeann] = 1;
    if ($tab[ppport] == "") $tab[ppport] = 0;
    if ($tab[listeq]) $strlisteq = @implode(";",$tab[listeq]);
    if ($tab[listel]) $strlistel = @implode(";",$tab[listel]);
    if ($tab[apartirde] == "") $tab[apartirde] = 0;
    if ($tab[listequiubi]) $strlisteq = str_replace(",",";",$tab[listequiubi]);
    if ($tab[listelecubi]) $strlistel = str_replace(",",";",$tab[listelecubi]);
    if ($tab[taxe] == "") $tab[taxe] = "TTC";
    if (($tab[ismoteur] == "")||(!$tab[ismoteur])) $tab[ismoteur] = 'Y';
    if ($tab[nonvis] == '1') $tab[nonvis] = 'N'; else $tab[nonvis] = 'Y';

    // MOTEUR
    if ($tab[type] == 4){

        // INSERTION DE L'ANNONCE DANS LA BASE DE DONNEE
        $insAds = "
                INSERT INTO `annonce_moteur` VALUES (
                '',
                ".$tab[idcontrat].",
                ".$tab[sscat].",
                '".$tab[nrj]."',
                '".$tab[idmarquemoteur]."',
                '".addslashes($tab[modelemot])."',
                '".str_link_mod($tab[modelemot])."',
                ".$tab[idtypeann].",
                '".$tab[puissance]."',
                '',
                '".$tab[annee]."',
                '".$tab[nbhrmot]."',
                '',
                '".$prefixe.$tab[idclient]."_".$tab[ref]."',
                '".addslashes($tab[txtopt])."',
                '',
                '".$tab[prix]."',
                '".$tab[taxe]."',
                '',
                '".$tab[apartirde]."',
                '',
                'Y',
                NOW(),
                NOW(),
                '')";

        $suftype = 'moteur';



    } else if ($tab[type] == 99) {
 // INSERTION DE L'ANNONCE DANS LA BASE DE DONNEE
    $insAds = "
            INSERT INTO `annonce_bateau_location` VALUES (
            '',
            ".$tab[idcontrat].",
            ".$tab[id_modele].",
            ".$tab[sscat].",
            '".$prefixe.$tab[idclient]."_".$tab[ref]."',
            '".$tab[idmarquemoteur]."',
            '".$tab[nrj]."',
            '".$tab[propulsion]."',
            '".$tab[nbmot]."',
            '".$tab[puissance]."',
            '".addslashes($tab[infomot])."',
            '".$tab[anneemot]."',
            '".$tab[nbhrmot]."',
            '".$tab[ismoteur]."',
            '".str_replace(",",".",$tab[longueur])."',
            '".str_replace(",",".",$tab[largeur])."',
            '".$tab[annee]."',
            '".$tab[nbcab]."',
            '".$tab[nbcouch]."',
            '".$tab[nbsdb]."',
            '".$strlisteq."',
            '".$strlistel."',
            '',
            '".addslashes($tab[txtopt])."',
            '',
            ".$tab[port].",
            '///',
            '".$tab[prix]."',
            'Y',
            NOW(),
            NOW(),
            '')";
    $suftype = 'location';
    } else if ($tab[type] == 5) {

        // INSERTION DE L'ANNONCE DANS LA BASE DE DONNEE
        $insAds = "
                INSERT INTO `annonce_divers` VALUES (
                '',
                ".$tab[idcontrat].",
                1,
                ".$tab[sscat].",
                '',
                '".$prefixe.$tab[idclient]."_".$tab[ref]."',
                '".addslashes($tab[intitule])."',
                '',
                '',
                'IndiffÈrent',
                '',
                '',
                '".$tab[ptac]."',
                '',
                '".addslashes($tab[txtopt])."',
                '',
                '".$tab[prix]."',
                '".$tab[taxe]."',
                '',
                '".$tab[apartirde]."',
                '',
                'Y',
                NOW(),
                NOW(),
                '')";

        $suftype = 'divers';

    } else {

    // INSERTION DE L'ANNONCE DANS LA BASE DE DONNEE
    $insAds = "
            INSERT INTO `annonce_bateau` VALUES (
            '',
            ".$tab[idcontrat].",
            ".$tab[id_modele].",
            ".$tab[sscat].",
            ".$tab[idtypeann].",
            '".$prefixe.$tab[idclient]."_".$tab[ref]."',
            '".$tab[idmarquemoteur]."',
            '".$tab[nrj]."',
            '".$tab[propulsion]."',
            '".$tab[nbmot]."',
            '".$tab[puissance]."',
            '".addslashes($tab[infomot])."',
            '".$tab[anneemot]."',
            '".$tab[nbhrmot]."',
            '".$tab[ismoteur]."',
            '".str_replace(",",".",$tab[longueur])."',
            '".str_replace(",",".",$tab[largeur])."',
            '".$tab[annee]."',
            '".$tab[nbcab]."',
            '".$tab[nbcouch]."',
            '".$tab[nbsdb]."',
            '".$strlisteq."',
            '".$strlistel."',
            '".$tab[sssscat]."',
            '".addslashes($tab[txtopt])."',
            '',
            '".addslashes($tab[ville]).";".$tab[paysvis].";".$tab[nonvis]."',
            '".$tab[gar]."',
            '".$tab[ppport]."',
            '".$tab[prix]."',
            '".$tab[taxe]."',
            '',
            '".$tab[apartirde]."',
            '".$tab[percentpromo]."',
            'Y',
            NOW(),
            NOW(),
            '')";
    $suftype = 'bateau';
}

            if (!mysql_query($insAds)){
                $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
                fwrite($pfichier,"INSERT ADS ECHEC. SQL -> ".$insAds."
");
                fclose($pfichier);
                $cptERROR++;
                echo "INSERT KO -> ".$insAds."\n";
            } else {


                // NOUVELLE ID DE l'ANNONCE
                $lstIdAds = mysql_insert_id();

                // INSERTION DE LA LIGNE D'ANNONCE POUR LES STATS DU CLIENT
                $insStats = "INSERT INTO `stats_client` VALUES (
                                                '',
                                                ".$lstIdAds.",
                                                '".$suftype."',
                                                ".$tab[idclient].",
                                                0,
                                                0,
                                                0,
                                                0
                                        )";
                $resStats = mysql_query($insStats) or die ($insStats." : ".mysql_error());

                // INSERTION DE LA LIGNE D'ANNONCE POUR LES STATS DU CLISTAT
                $insStatsT = "INSERT INTO `stats_total` VALUES (
                                                '',
                                                ".$lstIdAds.",
                                                '".$suftype."',
                                                ".$tab[idclient].",
                                                0,
                                                0,
                                                0,
                                                0,
                                                '".date('Y')."-".date('m')."-01'
                                        )";
                $resStatsT = mysql_query($insStatsT) or die ($insStatsT." : ".mysql_error());
                
                nameproduit_fromId($lstIdAds,$tab[type]);

                // ARGUMENT PASSE EN SCRIPT QUI NE MET PAS LES IMAGES A JOUR
                if (!in_array("noimg",$argv)){
                    $posphoto = 1;
                    if ($tab[urlphoto][0] != ''){ // ON MODIFIE LES IMAGES UNIQUEMENT SI LA PREMIERE IMAGE EST INDIQUE DANS LE FICHIER XML/CSV
                        foreach ($tab[urlphoto] as $photo){
                            if ($tab[typesource] != "distant") $photo = $tab[chemsource].$photo;
                            img_copy($photo, $tab[typesource], $posphoto, $suftype, $prefixe, $tab, $lstIdAds, $intitule, $replog);
                            $posphoto++;
                        }
                    } else {
                        echo "0.";
                    }
                }

                $cptOK++;
                echo " > INSERE !\n";
                

            }
}
















/**************************************************/
/* FONCTIONS UPDATE D'AUTOMAT
/**************************************************/
// Update d'une annonce d'automat
function update_automat($tab,$prefixe,$replog,$argv)
{
    global $intitule,$cptUPDATE,$cptERROR,$tab_traite_posimg,$noupddecisoft,$exit_flood;

    $ms = date('m');
    $year = date('Y');
    $noupddecisoft = 0;

    if (($tab[paysvis] == "")||(!$tab[paysvis])) $tab[paysvis] = 100;
    if ($tab[idtypeann] == "") $tab[idtypeann] = 1;
    if ($tab[ppport] == "") $tab[ppport] = 0;
    if ($tab[apartirde] == "") $tab[apartirde] = 0;
    if ($tab[listeq]) $strlisteq = @implode(";",$tab[listeq]);
    if ($tab[listel]) $strlistel = @implode(";",$tab[listel]);
    if ($tab[listequiubi]) $strlisteq = str_replace(",",";",$tab[listequiubi]);
    if ($tab[listelecubi]) $strlistel = str_replace(",",";",$tab[listelecubi]);
    if ($tab[taxe] == "") $tab[taxe] = "TTC";
    if (($tab[ismoteur] == "")||(!$tab[ismoteur])) $tab[ismoteur] = 'Y';
    if ($tab[nonvis] == '1') $tab[nonvis] = 'N'; else $tab[nonvis] = 'Y';


    // dÈsactivÈ pour le moment : `DATE_M_ANNONCE_BATEAU` = '".$instantdate."'

    // MOTEUR
    if ($tab[type] == 4){
        if (in_array("updatedate",$argv)) $majAdsPlus = " DATE_M_ANNONCE_MOTEUR = NOW(),";
        // Maj de l'annonce
        $majAds = "
                UPDATE annonce_moteur SET
                        `NOM_MODELE_MOTEUR` = '".addslashes($tab[modelemot])."',
                        `REWRITE_MODELE_MOTEUR` = '".str_link_mod($tab[modelemot])."',
                        `ID_CATEGORIE_BATEAU` = ".$tab[sscat].",
                        `ID_MARQUE_MOTEUR_BATEAU` =  '".$tab[idmarquemoteur]."',
                        `ID_ENERGIE_BATEAU` =  '".$tab[nrj]."',
                        `PUISSANCE_ANNONCE_MOTEUR` =  '".$tab[puissance]."',
                        `NB_HEURES_MOTEUR` =  '".$tab[nbhrmot]."',
                        `ANNEE_ANNONCE_MOTEUR` =  '".$tab[annee]."',
                        `TEXTE_ANNONCE_MOTEUR` = '".addslashes($tab[txtopt])."',
                        `PRIX_ANNONCE_MOTEUR` = ".$tab[prix].",
                         TAXE_ANNONCE_MOTEUR = '".$tab[taxe]."',
                         APARTIRDE = '".$tab[apartirde]."',
                         ".$majAdsPlus."
                        `ID_TYPE_ANNONCE_BATEAU`  = ".$tab[idtypeann]."
                WHERE
                        `REF_ANNONCE_MOTEUR` = '".$prefixe.$tab[idclient]."_".$tab[ref]."'";
        $suftype = 'moteur';
    
    } else if ($tab[type] == 99) {
    if (in_array("updatedate",$argv)) $majAdsPlus = " DATE_M_ANNONCE_BATEAU = NOW(),";
    // Maj de l'annonce
    $majAds = "
            UPDATE annonce_bateau_location SET
                    `ID_MODELE_BATEAU` = ".$tab[id_modele].",
                    `ID_CATEGORIE_BATEAU` = ".$tab[sscat].",
                    `IS_MOTEUR_BATEAU` = '".$tab[ismoteur]."',
                    `ID_MARQUE_MOTEUR_BATEAU` =  '".$tab[idmarquemoteur]."',
                    `ID_ENERGIE_BATEAU` =  '".$tab[nrj]."',
                    `ID_PROPULSION_BATEAU` = '".$tab[propulsion]."',
                    `NB_MOTEUR_BATEAU` =  '".$tab[nbmot]."',
                    `PUISSANCE_MOTEUR_BATEAU` =  '".$tab[puissance]."',
                    `INFOS_MOTEUR_BATEAU` =  '".addslashes($tab[infomot])."',
                    `NB_HEURES_MOTEUR` =  '".$tab[nbhrmot]."',
                    `ANNEE_MOTEUR_BATEAU` =  '".$tab[anneemot]."',
                    `LONGUEUR_ANNONCE_BATEAU` = '".str_replace(",",".",$tab[longueur])."',
                    `LARGEUR_ANNONCE_BATEAU` = '".str_replace(",",".",$tab[largeur])."',
                    `ANNEE_ANNONCE_BATEAU` = '".$tab[annee]."',
                    `NB_CABINE_BATEAU` = '".$tab[nbcab]."',
                    `NB_COUCHETTE_BATEAU` = '".$tab[nbcouch]."',
                    `NB_SDB_BATEAU` = '".$tab[nbsdb]."',
                    IDS_EQUIP_BATEAU = '".$strlisteq."',
                    IDS_ELECTRO_BATEAU = '".$strlistel."',
                    `TEXTE_ANNONCE_BATEAU` = '".addslashes($tab[txtopt])."',
                    `PRIX_ANNONCE_BATEAU` = '".$tab[prix]."',                    
                    ".$majAdsPlus."
                    ID_PORT = ".$tab[port]."

            WHERE
                    `REF_ANNONCE_BATEAU` = '".$prefixe.$tab[idclient]."_".$tab[ref]."'";
   $suftype = "location";
   } else if ($tab[type] == 5) {
       if (in_array("updatedate",$argv)) $majAdsPlus = " DATE_M_ANNONCE_DIVERS = NOW(),";
        // Maj de l'annonce
        $majAds = "
                UPDATE annonce_divers SET
                        `ID_CATEGORIE_BATEAU` = ".$tab[sscat].",
                        INTITULE_ANNONCE_DIVERS = '".addslashes($tab[intitule])."',
                        REMORQUE_PTAC = '".$tab[ptac]."',
                        `TEXTE_ANNONCE_DIVERS` = '".addslashes($tab[txtopt])."',
                        `PRIX_ANNONCE_DIVERS` = ".$tab[prix].",
                        ".$majAdsPlus."
                        TAXE_ANNONCE_DIVERS = '".$tab[taxe]."'
                WHERE
                        `REF_ANNONCE_DIVERS` = '".$prefixe.$tab[idclient]."_".$tab[ref]."'";
        $suftype = 'divers';
    } else {
    // Maj de l'annonce
    if (in_array("updatedate",$argv)) $majAdsPlus = " DATE_M_ANNONCE_BATEAU = NOW(),";
    $majAds = "
            UPDATE annonce_bateau SET
                    `ID_MODELE_BATEAU` = ".$tab[id_modele].",
                    `ID_CATEGORIE_BATEAU` = ".$tab[sscat].",
                    `IS_MOTEUR_BATEAU` = '".$tab[ismoteur]."',
                    `ID_MARQUE_MOTEUR_BATEAU` =  '".$tab[idmarquemoteur]."',
                    `ID_ENERGIE_BATEAU` =  '".$tab[nrj]."',
                    `ID_PROPULSION_BATEAU` = '".$tab[propulsion]."',
                    `NB_MOTEUR_BATEAU` =  '".$tab[nbmot]."',
                    `PUISSANCE_MOTEUR_BATEAU` =  '".$tab[puissance]."',
                    `INFOS_MOTEUR_BATEAU` =  '".addslashes($tab[infomot])."',
                    `NB_HEURES_MOTEUR` =  '".$tab[nbhrmot]."',
                    `ANNEE_MOTEUR_BATEAU` =  '".$tab[anneemot]."',
                    `LONGUEUR_ANNONCE_BATEAU` = '".str_replace(",",".",$tab[longueur])."',
                    `LARGEUR_ANNONCE_BATEAU` = '".str_replace(",",".",$tab[largeur])."',
                    `ANNEE_ANNONCE_BATEAU` = '".$tab[annee]."',
                    `NB_CABINE_BATEAU` = '".$tab[nbcab]."',
                    `NB_COUCHETTE_BATEAU` = '".$tab[nbcouch]."',
                    `NB_SDB_BATEAU` = '".$tab[nbsdb]."',
                    IDS_EQUIP_BATEAU = '".$strlisteq."',
                    IDS_ELECTRO_BATEAU = '".$strlistel."',
                     ID_SSCAT_BATEAU = '".$tab[sssscat]."',
                    `TEXTE_ANNONCE_BATEAU` = '".addslashes($tab[txtopt])."',
                    `VISIBLE_ANNONCE_BATEAU` = '".addslashes($tab[ville]).";".$tab[paysvis].";".$tab[nonvis]."',
                    `PRIX_ANNONCE_BATEAU` = ".$tab[prix].",
                    `TAXE_ANNONCE_BATEAU` = '".$tab[taxe]."',
                    `GARANTIE_ANNONCE_BATEAU` = '".$tab[gar]."',
                    `PLACE_DE_PORT_ANNONCE_BATEAU` = '".$tab[ppport]."',
                     PERCENT_PROMO = '".$tab[percentpromo]."',
                    ".$majAdsPlus."
                    `ID_TYPE_ANNONCE_BATEAU`  = ".$tab[idtypeann]."

            WHERE
                    `REF_ANNONCE_BATEAU` = '".$prefixe.$tab[idclient]."_".$tab[ref]."'";
    $suftype = 'bateau';
    }


    //echo $majAds."\n";
    if (!mysql_query($majAds)){
            $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
            fwrite($pfichier,"MAJ ADS ECHEC. SQL -> ".$majAds."
");
            fclose($pfichier);
            $cptERROR++;
            echo "MAJ KO -> ".$majAds."\n";
    } else {

        //MAJ PHOTO
        if ($suftype == "location"){
        $recupAnnId = mysql_fetch_array(mysql_query("
            SELECT ID_ANNONCE_BATEAU
            FROM annonce_bateau_location WHERE
            REF_ANNONCE_BATEAU = '".$prefixe.$tab[idclient]."_".$tab[ref]."'
            AND PUBLIER_ANNONCE_BATEAU = 'Y'
            AND ID_CONTRAT_NET_BATEAU = ".$tab[idcontrat]));     
        } else {
        $recupAnnId = mysql_fetch_array(mysql_query("
            SELECT ID_ANNONCE_".$suftype."
            FROM annonce_".$suftype." WHERE
            REF_ANNONCE_".$suftype." = '".$prefixe.$tab[idclient]."_".$tab[ref]."'
            AND PUBLIER_ANNONCE_".$suftype." = 'Y'
            AND ID_CONTRAT_NET_BATEAU = ".$tab[idcontrat]));
        }

        nameproduit_fromId($recupAnnId[0],$tab[type]);

        // PAS DE MAJ DE PHOTO SI ARGUMENT DONNEE
        if (!in_array("noimg",$argv)){
            // GESTION PHOTO ------------------------
            $posphoto = 1;
            if ($tab[urlphoto][0] != ''){ // ON MODIFIE LES IMAGES UNIQUEMENT SI LA PREMIERE IMAGE EST INDIQUE DANS LE FICHIER XML/CSV
                $tab_traite_posimg = array();
                foreach ($tab[urlphoto] as $photo){
                    if ($tab[typesource] != "distant") $photo = $tab[chemsource].$photo;
                    img_copy($photo, $tab[typesource], $posphoto, $suftype, $prefixe, $tab, $recupAnnId[0], $intitule, $replog);
                    $posphoto++;
                }
            } else {
                echo "0.";
            }
            //echo 'NOUPDDECO '.$noupddecisoft.'-';
            if ($noupddecisoft != 1){
                purge_photo($tab_traite_posimg,$recupAnnId[0],$suftype,$tab[idclient]);
            }
        }

        // FIN PHOTO ------------------------

        echo " > MAJ !\n";
        $cptUPDATE++;

    }

}


function purge_photo($tabATraiter,$idAnn,$typAnn,$idclient)
{

    // SELECTION DES ID PHOTO ET INFOS PHOTO EN COURS
    $SQL = "SELECT DISTINCT ID_PHOTO, TEXTE_FICHIER, TYPE_ANNONCE, POSITION_PHOTO from photo_annonce WHERE ID_ANNONCE = ".$idAnn." AND TYPE_ANNONCE = '".$typAnn."' ORDER BY POSITION_PHOTO";
    $RES = mysql_query($SQL);
    while ($VAL = mysql_fetch_array($RES)){
        $tabEnCours[$VAL[3]] = $VAL[TYPE_ANNONCE].'_'.$VAL[TEXTE_FICHIER].'_'.$VAL[ID_PHOTO];
        $tabPos[] = $VAL[3];
    }

    // On exporte les Position de photos non traitÈ (donc a effacÈ)
    $tabAPurger = array_diff((array)$tabPos,(array)$tabATraiter);
    //print_r($tabPos);
    //print_r($tabATraiter);
    //print_r($tabAPurger);
    // On purge
    foreach ($tabAPurger as $value){
       // $explodetab = explode("_",$tabEnCours[$value]);
        unlink(PATH_YB."images/photo/".$idclient."/tb/".$tabEnCours[$value].".jpg");
        unlink(PATH_YB."images/photo/".$idclient."/normal/".$tabEnCours[$value].".jpg");
        unlink(PATH_YB."images/photo/".$idclient."/origine/".$tabEnCours[$value].".jpg");
        $DEL = "DELETE FROM photo_annonce WHERE ID_ANNONCE = ".$idAnn." AND TYPE_ANNONCE = '".$typAnn."' AND POSITION_PHOTO = ".$value."";
        mysql_query($DEL) or die ($DEL.' '.mysql_error());
    }

    // On rÈcupËre les nouveaux id Photo
    $tabIdPhoto = array();
    $SQL = "SELECT DISTINCT ID_PHOTO from photo_annonce WHERE ID_ANNONCE = ".$idAnn." AND TYPE_ANNONCE = '".$typAnn."' ORDER BY POSITION_PHOTO";
    $RES = mysql_query($SQL);
    while ($VAL = mysql_fetch_array($RES)){
        $tabIdPhoto[] = $VAL[0];
    }

    //On reclasse les positions.
    $i = 1;
    foreach ($tabIdPhoto as $idpht){
        $sql = "UPDATE photo_annonce SET POSITION_PHOTO = '".$i."' WHERE ID_PHOTO = ".$idpht;
        mysql_query($sql) or die ($sql." ".mysql_error());
        $i++;
    }

}








function update_automat_DECISOFT($tab,$prefixe,$replog='')
{
    global $intitule;

    $instantdate = date("Y-m-d G:i:s");
    $ms = date('m');
    $year = date('Y');

    if (($tab[paysvis] == "")||(!$tab[paysvis])) $tab[paysvis] = 100;
    if ($tab[idtypeann] == "") $tab[idtypeann] = 1;
    if ($tab[listeq]) $strlisteq = @implode(";",$tab[listeq]);
    if ($tab[listel]) $strlistel = @implode(";",$tab[listel]);

    if ($tab[ismoteur] == 'N'){
        $tab[idmarquemoteur] = 1;
        $tab[nrj] = 3;
        $tab[propulsion] = 1;
    }

    // dÈsactivÈ pour le moment : `DATE_M_ANNONCE_BATEAU` = '".$instantdate."'

    // MOTEUR
    if ($tab[type] == 4){

        // Maj de l'annonce
        $majAds = "
                UPDATE annonce_moteur SET
                        `NOM_MODELE_MOTEUR` = '".addslashes($tab[modelemot])."',
                        `REWRITE_MODELE_MOTEUR` = '".str_link_mod($tab[modelemot])."',
                        `ID_CATEGORIE_BATEAU` = ".$tab[sscat].",
                        `ID_MARQUE_MOTEUR_BATEAU` =  '".$tab[idmarquemoteur]."',
                        `ID_ENERGIE_BATEAU` =  '".$tab[nrj]."',
                        `PUISSANCE_ANNONCE_MOTEUR` =  '".$tab[puissance]."',
                        `NB_HEURES_MOTEUR` =  '".$tab[nbhrmot]."',
                        `ANNEE_ANNONCE_MOTEUR` =  '".$tab[annee]."',
                        `TEXTE_ANNONCE_MOTEUR` = '".addslashes($tab[txtopt])."',
                        `PRIX_ANNONCE_MOTEUR` = ".$tab[prix].",
                         TAXE_ANNONCE_MOTEUR = '".$tab[taxe]."',
                        `ID_TYPE_ANNONCE_BATEAU`  = ".$tab[idtypeann]."
                WHERE
                        `REF_ANNONCE_MOTEUR` = '".$prefixe.$tab[idclient]."_".$tab[ref]."'";
        $suftype = 'moteur';

    }  else {

        // Maj de l'annonce
        $majAds = "
                UPDATE annonce_bateau SET
                        `ID_MODELE_BATEAU` = ".$tab[id_modele].",
                        `ID_CATEGORIE_BATEAU` = ".$tab[sscat].",
                        `ID_MARQUE_MOTEUR_BATEAU` =  '".$tab[idmarquemoteur]."',
                        `ID_ENERGIE_BATEAU` =  '".$tab[nrj]."',
                        `ID_PROPULSION_BATEAU` = '".$tab[propulsion]."',
                        `NB_MOTEUR_BATEAU` =  '".$tab[nbmot]."',
                        `PUISSANCE_MOTEUR_BATEAU` =  '".$tab[puissance]."',
                        `INFOS_MOTEUR_BATEAU` =  '".addslashes($tab[infomot])."',
                        `NB_HEURES_MOTEUR` =  '".$tab[nbhrmot]."',
                        `ANNEE_MOTEUR_BATEAU` =  '".$tab[anneemot]."',
                        `IS_MOTEUR_BATEAU` = '".$tab[ismoteur]."',
                        `LONGUEUR_ANNONCE_BATEAU` = '".str_replace(",",".",$tab[longueur])."',
                        `LARGEUR_ANNONCE_BATEAU` = '".str_replace(",",".",$tab[largeur])."',
                        `ANNEE_ANNONCE_BATEAU` = '".$tab[annee]."',
                        `NB_CABINE_BATEAU` = '".$tab[nbcab]."',
                        `NB_COUCHETTE_BATEAU` = '".$tab[nbcouch]."',
                        `NB_SDB_BATEAU` = '".$tab[nbsdb]."',
                        IDS_EQUIP_BATEAU = '".$strlisteq."',
                        IDS_ELECTRO_BATEAU = '".$strlistel."',
                        `TEXTE_ANNONCE_BATEAU` = '".addslashes($tab[txtopt])."',
                        `VISIBLE_ANNONCE_BATEAU` = '".addslashes($tab[ville]).";".$tab[paysvis].";Y',
                        `PRIX_ANNONCE_BATEAU` = ".$tab[prix].",
                         TAXE_ANNONCE_BATEAU = '".$tab[taxe]."',
                        `ID_TYPE_ANNONCE_BATEAU`  = ".$tab[idtypeann]."

                WHERE
                        `REF_ANNONCE_BATEAU` = '".$prefixe.$tab[idclient]."_".$tab[ref]."'";
        $suftype = 'bateau';
    }

    $updAds = mysql_query($majAds) or die ($majAds." : ".mysql_error());


  /* if ($tab[urlphoto][0] != ""){

        if (is_file(PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$tab[urlphoto][0])){

            // ON RECUPERE L'ID ANNONCE BATEAU POUR GESTION PHOTO
                $chopsql = mysql_fetch_array(mysql_query("SELECT ID_ANNONCE_".$suftype." FROM annonce_".$suftype." WHERE PUBLIER_ANNONCE_".$suftype." = 'Y' AND REF_ANNONCE_".$suftype." = '".$prefixe.$tab[idclient]."_".$tab[ref]."'"));

                // ON ECRASE TT LES PHOTOS POUR LES REMETTRE AFIN DE POUVOIR MODIFIER LES PHOTOS SI MODIFICATION BIEN SUR
                $y = 0;

                // EFFACEMEENT DES PHOTOS PRECEDENTES PHYSIQUEMENT SUR LE DISQUE // SINON AIEEEE PAN LE SERVEUR PLUS DE PLACE
                $PrEffacPhoto = "SELECT ID_PHOTO, TEXTE_FICHIER from photo_annonce WHERE ID_ANNONCE = ".$chopsql[0]." AND TYPE_ANNONCE = '".$suftype."'";
                $RES_PrEffacPhoto = mysql_query($PrEffacPhoto);
                while ($VAL_PrEffacPhoto = mysql_fetch_array($RES_PrEffacPhoto)){
                        @unlink(PATH_YB."images/photo/".$tab[idclient]."/tb/".$suftype."_".$VAL_PrEffacPhoto[1]."_".$VAL_PrEffacPhoto[0].".jpg");
                        @unlink(PATH_YB."images/photo/".$tab[idclient]."/normal/".$suftype."_".$VAL_PrEffacPhoto[1]."_".$VAL_PrEffacPhoto[0].".jpg");
                        @unlink(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".$VAL_PrEffacPhoto[1]."_".$VAL_PrEffacPhoto[0].".jpg");
                }

                $delpht = "DELETE from photo_annonce WHERE ID_ANNONCE = ".$chopsql[0]." AND TYPE_ANNONCE = '".$suftype."'";
                mysql_query($delpht);

                nameproduit_fromId($chopsql[0],$tab[type]);

                foreach ($tab[urlphoto] as $value) {

                    if ($value != ""){

                        if (is_file(PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$value)){

                            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
                            $mime = finfo_file($finfo, PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$value);

                            if (($mime == 'image/jpeg')||($mime == 'image/pjpeg')){

                                $sql4 = "INSERT INTO photo_annonce VALUES ('','".$chopsql[0]."','".$suftype."',".($y+1).",'".str_img_uploade($intitule)."')";
                                mysql_query($sql4) or die ("Impossible d'indenter photo_annonce");
                                $lastphoto = mysql_insert_id();

                                if (@copy(PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$value,PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg")){

                                    // ACTION DE REDIMMENSIONNEMENT ET COPIE SELON SI L'IMAGE OU LES IMAGES EXISTE
                                    $img = new img(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                                    $img->resize(320,240);
                                    $img->store(PATH_YB."images/photo/".$tab[idclient]."/normal/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                                    $img->resize(133,100);
                                    $img->store(PATH_YB."images/photo/".$tab[idclient]."/tb/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                                } else {
                                    mysql_query("DELETE FROM photo_annonce WHERE ID_PHOTO = ".$lastphoto." AND TYPE_ANNONCE = '".$suftype."'");
                                }
                                $y++;

                            } else {
                                $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
                                fwrite($pfichier,"IMAGE NON JPG. Image -> ".$value."
");
                                fclose($pfichier);
                                echo 'Image Non JPG => '.$value.' ';
                            }
                        finfo_close($finfo);
                        }
                    }
                }

        } // SI Y A BIEN UN FICHIER
    }*/// SI Y A UNE IMAGE INDIQUE DANS LE FICHIER DECISOFT

}









function insert_automat_DECISOFT($tab,$prefixe,$replog){

    $instantdate = date("Y-m-d G:i:s");
    $ms = date('m');
    $year = date('Y');

    global $cptERROR,$cptOK,$intitule;


    if (($tab[paysvis] == "")||(!$tab[paysvis])) $tab[paysvis] = 100;
    if ($tab[idtypeann] == "") $tab[idtypeann] = 1;
    if ($tab[listeq]) $strlisteq = @implode(";",$tab[listeq]);
    if ($tab[listel]) $strlistel = @implode(";",$tab[listel]);

    if ($tab[ismoteur] == 'N'){
        $tab[idmarquemoteur] = 1;
        $tab[nrj] = 3;
        $tab[propulsion] = 1;
    }

    // MOTEUR
    if ($tab[type] == 4){

        // INSERTION DE L'ANNONCE DANS LA BASE DE DONNEE
        $insAds = "
                INSERT INTO `annonce_moteur` VALUES (
                '',
                ".$tab[idcontrat].",
                ".$tab[sscat].",
                '".$tab[nrj]."',
                '".$tab[idmarquemoteur]."',
                '".addslashes($tab[modelemot])."',
                '".str_link_mod($tab[modelemot])."',
                ".$tab[idtypeann].",
                '".$tab[puissance]."',
                '',
                '".$tab[annee]."',
                '".$tab[nbhrmot]."',
                '',
                '".$prefixe.$tab[idclient]."_".$tab[ref]."',
                '".addslashes($tab[txtopt])."',
                '',
                '".$tab[prix]."',
                '".$tab[taxe]."',
                '',
                '',
                '',
                'Y',
                '".$instantdate."',
                '".$instantdate."',
                '')";

        $suftype = 'moteur';

    } else {

        // INSERTION DE L'ANNONCE DANS LA BASE DE DONNEE
        $insAds = "
                INSERT INTO `annonce_bateau` VALUES (
                '',
                ".$tab[idcontrat].",
                ".$tab[id_modele].",
                ".$tab[sscat].",
                ".$tab[idtypeann].",
                '".$prefixe.$tab[idclient]."_".$tab[ref]."',
                '".$tab[idmarquemoteur]."',
                '".$tab[nrj]."',
                '".$tab[propulsion]."',
                '".$tab[nbmot]."',
                '".$tab[puissance]."',
                '".addslashes($tab[infomot])."',
                '".$tab[anneemot]."',
                '".$tab[nbhrmot]."',
                '".$tab[ismoteur]."',
                '".str_replace(",",".",$tab[longueur])."',
                '".str_replace(",",".",$tab[largeur])."',
                '".$tab[annee]."',
                '".$tab[nbcab]."',
                '".$tab[nbcouch]."',
                '".$tab[nbsdb]."',
                '".$strlisteq."',
                '".$strlistel."',
                '',
                '".addslashes($tab[txtopt])."',
                '',
                '".addslashes($tab[ville]).";".$tab[paysvis].";Y',
                '',
                '0',
                '".$tab[prix]."',
                '".$tab[taxe]."',
                '',
                '0',
                '',
                'Y',
                '".$instantdate."',
                '".$instantdate."',
                '')";
        $suftype = 'bateau';
    }

            if (!mysql_query($insAds)){
                $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
                fwrite($pfichier,"INSERT ADS ECHEC '.$suftype.'. SQL -> ".$insAds."
");
                fclose($pfichier);
                $cptERROR++;
            } else {


                // NOUVELLE ID DE l'ANNONCE
                $lstIdAds = mysql_insert_id();

                // INSERTION DE LA LIGNE D'ANNONCE POUR LES STATS DU CLIENT
                $insStats = "INSERT INTO `stats_client` VALUES (
                                                '',
                                                ".$lstIdAds.",
                                                '".$suftype."',
                                                ".$tab[idclient].",
                                                0,
                                                0,
                                                0,
                                                0
                                        )";
                $resStats = mysql_query($insStats) or die ($insStats." : ".mysql_error());

                // INSERTION DE LA LIGNE D'ANNONCE POUR LES STATS DU CLISTAT
                $insStatsT = "INSERT INTO `stats_total` VALUES (
                                                '',
                                                ".$lstIdAds.",
                                                '".$suftype."',
                                                ".$tab[idclient].",
                                                0,
                                                0,
                                                0,
                                                0,
                                                '".date('Y')."-".date('m')."-01'
                                        )";
                $resStatsT = mysql_query($insStatsT) or die ($insStatsT." : ".mysql_error());

                nameproduit_fromId($lstIdAds,$tab[type]);

                foreach ($tab[urlphoto] as $value) {

                    if ($value != ""){

                        if (is_file(PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$value)){

                            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
                            $mime = finfo_file($finfo, PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$value);

                            if (($mime == 'image/jpeg')||($mime == 'image/pjpeg')){

                                $sql4 = "INSERT INTO photo_annonce VALUES ('','".$lstIdAds."','".$suftype."',".($y+1).",'".str_img_uploade($intitule)."')";
                                mysql_query($sql4) or die ("Impossible d'indenter photo_annonce");
                                $lastphoto = mysql_insert_id();

                                if (copy(PATH_YB_RACINE."decisoft/dezip/".$tab[idclient]."/".$value,PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg")){

                                    // ACTION DE REDIMMENSIONNEMENT ET COPIE SELON SI L'IMAGE OU LES IMAGES EXISTE
                                    $img = new img(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                                    $img->resize(320,240);
                                    $img->store(PATH_YB."images/photo/".$tab[idclient]."/normal/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                                    $img->resize(133,100);
                                    $img->store(PATH_YB."images/photo/".$tab[idclient]."/tb/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                                } else {
                                    mysql_query("DELETE FROM photo_annonce WHERE ID_PHOTO = ".$lastphoto." AND TYPE_ANNONCE = '".$suftype."'");
                                }
                                $y++;

                            } else {
                                    echo $value.'=> Image non JPG ! ';
                                    $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
                                    fwrite($pfichier,"IMAGE NON JPG. image -> ".$value."
");
                                    fclose($pfichier);
                            }
                            finfo_close($finfo);
                        }

                    }
                }
                $cptOK++;
            }


}



















/**************************************************/
/* FONCTIONS GESTION IMAGE
/**************************************************/
function f_exists($photo,$typesource){
    switch ($typesource){
        case "local":
            if (is_file($photo))
                return true;
            else
                return false;
        break;
        case "distant":
            stream_context_set_default(
                array('http' => array('method' => 'HEAD'))
            );
            $tabfsize = @get_headers($photo,1);
            //var_print($tabfsize);
            if ($tabfsize[0] == 'HTTP/1.1 200 OK')
                return true;
            else
                return false;
        break;
    }
}

function taille_photo($photo,$typesource){
    switch ($typesource){
        case "local":
            $fsize = @filesize($photo);
            return $fsize;
        break;
        case "distant":
            stream_context_set_default(
                array('http' => array('method' => 'HEAD'))
            );
            $tabfsize = get_headers($photo,1);
            return $tabfsize['Content-Length'];
        break;
    }
}

function is_a_jpg($photo,$typesource){
    switch ($typesource){
        case "local":
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $mime = finfo_file($finfo, $photo);
            finfo_close($finfo);
            if      (($mime == 'image/jpeg')||($mime == 'image/pjpeg')) return true;
            else                                                        return false;
        break;
        case "distant":
            return true;
            /*stream_context_set_default(
                array('http' => array('method' => 'GET'))
            );
            mt_srand((float) microtime()*1000000);                              // On cree un nom de fichier alÈatoire pour vÈrifier son type!
            $number =  date("Ymd").'_'.mt_rand(1, 10000000);                    // Afiche 1 nombre alÈatoire entre 1 et 100
            //echo "COPIE : ".$photo." > ".PATH_YB_RACINE."fct/tmp/".$number.".jpg\n";

            if (copy($photo,PATH_YB_RACINE."fct/tmp/".$number.".jpg")){        // On le copie temporairement
                $finfo = finfo_open(FILEINFO_MIME_TYPE);                        // return mime type ala mimetype extension
                $mime = finfo_file($finfo, PATH_YB_RACINE.'fct/tmp/'.$number.'.jpg');
                finfo_close($finfo);  
                unlink(PATH_YB_RACINE."fct/tmp/".$number.".jpg"); // Effaacement du fichier temporaire
                //echo "MIME : ".$mime."\n";
                if (($mime == 'image/jpeg')||($mime == 'image/pjpeg'))  return true;
                else                                                    return false;
            } else {
                return false;
            }*/
        break;
    }
}

function img_copy($photo,$typesource,$position,$suftype,$prefixe,$tab,$idannonce,$intitule,$replog)
{
    global $intitule,$tab_traite_posimg,$noupddecisoft,$exit_flood;

    $ms = date('m');
    $year = date('Y');

    if (f_exists($photo,$typesource)) {          // SI LE FICHIER EXISTE
        if (is_a_jpg($photo,$typesource)) {      // SI C UN JPG

            // Recup info photo existante en cours
            $SQL = "SELECT ID_PHOTO, TEXTE_FICHIER from photo_annonce WHERE ID_ANNONCE = ".$idannonce." AND TYPE_ANNONCE = '".$suftype."' AND POSITION_PHOTO = ".$position;
            $RES = mysql_query($SQL);
            if (mysql_num_rows($RES) == 1){
                $VAL = mysql_fetch_row($RES);
                // Si photo scannÈ de l'automat est diffÈrent de la photo actuelle sur le site, on met a jour
                //echo 'T1:'.taille_photo($photo,$typesource).'--- T2:'.taille_photo(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".$VAL[1]."_".$VAL[0].".jpg","local").' /';
                if ((taille_photo($photo,$typesource)) != (taille_photo(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".$VAL[1]."_".$VAL[0].".jpg","local"))){                    
                    // MAJ
                    stream_context_set_default(
                        array('http' => array('method' => 'GET'))
                    );   
                    if (@copy($photo,PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$VAL[0].".jpg")){
                        /// MAJ de BD
                        $UPDphoto = "UPDATE photo_annonce SET TEXTE_FICHIER = '".str_img_uploade($intitule)."' WHERE ID_PHOTO = ".$VAL[0];
                        mysql_query($UPDphoto) or die ($UPDphoto.' '.mysql_error());
                        // MAJ PHYSIQUE

                        $img = new img(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$VAL[0].".jpg");
                        $img->resize(320,240);
                        $img->store(PATH_YB."images/photo/".$tab[idclient]."/normal/".$suftype."_".str_img_uploade($intitule)."_".$VAL[0].".jpg");
                        $img->resize(133,100);
                        $img->store(PATH_YB."images/photo/".$tab[idclient]."/tb/".$suftype."_".str_img_uploade($intitule)."_".$VAL[0].".jpg");

                        echo $position.".";
                    } else {
                        echo "IMPOSSIBLE DE COPIER LA PHOTO EN MAJ\n";
                    }
                } else {
                    echo $position."[I].";
                }
                
                //echo 'prout'.print_r($tab_traite_posimg);
            } else if (mysql_num_rows($RES) == 0){ // NELLE PHOTO
                $INSpht = "INSERT INTO photo_annonce VALUES ('','".$idannonce."','".$suftype."',".$position.",'".str_img_uploade($intitule)."')";
                mysql_query($INSpht) or die ($INSpht." > ".mysql_error()." > Impossible d'indenter photo_annonce");
                $lastphoto = mysql_insert_id();
                stream_context_set_default(
                    array('http' => array('method' => 'GET'))
                );
                if (@copy($photo,PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg")){

                    // ACTION DE REDIMMENSIONNEMENT ET COPIE SELON SI L'IMAGE OU LES IMAGES EXISTE
                    $img = new img(PATH_YB."images/photo/".$tab[idclient]."/origine/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                    $img->resize(320,240);
                    $img->store(PATH_YB."images/photo/".$tab[idclient]."/normal/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");
                    $img->resize(133,100);
                    $img->store(PATH_YB."images/photo/".$tab[idclient]."/tb/".$suftype."_".str_img_uploade($intitule)."_".$lastphoto.".jpg");

                    echo $position.'.';
                } else {
                    echo "IMPOSSIBLE DE COPIER LA PHOTO EN INS\n";
                }
            }
        $tab_traite_posimg[] = $position;
        //print_r($tab_traite_posimg);echo 'prout';
        } else {
            $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
            fwrite($pfichier,"IMAGE NON JPG. Image -> ".$photo."
");
            fclose($pfichier);
            echo "Image Non JPG => ".$photo."\n";
        }
    } else {
        //echo ID_AUTOMAT.'IDAUTOMAT';
        if ((ID_AUTOMAT == 4)&&($position == 1)) $noupddecisoft = 1;
            $pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
            fwrite($pfichier,"IMAGE NON EXISTANTE. Image -> ".$photo."
");
            fclose($pfichier);
         echo "Image Non existante ‡ l'emplacement indiquÈ => ".$photo."\n";
         $exit_flood++;
    }    
}

function monFileGetContentsCurl($url, $montrerContenu, $timeout,$passerelle){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  $resultat = curl_exec($ch);
  $CurlErr = curl_error($ch);
  curl_close($ch);
  
  if ($CurlErr) {
    mail("pierre@rivamedia.fr","[".$passerelle."] Impossible d'acceder au site",$url." => ".$CurlErr."\n\n");
    return false;
  }else if (empty($resultat)) {
    mail("pierre@rivamedia.fr","[".$passerelle."] Aucun resultat trouve a cette URL => ",$url."\n\n");
    return false;       
  }
  elseif ($montrerContenu){
    return $resultat;
  }
}