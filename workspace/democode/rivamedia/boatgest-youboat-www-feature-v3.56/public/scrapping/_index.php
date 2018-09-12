<?php
//require_once("/home/ftp/rivamedia/www/youboat/automat/fct/findelemv2.php");
require_once("findelemv2.php");
header('Content-Type:text/xml;charset=utf-8');
$xml .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$xml .= "<annonces version=\"1.0\">";



$passerelle = "Philibert";
$taburl = array();
$urlsite = 'http://atelier-nautique-kerollaire.com/';


/*** Bateau Moteur OCCASION ***/
/***************************/
$url            = $urlsite.'ad-category/bateaux-a-moteurs/';
$montrerContenu = true;
$timeout        = 5;

$content = monFileGetContentsCurl($url, $montrerContenu, $timeout,$passerelle);
if (!$content) exit(); // Si Faux ca sort
$search = array("\t", "\n", "\r");
$content = str_replace($search, ' ', $content);
$content = utf8_encode($content);
if(preg_match('@<!-- left block -->(.*)<!-- /content_left -->@Ui', $content, $out))
{
	$tab = get_urls($out[0]);
	foreach($tab as $val)
	{
		if(substr($val, 0, 43) == 'http://atelier-nautique-kerollaire.com/ads/' && !in_array($val.'|bateau_moteur', $taburl)){ array_push($taburl, $val.'|bateau_moteur'); }
	}
}

/*** Semi rigide OCCASION ***/
/***************************/
$url            = $urlsite.'ad-category/semi-rigides/';
$montrerContenu = true;
$timeout        = 5;

$content = monFileGetContentsCurl($url, $montrerContenu, $timeout,$passerelle);
if (!$content) exit(); // Si Faux ca sort
$search = array("\t", "\n", "\r");
$content = str_replace($search, ' ', $content);
$content = utf8_encode($content);
if(preg_match('@<!-- left block -->(.*)<!-- /content_left -->@Ui', $content, $out))
{
	$tab = get_urls($out[0]);
	foreach($tab as $val)
	{
		if(substr($val, 0, 43) == 'http://atelier-nautique-kerollaire.com/ads/' && !in_array($val.'|semi_rigide', $taburl)){ array_push($taburl, $val.'|semi_rigide'); }
	}
}

/*** Voiliers OCCASION ***/
/***************************/
$url            = $urlsite.'ad-category/voiliers/';
$montrerContenu = true;
$timeout        = 5;

$content = monFileGetContentsCurl($url, $montrerContenu, $timeout,$passerelle);
if (!$content) exit(); // Si Faux ca sort
$search = array("\t", "\n", "\r");
$content = str_replace($search, ' ', $content);
$content = utf8_encode($content);
if(preg_match('@<!-- left block -->(.*)<!-- /content_left -->@Ui', $content, $out))
{
	$tab = get_urls($out[0]);
	foreach($tab as $val)
	{
		if(substr($val, 0, 43) == 'http://atelier-nautique-kerollaire.com/ads/' && !in_array($val.'|voilier', $taburl)){ array_push($taburl, $val.'|voilier'); }
	}
}

//var_dump($taburl);
$i=0;

if (!empty($taburl[0])) {

	// Récupération des données de chaque annonce
	foreach($taburl as $val)
	{
		$i++;

		$tval = explode('|', $val);
		$url = $tval[0];

		$xml .= '<annonce>'."\n";
		$xml .= '<categorie><![CDATA['.$tval[1].']]></categorie>'."\n";

		$descr = monFileGetContentsCurl($url, $montrerContenu, $timeout,$passerelle);
		if (!$descr) exit();
		$search = array("\t", "\n", "\r");
		$descr = str_replace($search, ' ', $descr);
		//$descr = utf8_encode($descr);

		if(preg_match('@identification de l\'annonce :</strong>(.*)</div>@Ui', $descr, $out)){ $xml .= '<id><![CDATA['.trim(str_replace(' ', '', str_replace('identification de l\'annonce :', '', strip_tags($out[0])))).']]></id>'."\n"; }
		if(preg_match('@<p class="post-price">(.*)</p>@Ui', $descr, $out)){ $xml .= '<prix><![CDATA['.trim(str_replace('€', '', str_replace(',', '', strip_tags($out[0])))).']]></prix>'."\n"; }
		if(preg_match('@Constructeur:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<marque><![CDATA['.trim(str_replace('Constructeur:', '', strip_tags($out[0]))).']]></marque>'."\n"; }
		if(preg_match('@Modèle:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<modele><![CDATA['.trim(str_replace('Modèle:', '', strip_tags($out[0]))).']]></modele>'."\n"; }
		if(preg_match('@Moteur:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<moteur><![CDATA['.trim(str_replace('Moteur:', '', strip_tags($out[0]))).']]></moteur>'."\n"; }
		if(preg_match('@Equipement extérieur:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<equipement_exterieur><![CDATA['.trim(str_replace('Equipement extérieur:', '', strip_tags($out[0]))).']]></equipement_exterieur>'."\n"; }
		if(preg_match('@Equipement intérieur:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<equipement_interieur><![CDATA['.trim(str_replace('Equipement intérieur:', '', strip_tags($out[0]))).']]></equipement_interieur>'."\n"; }
		if(preg_match('@Equipement électronique:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<equipement_electronique><![CDATA['.trim(str_replace('Equipement électronique:', '', strip_tags($out[0]))).']]></equipement_electronique>'."\n"; }
		if(preg_match('@Divers:</span>(.*)</li>@Ui', $descr, $out)){ $xml .= '<divers><![CDATA['.trim(str_replace('Divers:', '', strip_tags($out[0]))).']]></divers>'."\n"; }
		if(preg_match('@Description</h3>(.*)<div class="note">@Ui', $descr, $out)){ $xml .= '<description><![CDATA['.trim(str_replace('Description', '', strip_tags(str_replace('</p>', "\n\n", str_replace('<br />', "\n", $out[0]))))).']]></description>'."\n"; }

		$xml .= '<photos>';

		if(preg_match('@<div id="main-pic">(.*)</div>@Ui', $descr, $out))
		{
			$imgurl = get_urls($out[0]);
			if(!empty($imgurl[0])){ $xml .= '<photo>'.$imgurl[0].'</photo>'; }
		}

		if(preg_match('@<div id="thumbs-pic">(.*)</div>@Ui', $descr, $out))
		{
			$imgurl = get_urls($out[0]);
			if(!empty($imgurl[0]))
			{
				foreach($imgurl as $img)
				{
					$xml .= '<photo>'.$img.'</photo>';
				}
			}
		}

		$xml .= '</photos>';

		$xml .= '</annonce>'."\n";
	}
        $xml .= '</annonces>'."\n";
	echo $xml;
}
