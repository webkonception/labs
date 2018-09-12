<?php
	function strip_specialchar($url) {
		$url = utf8_decode($url);
		$url = strtr($url, utf8_decode('ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ()[]\'"~$&%*@ç!?;,:/\^¨€{}<>|+.- `³²?°´#×'), 'aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn    --      c  ---    e       ---32     x');
		$url = preg_replace('/([^.a-z0-9]+)/i', '-', $url);
		$url = str_replace(' ', '', $url);
		$url = str_replace('---', '-', $url);
		$url = str_replace('--', '-', $url);
		$url = trim($url, '-');
		return strtolower($url);
	}

	function rmrf($dir)
	{
		foreach(glob($dir) as $file)
		{
			if(is_dir($file))
			{
				rmrf("$file/*");
				rmdir($file);
			}
			else
			{
				unlink($file);
			}
		}
	}
	
	// Copie CURL
	function ftp_curl_get($url, $sortie, $timeout = 5)
	{
		if ($fp = fopen($sortie, 'w')) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_FTP_USE_EPSV, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

			$ret = curl_exec($ch);
			curl_close($ch);
			fclose($fp);

			return $ret;
		}
		return false;
	}

	// Blacklist
	function passerelle_blacklist($id_automat, $type, $libelle)
	{
		$sql = "SELECT id FROM gateway_blacklist WHERE gateway_id=".intval($id_automat)." AND type='".$type."' AND libelle='".mysql_real_escape_string($libelle)."'";
		$req = mysql_query($sql);
		$row = mysql_fetch_row($req);
		if(!empty($row[0]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Matching etat
	function match_etat($etat)
	{
		$etat = trim($etat);
		$etat = strtolower($etat);
		
		switch($etat)
		{
			case 'occasion':
				return 'used';
			break;
			case 'neuf':
				return 'new';
			break;
			default:
				return '';
			break;
		}
	}
	
	// Matching type
	function match_type($type)
	{
		$type = trim($type);
		$type = strtolower($type);
		$type = strip_specialchar($type);
		
		switch($type)
		{
			case 'bateau-a-moteur':case 'bateau-moteur':
				return 1;
			break;
			case 'voilier':
				return 2;
			break;
			case 'pneuma-semi-rigides':
				return 3;
			break;
			case 'moteur-bateau':
				return 4;
			break;
			case 'accessoires-divers':
				return 5;
			break;
			default:
				return 0;
			break;
		}
	}
	
	// Matching categorie
	function match_categorie($categorie)
	{
		$categorie = trim($categorie);
		$categorie = strtolower($categorie);
		
		switch($categorie)
		{
			case 'catamaran moteur':
				return 1;
			break;
			case 'day cruiser':
				return 2;
			break;
			case 'coques open':
				return 3;
			break;
			case 'offshores':
				return 5;
			break;
			case 'pêche promenade':
				return 6;
			break;
			case 'péniches et navigation fluviale':
				return 7;
			break;
			case 'jet-ski / scooter':
				return 8;
			break;
			case 'trawler':
				return 9;
			break;
			case 'vedettes flybridge':
				return 10;
			break;
			case 'vedettes open':
				return 11;
			break;
			case 'yachts (+ 16m)':
				return 12;
			break;
			case 'pêche sportive':
				return 69;
			break;
			case 'vedettes hard-top':
				return 70;
			break;
			case 'bateaux de caractère':
				return 16;
			break;
			case 'bateaux de commerce / erp':
				return 30;
			break;
			case 'dériveurs légers':
				return 13;
			break;
			case 'voiliers fifty':
				return 14;
			break;
			case 'multicoques':
				return 15;
			break;
			case 'voiliers de caractère':
				return 16;
			break;
			case 'voiliers dériveur':
				return 17;
			break;
			case 'voiliers quillard':case 'voiliers biquille':
				return 18;
			break;
			case 'voiliers course':
				return 19;
			break;
			case 'pneumatiques':
				return 35;
			break;
			case 'semi-rigides':
				return 22;
			break;
			case 'moteur bateau in-bord':
				return 23;
			break;
			case 'moteur bateau hors-bord':
				return 24;
			break;
			case 'accastillage':
				return 42;
			break;
			case 'place de port':
				return 45;
			break;
			default:
				return 0;
			break;
		}
	}
	
	// Matching marque
	
	$tmarque = array();
	$sql = "SELECT id, rewrite_url FROM manufacturers";
	$req = mysql_query($sql);
	while($data = mysql_fetch_assoc($req)){ $tmarque[] = $data['rewrite_url'].'|'.$data['id']; }
	array_multisort(array_map('strlen', $tmarque), $tmarque);
	krsort($tmarque);
	
	$tmarquea = array();
	$sql = "SELECT manufacturers_id, rewrite FROM gateway_assoc_manufacturers";
	$req = mysql_query($sql);
	while($data = mysql_fetch_assoc($req)){ $tmarquea[] = $data['rewrite'].'|'.$data['manufacturers_id']; }
	array_multisort(array_map('strlen', $tmarquea), $tmarquea);
	krsort($tmarquea);

	function match_marque($marque)
	{
		global $tmarque, $tmarquea;
		$return = '';
	
		foreach($tmarque as $m)
		{
			$tm = explode('|', $m);
		
			if(strip_specialchar($marque)==$tm[0])
			{
				$return = $tm[1];
			}
			else
			{
				foreach($tmarquea as $ma)
				{
					$tma = explode('|', $ma);
				
					if(strip_specialchar($marque)==$tma[0])
					{
						$return = $tma[1];
					}
				}
			}
		}
		
		return $return;
	}
	
	// Matching modèle
	
	function match_modele($modele, $manufacturers_id)
	{
		global $nommodtemp;
		
		$temptext = '';
		$matchtext = '';
		$returnmodele = '';
		$returnmodeletext = '';
		
		// Exemple : Classe B 180 CDI Design CVT
		$modele = trim($modele);
		
		// On check si le modèle "Classe B 180 CDI Design CVT" est associé
		// Dans notre exemple pas présent
		// On vérifie donc au fur et à mesure si il est présent en virant des mots :
		// Test 1 => Classe B 180 CDI Design
		// Test 2 => Classe B 180 CDI
		// Test 3 => Classe B 180
		// Test 4 => Classe B => Trouvé logiquement
		$tabtext = explode(' ', $modele);
		for($i=count($tabtext);$i>0;$i--)
		{
			if($i==count($tabtext))
			{
				$matchtext = $modele;
				$temptext = $tabtext[$i-1];
			}
			else
			{
				$matchtext = str_replace(' '.$temptext, '', $modele);
				$temptext = $tabtext[$i-1].' '.$temptext;
			}
			
			// Création d'un matchtext 2 : Nombreux cas où le modèle est écrit sans espace (Exemple : McLaren MP4-12 C => McLaren MP4-12C, Jaguar XK 8 => Jaguar XK8)
			$matchtextspace = '';
			$tabm = explode(' ', $matchtext);
			$last = array_pop($tabm);
			$matchtextspace = implode(' ', $tabm).$last;
			
			// On check si le modèle existe dans la table modèle
			$sql = "SELECT id, rewrite_url FROM models WHERE manufacturers_id=".$manufacturers_id." AND (rewrite_url='".strip_specialchar($matchtext)."' OR rewrite_url='".strip_specialchar($matchtextspace)."') ORDER BY id LIMIT 1";
			$req = mysql_query($sql);
			$row = mysql_fetch_row($req);
			if(!empty($row[0]))
			{
				$nommodtemp = str_replace($matchtextspace, '', $nommodtemp);
				$nommodtemp = str_replace($matchtext, '', $nommodtemp);
				$returnmodele = $row[0];
				$returnmodeletext = $row[1];
			}
			else
			{
				// Sinon on check si il existe dans la table synonyme
				$sql = "SELECT models_id FROM gateway_assoc_models WHERE manufacturers_id=".$manufacturers_id." AND (rewrite='".strip_specialchar($matchtext)."' OR rewrite='".strip_specialchar($matchtextspace)."') ORDER BY models_id LIMIT 1";
				$req = mysql_query($sql);
				$row = mysql_fetch_row($req);
				if(!empty($row[0]))
				{
					$nommodtemp = str_replace($matchtextspace, '', $nommodtemp);
					$nommodtemp = str_replace($matchtext, '', $nommodtemp);
					$returnmodele = $row[0];
				}
			}
		}
		
		// Si un modèle a été trouvé, on essaye de le rendre encore plus précis en se bansant sur les autres mots
		// Exemple : CLA 200 CDI Shooting Brake
		// Modèle actuellement trouvé => CLA
		// Modèle précis => CLA Shooting Brake
		if(!empty($returnmodele) && !empty($returnmodeletext))
		{
			foreach($tabtext as $word)
			{
				$sql = "SELECT id FROM models WHERE manufacturers_id=".$manufacturers_id." AND (rewrite_url='".$returnmodeletext.'-'.strip_specialchar($word)."' OR rewrite_url LIKE '".$returnmodeletext.'-'.strip_specialchar($word)."-%')";
				$req = mysql_query($sql);
				$row = mysql_fetch_row($req);
				if(!empty($row[0]))
				{
					$nommodtemp = str_replace(' '.$word.' ', '', $nommodtemp);
					$nommodtemp = str_replace(' '.$word, '', $nommodtemp);
					$nommodtemp = str_replace($word.' ', '', $nommodtemp);
					$returnmodele = $row[0];
				}
			}
		}
		
		return $returnmodele;
	}
?>