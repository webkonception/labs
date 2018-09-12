<?php
	error_reporting(~E_NOTICE & ~E_DEPRECATED);
	ini_set('memory_limit', '256M');
	set_time_limit(3600);
	define('RUN_TYPE', 'test');
	$date = date('Ymd');
	$key = sha1('ybfr'.$date);
	
	switch(RUN_TYPE)
	{
		case "test":
			define('BDD_USER', 'de96033');
			define('BDD_PWD', 'Gahshe4ohTea');
			define('BDD_SERVER', 'localhost');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', '/htdocs/youboat-www/youboat-www_boatgest/public/gateway/import/');
			define('PATH_IMG', '/htdocs/youboat-www/youboat-www_website/public/assets/photos/uk/');
			define('ID_AUTOMAT', 1);
		break;
		case "release":
			define('BDD_USER', 'de96033');
			define('BDD_PWD', 'Gahshe4ohTea');
			define('BDD_SERVER', 'localhost');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', '/htdocs/youboat-www/youboat-www_boatgest/public/gateway/import/');
			define('PATH_IMG', '/htdocs/youboat-www/youboat-www_website/public/assets/photos/uk/');
			define('ID_AUTOMAT', 1);
		break;
	}
	
	$db = mysql_connect(BDD_SERVER, BDD_USER, BDD_PWD);
	mysql_select_db(BDD_NAME, $db);
	mysql_query("SET NAMES 'utf8'");

	require(PATH_AUTOMAT.'gateway-functions.php');

	$rep = PATH_AUTOMAT."youboat-fr/";
	$replog = $rep."logs/";
	$prefixe = "YBFR_";
	
	$cptOK = 0;
	$cptERROR = 0;
	$cptUPDATE = 0;
	$cptDELETE = 0;
	
	$date = date('Ymd');
	$key = sha1('ybfr'.$date);
	$datemaj=date("Y-m-d H:i:s");
	$ms = substr($datemaj,5,2);
	$year = substr($datemaj,0,4);
	
	// Logs
	$sql = "INSERT INTO gateway_logs VALUES(NULL,".ID_AUTOMAT.",NOW(),'',0,0,0,0)";
	mysql_query($sql);
	$id_log = mysql_insert_id();
	$pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
	fwrite($pfichier,"--------------------------  ".$datemaj."  --------------------------\n");
	
	// Parcours fichier client
	$xml = simplexml_load_file('https://www.rivamedia.fr/runs/List_Dealers_YBFR.php?key='.$key);
	$tab_client = $xml->dealer;
	foreach($tab_client as $client)
	{
		// Récupération données client
		$xmlad = simplexml_load_file('https://www.rivamedia.fr/runs/XML_YB_FR.php?id='.($client->id).'&key='.$key);
		
		// MAJ ou Insert client
		$sql = "SELECT dealerscaract_id FROM gateway_assoc_dealers WHERE gatewaydealers_id=".$client->id;
		$query = mysql_query($sql);
		$row = mysql_fetch_row($query);
		$id_client = $row[0];
		$id_user = '';
		
		// Insert
		if(empty($id_client))
		{
			if($client->active_contract==1)
			{			
				$sql = "INSERT INTO users(
										role_id,
										type,
										active,
										username,
										email,
										password,
										notified,
										created_at,
										updated_at
									) VALUES(
										4,
										'dealer',
										'active',
										'".strip_specialchar($xmlad->client->coordonnees->nom_client).$client->id."',
										'".($xmlad->client->coordonnees->email_client)."',
										'".md5($xmlad->client->coordonnees->nom_client).$client->id."',
										0,
										'".$datemaj."',
										'".$datemaj."'
									)";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
				
				$sql = "SELECT id FROM users WHERE username='".strip_specialchar($xmlad->client->coordonnees->nom_client).$client->id."' ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_user = $row[0];
			
				$sql = "INSERT INTO dealerscaracts(
												user_id,
												denomination,
												address,
												zip,
												city,
												phone_1,
												phone_2,
												fax,
												emails,
												website_url,
												created_at,
												updated_at
											) VALUES(
												".$id_user.",
												'".($xmlad->client->coordonnees->nom_client)."',
												'".($xmlad->client->coordonnees->addr_client)."',
												'".($xmlad->client->coordonnees->cp_client)."',
												'".($xmlad->client->coordonnees->ville_client)."',
												'".($xmlad->client->coordonnees->tel1_client)."',
												'".($xmlad->client->coordonnees->tel2_client)."',
												'".($xmlad->client->coordonnees->fax_client)."',
												'".($xmlad->client->coordonnees->email_client)."',
												'".($xmlad->client->coordonnees->web_client)."',
												'".$datemaj."',
												'".$datemaj."'
											)";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
				
				$sql = "SELECT id FROM dealerscaracts WHERE emails='".($xmlad->client->coordonnees->email_client)."' ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_client = $row[0];
				
				$sql = "INSERT INTO countrycontracts(
											dealerscaracts_id,
											commercialscaracts_id,
											countries_ids,
											start_date,
											end_date,
											status,
											created_at,
											updated_at
										) VALUES(
											".$id_client.",
											1,
											'75;77',
											'".date("Y-m-d")."',
											'".date("Y-m-d", strtotime("+1 year"))."',
											'active',
											'".$datemaj."',
											'".$datemaj."
										)";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
				
				echo $log = "CLIENT ".($xmlad->client->coordonnees->nom_client)." [".$client->id."]\n";
				fwrite($pfichier, $log);
				echo $log = "Client inexistant : création en BDD => n°".$id_client."\n";
				fwrite($pfichier, $log);
			}
		}
		// MAJ
		else
		{
			// Contrat inactif
			if($client->active_contract==0)
			{			
				$sql = "UPDATE countrycontracts SET status='inactive' WHERE dealerscaracts_id=".$id_client;
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
			}
			// Contrat actif
			else
			{
				$sql = "UPDATE dealerscaracts SET
											denomination='".($xmlad->client->coordonnees->nom_client)."',
											address='".($xmlad->client->coordonnees->addr_client)."',
											zip='".($xmlad->client->coordonnees->cp_client)."',
											city='".($xmlad->client->coordonnees->ville_client)."',
											phone_1='".($xmlad->client->coordonnees->tel1_client)."',
											phone_2='".($xmlad->client->coordonnees->tel2_client)."',
											fax='".($xmlad->client->coordonnees->fax_client)."',
											emails='".($xmlad->client->coordonnees->email_client)."',
											website_url='".($xmlad->client->coordonnees->web_client)."',
											created_at='".$datemaj."',
											updated_at='".$datemaj."'
										WHERE id=".$id_client;
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
				
				$sql = "SELECT user_id FROM dealerscaracts WHERE id=".$id_client;
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_user = $row[0];
				
				echo $log = "CLIENT ".($xmlad->client->coordonnees->nom_client)." [".$client->id."] => n°".$id_client."\n";
				fwrite($pfichier, $log);
			}
		}
	
		// Stockage références annonce
		$ref_aa = array();
		$sel_ref = "SELECT ad_ref FROM gateway_ads_details WHERE status='active' AND ad_ref LIKE '".$prefixe."_".$id_client."_%'";
		$res_ref = mysql_query($sel_ref) or die ($sel_ref." : ".mysql_error());
		while ($val_ref = mysql_fetch_array($res_ref)){	$ref_aa[] = $val_ref[0]; }
		mysql_free_result($res_ref);
		$nbannoncetrouve = count($ref_aa);
		$log = "EN LIGNE : ".$nbannoncetrouve."\n";
		fwrite($pfichier, $log);
	
		if($client->active_contract==1 && !empty($id_client))
		{
			foreach($xmlad->client->annonces->annonce as $annonce)
			{
				$reference = $prefixe."_".$id_client."_".$annonce['ref'];
				
				$type = match_type($annonce->info_annonce->type);
				$etat = match_etat($annonce->info_annonce->etat);
				$prix = $annonce->info_annonce->prix_public;
				$prix_descr = $annonce->info_annonce->prix_public['tax'];
				$descriptif = $annonce->info_annonce->descriptifs->descriptif;
				$marque = match_marque($annonce->info_annonce->marque);
				$tmp_marque = $annonce->info_annonce->marque
				$modele = '';
				if(!empty($marque)){ $modele = match_modele($annonce->info_annonce->modele, $marque); }
				$tmp_modele = $annonce->info_annonce->modele;
				$visibilite = $annonce->info_annonce->visibilite;
				$categorie = match_categorie($annonce->info_bateau->construction->item[1]);
				// Dimensions
				$largeur = $annonce->info_bateau->dimensions->item[0];
				$longueur = $annonce->info_bateau->dimensions->item[1];
				$annee = $annonce->info_bateau->construction->item[0];
				// Moteur
				$nb_moteur = $annonce->info_bateau->moteur->item[0];
				$fuel = $annonce->info_bateau->moteur->item[1];
				$heures = $annonce->info_bateau->moteur->item[2];
				$puissance = $annonce->info_bateau->moteur->item[3];
				$marque_moteur = $annonce->info_bateau->moteur->item[4];
				$propulsion = $annonce->info_bateau->moteur->item[5];
				$info = $annonce->info_bateau->moteur->item[6];
				// Photos
				$tabphoto = $annonce->image->media;
				
				if((empty($id_marque) && !empty($tmp_marque) && !passerelle_blacklist(ID_AUTOMAT, 'marque', $tmp_marque)) ||
					(empty($id_modele) && !empty($tmp_modele) && !passerelle_blacklist(ID_AUTOMAT, 'modele', $tmp_modele))
				)
				{
					$sql = "INSERT INTO gateway_error(gateway_id, manufacturers, models) VALUES(".ID_AUTOMAT.", '".$tmp_marque."', '".$tmp_modele."')";
					if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
					
					$pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
					fwrite($pfichier,"INSERT ADS ECHEC. MARQUE ET/OU MODELE INCONNU => ".$reference."\n");
					fclose($pfichier);
					$cptERROR++;
					$cptERRORt++;
				}
				else
				{
					if(empty($type) || empty($prix) || empty($etat))
					{
						$pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
						fwrite($pfichier,"INSERT ADS ECHEC. TYPE, PRIX OU ETAT INCONNU => ".$reference."\n");
						fclose($pfichier);
						$cptERROR++;
						$cptERRORt++;
					}
					else
					{
						echo "Traitement annonce réf ".$reference."\n";
						if(!in_array($reference, $ref_aa))
						{
							// INSERT
							$sql = "INSERT INTO gateway_ads_details(
											ad_country_code,
											ad_referrer,
											ad_ref,
											ad_mooring_country,
											ad_year_built,
											ad_width_meter,
											ad_length_meter,
											ad_description,
											ad_propulsion,
											ad_nb_engines,
											dealerscaracts_id,
											user_id,
											adstypes_id,
											categories_ids,
											manufacturers_id,
											models_id,
											manufacturersengines_id,
											modelsengines_id,
											countries_id,
											sell_type,
											status,
											created_at,
											updated_at
									) VALUES(
										'fr',
										'YBFR',
										'".$reference."',
										'".$visibilite."',
										'".$annee."',
										'".$largeur."',
										'".$longueur."',
										'".$descriptif."',
										'".$propulsion."',
										'".$nb_moteur."',
										".$id_client.",
										".$id_user.",
										".$type.",
										".$categorie.",
										".$marque.",
										".$modele.",
										'',
										'',
										75,
										'".$etat."',
										'active',
										'".$datemaj."',
										'".$datemaj."'
									)";
							if(RUN_TYPE!='test')
							{
								if(mysql_query($sql))
								{
									$sql = "SELECT id FROM gateway_ads_details WHERE reference='".mysql_real_escape_string($reference)."' AND publier='".$publier."' AND id_contrat=".$idcontrat;
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$id_annonce = $row[0];
									
									echo "Annonce ".$id_annonce." insérée\n";

									// Photos
									$i=0;
									foreach($tabphoto as $photo)
									{
										if(!is_dir()){ mkdir('/htdocs/youboat-www/youboat-www_boatgest/public/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($tmp_marque.'-'.$tmp_modele), 0777); }
										copy($photo, '/htdocs/youboat-www/youboat-www_boatgest/public/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($tmp_marque.'-'.$tmp_modele).'/photo-'.$i.'.jpg');
										$i++;
									}
								
									$cptOK++;
								}
								else
								{
									echo $sql."\n";
									echo "Problème insert annonce ".$reference."\n";
									$cptERROR++;
									$cptERRORt++;
								}
							}
							else
							{
								echo $sql."\n";
							}
						}
						else
						{
							// UPDATE
							$sql = "UPDATE annonce SET
											ad_mooring_country='".$visibilite."',
											ad_year_built='".$annee."',
											ad_width_meter='".$largeur."',
											ad_length_meter='".$longueur."',
											ad_description='".$descriptif."',
											ad_propulsion='".$propulsion."',
											ad_nb_engines='".$nb_moteur."',
											dealerscaracts_id=".$id_client.",
											user_id=".$id_user.",
											adstypes_id=".$type.",
											categories_ids=".$categorie.",
											manufacturers_id=".$marque.",
											models_id=".$modele.",
											manufacturersengines_id='',
											modelsengines_id='',
											sell_type='".$etat."',
											updated_at='".$datemaj."'
										WHERE ad_ref='".mysql_real_escape_string($reference)."'"; 
							if(RUN_TYPE!='test')
							{
								if(mysql_query($sql))
								{
									$sql = "SELECT id FROM gateway_ads_details WHERE ad_ref='".$reference."'";
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$id_annonce = $row[0];
									
									echo "Annonce ".$id_annonce." mise à jour\n";
									
									// Photos
									rmrf('/htdocs/youboat-www/youboat-www_boatgest/public/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($tmp_marque.'-'.$tmp_modele));
									if(!is_dir()){ mkdir('/htdocs/youboat-www/youboat-www_boatgest/public/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($tmp_marque.'-'.$tmp_modele), 0777); }
									$i=0;
									foreach($tabphoto as $photo)
									{
										copy($photo, '/htdocs/youboat-www/youboat-www_boatgest/public/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($tmp_marque.'-'.$tmp_modele).'/photo-'.$i.'.jpg');
										$i++;
									}
								
									$cptUPDATE++;
									unset($ref_aa[array_search($reference, $ref_aa)]);
								}
								else
								{
									echo $sql."\n";
									echo "Problème MAJ annonce ".$reference."\n";
									$cptERROR++;
								}
							}
							else
							{
								echo $sql."\n";
							}
						}
					}
				}
				
				// DELETE
				foreach($ref_aa as $value)
				{
					$del = "UPDATE gateway_ads_details SET status='removed', deleted_at='".date("Y-m-d H:i:s")."' WHERE ad_ref='".mysql_real_escape_string($value)."'";
					if(RUN_TYPE!='test'){ mysql_query($del); }else{ echo $sql."\n"; }
					$cptDELETE++;
				}
			}
		}
	}
	
	// RAPPORT
	$pfichier = fopen($replog."log_".$ms."-".$year.".txt","a+");
	fwrite($pfichier,$cptOK." annonces insérées, ".$cptUPDATE." annonces mises à jour, ".$cptERROR." annonces refusées, ".$cptDELETE." annonces effacées,\n");
	fclose($pfichier);
	
	mysql_query("UPDATE gateway_logs SET DATE_FIN=NOW(), ANN_OK='".$cptOK."', ANN_KO='".$cptERROR."', ANN_MAJ='".$cptUPDATE."', ANN_EFF='".$cptDELETE."' WHERE ID='".$id_log."'");
	
	echo $cptOK." annonces insérées, ".$cptUPDATE." annonces mises à jour, ".$cptERROR." annonces refusées, ".$cptDELETE." annonces effacées<br>";

	mysql_close();
?>