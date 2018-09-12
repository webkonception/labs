<?php
	error_reporting(~E_NOTICE & ~E_DEPRECATED);
	ini_set('memory_limit', '256M');
	set_time_limit(3600);
	define('RUN_TYPE', 'release');
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
		case "debug":
			define('BDD_USER', 'root');
			define('BDD_PWD', '');
			define('BDD_SERVER', 'localhost');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', 'C:/wamp/www/import_client_yb/import-ybfr/');
			define('PATH_IMG', 'C:/wamp/www/import_client_yb/import-ybfr/youboat-fr/photos/');
			define('ID_AUTOMAT', 1);
		break;
		case "release":
			define('BDD_USER', 'de96033');
			define('BDD_PWD', 'Gahshe4ohTea');
			define('BDD_SERVER', 'localhost');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', '/home/de96033/htdocs/youboat-www/youboat-www_boatgest/public/gateway/import/');
			define('PATH_IMG', '/home/de96033/htdocs/youboat-www/youboat-www_website/public/assets/photos/uk/');
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
	$sql2 = "SELECT id_client_bateau, status FROM rivamedia.ybworld_passerelle_fr";
	$req2 = mysql_query($sql2);
	while($data2 = mysql_fetch_assoc($req2))
	{
		$idclient = $data2['id_client_bateau'];
		$statusclient = $data2['status'];

		// Récupération données client
		$xmlad = simplexml_load_file('https://www.rivamedia.fr/runs/XML_YB_FR.php?id='.($idclient).'&key='.$key);

		// MAJ ou Insert client
		$sql = "SELECT gateway_assoc_dealers.dealerscaract_id, dealerscaracts.user_id, countrycontracts.id FROM gateway_assoc_dealers
				LEFT JOIN dealerscaracts ON dealerscaracts.id=gateway_assoc_dealers.dealerscaract_id
				LEFT JOIN countrycontracts ON countrycontracts.dealerscaracts_id=gateway_assoc_dealers.dealerscaract_id
				WHERE gateway_assoc_dealers.gatewaydealers_id=".intval($idclient);
		$query = mysql_query($sql);
		$row = mysql_fetch_row($query);
		$id_client = $row[0];
		$id_user = $row[1];
		$id_contrat = $row[2];

		// Insert
		if(empty($id_client))
		{
			if($statusclient==1)
			{
				$sql = "INSERT INTO users(
										role_id,
										type,
										status,
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
										'".mysql_real_escape_string(strip_specialchar($xmlad->client->coordonnees->nom_client).$idclient)."',
										'".mysql_real_escape_string($xmlad->client->coordonnees->email_client)."',
										'".mysql_real_escape_string(md5($xmlad->client->coordonnees->nom_client.$idclient))."',
										0,
										'".$datemaj."',
										'".$datemaj."'
									)";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }

				$sql = "SELECT id FROM users WHERE username='".strip_specialchar($xmlad->client->coordonnees->nom_client).$idclient."' ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_user = $row[0];

				$sql = "INSERT INTO dealerscaracts(
												user_id,
												denomination,
												address,
												zip,
												city,
												country_id,
												phone_1,
												phone_2,
												fax,
												emails,
												website_url,
												rewrite_url,
												created_at,
												updated_at
											) VALUES(
												".intval($id_user).",
												'".mysql_real_escape_string($xmlad->client->coordonnees->nom_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->addr_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->cp_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->ville_client)."',
												75,
												'".mysql_real_escape_string($xmlad->client->coordonnees->tel1_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->tel2_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->fax_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->email_client)."',
												'".mysql_real_escape_string($xmlad->client->coordonnees->web_client)."',
												'".strip_specialchar(mysql_real_escape_string($xmlad->client->coordonnees->nom_client))."',
												'".$datemaj."',
												'".$datemaj."'
											)";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }

				$sql = "SELECT id FROM dealerscaracts WHERE emails='".($xmlad->client->coordonnees->email_client)."' ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_client = $row[0];

				$sql = "INSERT INTO gateway_assoc_dealers(gatewaydealers_id, dealerscaract_id) VALUES(".intval($idclient).", ".intval($id_client).")";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }

				$sql = "INSERT INTO countrycontracts(
											dealerscaracts_id,
											user_id,
											commercialscaracts_id,
											countries_ids,
											start_date,
											end_date,
											status,
											created_at,
											updated_at
										) VALUES(
											".intval($id_client).",
											".intval($id_user).",
											1,
											'75;77',
											'".date("Y-m-d")."',
											'".date("Y-m-d", strtotime("+1 year"))."',
											'active',
											'".$datemaj."',
											'".$datemaj."'
										)";
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }

				$sql = "SELECT id FROM countrycontracts WHERE dealerscaracts_id=".intval($id_client)." ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_contrat = $row[0];

				echo $log = ">>>>> CLIENT ".($xmlad->client->coordonnees->nom_client)." [ID FR ".$idclient."]\n";
				fwrite($pfichier, $log);
				echo $log = "Client inexistant : création en BDD => [ID UK ".$id_client."]\n";
				fwrite($pfichier, $log);
			}
		}
		// MAJ
		else
		{
			// Contrat inactif
			if($statusclient==0)
			{
				$sql = "UPDATE countrycontracts SET status='inactive' WHERE dealerscaracts_id=".intval($id_client);
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
			}
			// Contrat actif
			else
			{
				$sql = "UPDATE dealerscaracts SET
											denomination='".mysql_real_escape_string($xmlad->client->coordonnees->nom_client)."',
											address='".mysql_real_escape_string($xmlad->client->coordonnees->addr_client)."',
											zip='".mysql_real_escape_string($xmlad->client->coordonnees->cp_client)."',
											city='".mysql_real_escape_string($xmlad->client->coordonnees->ville_client)."',
											country_id=75,
											phone_1='".mysql_real_escape_string($xmlad->client->coordonnees->tel1_client)."',
											phone_2='".mysql_real_escape_string($xmlad->client->coordonnees->tel2_client)."',
											fax='".mysql_real_escape_string($xmlad->client->coordonnees->fax_client)."',
											emails='".mysql_real_escape_string($xmlad->client->coordonnees->email_client)."',
											website_url='".mysql_real_escape_string($xmlad->client->coordonnees->web_client)."',
											rewrite_url='".strip_specialchar(mysql_real_escape_string($xmlad->client->coordonnees->nom_client))."',
											created_at='".$datemaj."',
											updated_at='".$datemaj."'
										WHERE id=".intval($id_client);
				if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }

				$sql = "SELECT id FROM countrycontracts WHERE dealerscaracts_id=".intval($id_client)." ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_contrat = $row[0];

				if(empty($id_contrat))
				{
					$sql = "INSERT INTO countrycontracts(
							dealerscaracts_id,
							user_id,
							commercialscaracts_id,
							countries_ids,
							start_date,
							end_date,
							status,
							created_at,
							updated_at
						) VALUES(
							".intval($id_client).",
							".intval($id_user).",
							1,
							'75;77',
							'".date("Y-m-d")."',
							'".date("Y-m-d", strtotime("+1 year"))."',
							'active',
							'".$datemaj."',
							'".$datemaj."'
						)";
					if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
				}

				$sql = "SELECT id FROM countrycontracts WHERE dealerscaracts_id=".intval($id_client)." ORDER BY id DESC LIMIT 1";
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_contrat = $row[0];

				$sql = "SELECT user_id FROM dealerscaracts WHERE id=".intval($id_client);
				$query = mysql_query($sql);
				$row = mysql_fetch_row($query);
				$id_user = $row[0];

				echo $log = ">>>>> CLIENT ".($xmlad->client->coordonnees->nom_client)." [ID FR ".$idclient."] [ID UK ".$id_client."]\n";
				fwrite($pfichier, $log);
			}
		}

		// Stockage références annonce
		$ref_aa = array();
		$sel_ref = "SELECT ad_ref FROM gateway_ads_details WHERE status='active' AND ad_ref LIKE '".$prefixe.$id_client."_%'";
		$res_ref = mysql_query($sel_ref) or die ($sel_ref." : ".mysql_error());
		while ($val_ref = mysql_fetch_array($res_ref)){	$ref_aa[] = $val_ref[0]; }
		mysql_free_result($res_ref);
		$nbannoncetrouve = count($ref_aa);
		echo $log = "Annonces en ligne : ".$nbannoncetrouve."\n";
		fwrite($pfichier, $log);

		if($statusclient==1 && !empty($id_client))
		{
			foreach($xmlad->client->annonces->annonce as $annonce)
			{
				$reference = '';
				$type = 0;
				$etat = '';
				$prix = 0;
				$prix_descr = '';
				$descriptif = '';
				$marque = 0;
				$tmp_marque = '';
				$modele = 0;
				$tmp_modele = '';
				$visibilite = '';
				$categorie = 0;
				$tmp_type = '';
				$largeur = '';
				$longueur = '';
				$annee = '';
				$nb_moteur = '';
				$fuel = '';
				$heures = '';
				$puissance = '';
				$marque_moteur = '';
				$propulsion = '';
				$info = '';

				$reference = $prefixe.$id_client."_".$annonce['ref'];
				$type = match_type($annonce->info_annonce->type);
				$tmp_type = $annonce->info_annonce->type;
				$etat = match_etat($annonce->info_annonce->etat);
				$prix = $annonce->info_annonce->prix_public;
				$prix_descr = $annonce->info_annonce->prix_public['tax'];
				$descriptif = $annonce->info_annonce->descriptifs->descriptif;
				$marque = match_marque($annonce->info_annonce->marque);
				$tmp_marque = $annonce->info_annonce->marque;
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

				if((empty($marque) && !empty($tmp_marque) && !passerelle_blacklist(ID_AUTOMAT, 'manufacturers', $tmp_marque)) ||
					(empty($modele) && !empty($tmp_modele) && !passerelle_blacklist(ID_AUTOMAT, 'models', $tmp_modele))
				)
				{
					$sql = "SELECT id FROM gateway_error WHERE gateway_id=".intval($id_automat)." AND manufacturers='".mysql_real_escape_string($tmp_marque)."' AND models='".mysql_real_escape_string($tmp_modele)."'";
					$req = mysql_query($sql);
					$row = mysql_fetch_row($req);
					if(empty($row[0]))
					{
						$sql = "INSERT INTO gateway_error(gateway_id, manufacturers, models) VALUES(".ID_AUTOMAT.", '".mysql_real_escape_string($tmp_marque)."', '".mysql_real_escape_string($tmp_modele)."')";
						if(RUN_TYPE!='test'){ mysql_query($sql); }else{ echo $sql."\n"; }
					}

					fwrite($pfichier,"INSERT ADS ECHEC. MARQUE ET/OU MODELE (".$tmp_marque." ".$tmp_modele.") => ".$reference."\n");
					$cptERROR++;
					$cptERRORt++;
				}
				else
				{
					if(empty($type) || $type==0)
					{
						fwrite($pfichier,"INSERT ADS ECHEC. TYPE (".$tmp_type.") => ".$reference."\n");
						$cptERROR++;
						$cptERRORt++;
					}
					else if(empty($prix) || $prix==0)
					{
						fwrite($pfichier,"INSERT ADS ECHEC. PRIX => ".$reference."\n");
						$cptERROR++;
						$cptERRORt++;
					}
					else if(empty($etat))
					{
						fwrite($pfichier,"INSERT ADS ECHEC. ETAT => ".$reference."\n");
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
											ad_price,
											dealerscaracts_id,
											countrycontracts_id,
											user_id,
											adstypes_id,
											categories_ids,
											manufacturers_id,
											models_id,
											countries_id,
											sell_type,
											status,
											created_at,
											updated_at
									) VALUES(
										'uk',
										'YBFR',
										'".mysql_real_escape_string($reference)."',
										'".mysql_real_escape_string($visibilite)."',
										'".mysql_real_escape_string($annee)."',
										'".mysql_real_escape_string($largeur)."',
										'".mysql_real_escape_string($longueur)."',
										'".mysql_real_escape_string($descriptif)."',
										'".mysql_real_escape_string($propulsion)."',
										'".mysql_real_escape_string($nb_moteur)."',
										".$prix.",
										".intval($id_client).",
										".intval($id_user).",
										".intval($id_contrat).",
										".intval($type).",
										".intval($categorie).",
										".intval($marque).",
										".intval($modele).",
										75,
										'".mysql_real_escape_string($etat)."',
										'active',
										'".$datemaj."',
										'".$datemaj."'
									)";
							if(RUN_TYPE!='test')
							{
								if(mysql_query($sql))
								{
									$sql = "SELECT id FROM gateway_ads_details WHERE ad_ref='".mysql_real_escape_string($reference)."' AND status='active'";
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$id_annonce = $row[0];

									$sql = "SELECT rewrite_url FROM manufacturers WHERE id=".intval($marque);
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$marque_photo = $row[0];

									$sql = "SELECT rewrite_url FROM models WHERE id=".intval($modele);
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$modele_photo = $row[0];

									echo "Annonce ".$id_annonce." insérée\n";

									// Photos
									$i=0;
									$ad_photo = '';
									$photosql = '';
									foreach($tabphoto as $photo)
									{
										if(!is_dir(PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo))){ mkdir(PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo), 0777); }
										@copy($photo, PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo).'/photo-'.$i.'.jpg');
										if($i==0) {
											$ad_photo = '/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo).'/photo-'.$i.'.jpg;';
										}
										$photosql .= '/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo).'/photo-'.$i.'.jpg;';
										$i++;
									}
									//$sql = "UPDATE gateway_ads_details SET ad_photos='".$photosql."' WHERE id=".$id_annonce;
									$sql = "UPDATE gateway_ads_details SET ad_photo='".$ad_photo."', ad_photos='".$photosql."' WHERE id=".$id_annonce;
									mysql_query($sql);

									$cptOK++;
								}
								else
								{
									echo $sql."\n";
									echo "Problème insert annonce ".$reference."\n";
									fwrite($pfichier,"Problème insert annonce ".$reference."\n".$sql."\n");
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
							$sql = "UPDATE gateway_ads_details SET
											ad_country_code='uk',
											ad_mooring_country='".mysql_real_escape_string($visibilite)."',
											ad_year_built='".mysql_real_escape_string($annee)."',
											ad_width_meter='".mysql_real_escape_string($largeur)."',
											ad_length_meter='".mysql_real_escape_string($longueur)."',
											ad_description='".mysql_real_escape_string($descriptif)."',
											ad_propulsion='".mysql_real_escape_string($propulsion)."',
											ad_nb_engines='".mysql_real_escape_string($nb_moteur)."',
											ad_price=".$prix.",
											dealerscaracts_id=".intval($id_client).",
											user_id=".intval($id_user).",
											countrycontracts_id=".intval($id_contrat).",
											adstypes_id=".intval($type).",
											categories_ids=".intval($categorie).",
											manufacturers_id=".intval($marque).",
											models_id=".intval($modele).",
											sell_type='".mysql_real_escape_string($etat)."',
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

									$sql = "SELECT rewrite_url FROM manufacturers WHERE id=".intval($marque);
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$marque_photo = $row[0];

									$sql = "SELECT rewrite_url FROM models WHERE id=".intval($modele);
									$req = mysql_query($sql);
									$row = mysql_fetch_row($req);
									$modele_photo = $row[0];

									echo "Annonce ".$id_annonce." mise à jour\n";

									// Photos
									rmrf(PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo));
									if(!is_dir(PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo))){ mkdir(PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo), 0777); }
									$i=0;
									$ad_photo = '';
									$photosql = '';
									foreach($tabphoto as $photo)
									{
										@copy($photo, PATH_IMG.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo).'/photo-'.$i.'.jpg');
										$photosql .= '/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo).'/photo-'.$i.'.jpg;';
										if($i==0) {
											$ad_photo = '/assets/photos/uk/'.$id_annonce.'_'.strip_specialchar($marque_photo.'-'.$modele_photo).'/photo-'.$i.'.jpg;';
										}
										$i++;
									}
									//$sql = "UPDATE gateway_ads_details SET ad_photos='".$photosql."' WHERE id=".$id_annonce;
									$sql = "UPDATE gateway_ads_details SET ad_photo='".$ad_photo."', ad_photos='".$photosql."' WHERE id=".$id_annonce;
									mysql_query($sql);

									$cptUPDATE++;
									unset($ref_aa[array_search($reference, $ref_aa)]);
								}
								else
								{
									echo $sql."\n";
									echo "Problème MAJ annonce ".$reference."\n";
									fwrite($pfichier,"Problème update annonce ".$reference."\n".$sql."\n");
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
			}
		}

		var_dump($ref_aa);

		// DELETE
		foreach($ref_aa as $value)
		{
			$del = "UPDATE gateway_ads_details SET status='removed', deleted_at='".date("Y-m-d H:i:s")."' WHERE ad_ref='".mysql_real_escape_string($value)."'";
			if(RUN_TYPE!='test'){ mysql_query($del); }else{ echo $sql."\n"; }
			$cptDELETE++;
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
