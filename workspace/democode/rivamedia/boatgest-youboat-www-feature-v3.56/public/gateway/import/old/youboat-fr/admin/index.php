<?php
	error_reporting(~E_NOTICE & ~E_DEPRECATED);
	ini_set('memory_limit', '256M');
	set_time_limit(3600);
	define('RUN_TYPE', 'debug');
	switch(RUN_TYPE)
	{
		case "test":
			define('BDD_USER', 'de96033');
			define('BDD_PWD', 'Gahshe4ohTea');
			define('BDD_SERVER', 'localhost');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', $_SERVER['DOCUMENT_ROOT'] . '/gateway/import/');
			define('PATH_IMG', $_SERVER['DOCUMENT_ROOT'] . '/assets/photos/uk/');
			define('ID_AUTOMAT', 1);
		break;
		case "debug":
			define('BDD_USER', 'root');
			define('BDD_PWD', 'root');
			define('BDD_SERVER', '127.0.0.1:8889');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', $_SERVER['DOCUMENT_ROOT'] . '/gateway/import/');
			define('PATH_IMG', $_SERVER['DOCUMENT_ROOT'] . '/assets/photos/uk/');
			define('ID_AUTOMAT', 1);
		break;
		case "release":
			define('BDD_USER', 'de96033');
			define('BDD_PWD', 'Gahshe4ohTea');
			define('BDD_SERVER', 'localhost');
			define('BDD_NAME', 'youboat-www');
			define('PATH_AUTOMAT', $_SERVER['DOCUMENT_ROOT'] . '/gateway/import/');
			define('PATH_IMG', $_SERVER['DOCUMENT_ROOT'] . '/assets/photos/uk/');
			define('ID_AUTOMAT', 1);
		break;
	}

	$currenturl = dirname($_SERVER['SERVER_PROTOCOL']).'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


	$db = mysql_connect(BDD_SERVER, BDD_USER, BDD_PWD);
	mysql_select_db(BDD_NAME, $db);
	mysql_query("SET NAMES 'utf8'");
	
	require(PATH_AUTOMAT.'gateway-functions.php');
	
	if(isset($_POST['action']) && $_POST['action']=='supprimer')
	{
		$sql = "DELETE FROM gateway_error WHERE id=".$data['erreur'];
		mysql_query($sql);
	}
	
	if(isset($_POST['action']) && $_POST['action']=='blacklist')
	{
		$sql = "INSERT INTO gateway_blacklist(gateway_id, type, libelle) VALUES(".intval($_POST['id_automat']).", '".mysql_real_escape_string($_POST['type'])."', '".mysql_real_escape_string($_POST['libelle'])."')";
		mysql_query($sql);
	}

	if(isset($_POST['action']) && $_POST['action']=='creer-marque')
	{
		$sql = "INSERT INTO manufacturers(name, rewrite_url, referrer, created_at, updated_at) VALUES('".mysql_real_escape_string($_POST['marque'])."', '".strip_specialchar($_POST['marque'])."', 'Rivamedia', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		mysql_query($sql);
	}
	
	if(isset($_POST['action']) && $_POST['action']=='creer-modele')
	{
		$sql = "INSERT INTO models(manufacturers_id, name, rewrite_url, referrer, created_at, updated_at) VALUES(".$_POST['marque'].", '".mysql_real_escape_string($_POST['modele'])."', '".strip_specialchar($_POST['modele'])."', 'Rivamedia', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
		mysql_query($sql);
	}
	
	if(isset($_POST['action']) && $_POST['action']=='associer-marque')
	{
		$sql = "INSERT INTO gateway_assoc_manufacturers(manufacturers_id, rewrite) VALUES(".$_POST['marque'].", '".$_POST['rewrite']."')";
		mysql_query($sql);
	}
	
	if(isset($_POST['action']) && $_POST['action']=='associer-modele')
	{
		$sql = "INSERT INTO gateway_assoc_models(models_id, manufacturers_id, rewrite) VALUES(".$_POST['modele'].", ".$_POST['marque'].", '".$_POST['rewrite']."')";
		mysql_query($sql);
	}
?>
<html>
	<head>
		<style type="text/css">
			table.table{border-collapse:collapse;}
				table.table td{background-color:#ffffff;padding:5px;border:1px solid #ccc;font-size:11px !important;font-family:Arial !important;}
					table.table td.legend{background-color:#555555;color:#ffffff;font-weight:bold;}
				table.table tr.tr1 td{background-color:#FFEBE8;}
				table.table ul{padding-left:10px;margin:0px;}
			.red{color:#D84E42}
		</style>
	</head>
	<body>
		<h1>Erreur passerelle</h1>
		<table class="table" width="100%">
			<tr>
				<th>ID</th>
				<th>ID passerelle</th>
				<th>Marque</th>
				<th>Relation marque</th>
				<th>Mod�le</th>
				<th>Relation mod�le</th>
				<th>Date</th>
				<th></th>
			</tr>
			<?php
				$class='tr1';
				$sql = "SELECT id, gateway_id, manufacturers, models, date FROM gateway_error ORDER BY id DESC";
				$amat = mysql_query($sql);
				while($data = mysql_fetch_array($amat))
				{
					$data['manufacturers'] = trim($data['manufacturers']);
					$data['models'] = trim($data['models']);
				
					$id_marque = match_marque($data['manufacturers']);
					$id_modele = match_modele($data['models'], $id_marque);
					
					if((!empty($id_marque) || (empty($id_marque) && empty($data['manufacturers'])) || passerelle_blacklist($data['id_automat'], 'manufacturers', $data['manufacturers'])) &&
						(!empty($id_modele) || (empty($id_modele) && empty($data['models'])) || passerelle_blacklist($data['id_automat'], 'models', $data['models'])))
					{
						$sql = "DELETE FROM gateway_error WHERE id=".$data['id'];
						mysql_query($sql);
					}
					else
					{
						if(empty($class)){ $class='tr1'; }else{ $class=''; }
						
						echo '<tr class="'.$class.'">
								<td>'.$data['id'].'</td>
								<td id="automat-'.$data['id'].'">'.$data['gateway_id'].'</td>
								<td>';
									if(!passerelle_blacklist($data['gateway_id'], 'manufacturers', $data['manufacturers']))
									{
										echo '<form method="post" action="'.$currenturl.'">
												<input type="hidden" name="action" value="creer-marque" />
												<input type="text" name="marque" value="'.$data['manufacturers'].'" /><br /><input type="submit" value="Cr�er" />
											</form>';
										echo '<form method="post" action="'.$currenturl.'">
												<input type="hidden" name="libelle" value="'.$data['manufacturers'].'" />
												<input type="hidden" name="id_automat" value="'.$data['gateway_id'].'" />
												<input type="hidden" name="type" value="marque" />
												<input type="hidden" name="action" value="blacklist" />
												<input type="submit" value="Blacklister" />
											</form>';
									}
									else
									{
										echo $data['manufacturers'].'<br /><span class="red">Blacklist</span>';
									}
							echo '</td>
								<td>';
									echo '<form method="post" action="'.$currenturl.'">
											<input type="hidden" name="action" value="associer-marque" />
											<input type="hidden" name="rewrite" value="'.strip_specialchar($data['manufacturers']).'" />
											<select name="marque">
												<option value=""></option>';
												$sql2 = "SELECT id, name FROM manufacturers ORDER BY name";
												$req2 = mysql_query($sql2);
												while($data2 = mysql_fetch_assoc($req2))
												{
													$selected = '';
													if($data2['id']==$id_marque){ $selected = ' selected="selected"'; }
													echo '<option value="'.$data2['id'].'"'.$selected.'>'.$data2['name'].'</option>';
												}
										echo '</select>';
											if(empty($id_marque)){ echo ' <span class="red">X</span><br /><input type="submit" value="Associer" />'; }else{ echo ' <span style="color:#18c900">V</span>'; }
									echo '</form>';
							echo '</td>
								<td>';
								if(!empty($data['models']))
								{
									if(!passerelle_blacklist($data['gateway_id'], 'models', $data['models']))
									{
										echo '<form method="post" action="'.$currenturl.'">
												<input type="hidden" name="action" value="creer-modele" />
												<input type="hidden" name="marque" value="'.$id_marque.'" />
												<input type="text" name="modele" value="'.$data['models'].'" /><br /><input type="submit" value="Cr�er" />
											</form>';
										echo '<form method="post" action="'.$currenturl.'">
												<input type="hidden" name="libelle" value="'.$data['models'].'" />
												<input type="hidden" name="id_automat" value="'.$data['gateway_id'].'" />
												<input type="hidden" name="type" value="modele" />
												<input type="hidden" name="action" value="blacklist" />
												<input type="submit" value="Blacklister" />
											</form>';
									}
									else
									{
										echo $data['models'].'<br /><span class="red">Blacklist</span>';
									}
								}else{ echo '-'; }
							echo '</td>
								<td>';
									if(!empty($data['models']) && !passerelle_blacklist($data['gateway_id'], 'models', $data['models']))
									{
										if(!empty($id_marque))
										{
											echo '<form method="post" action="'.$currenturl.'">
													<input type="hidden" name="action" value="associer-modele" />
													<input type="hidden" name="marque" value="'.$id_marque.'" />
													<input type="hidden" name="rewrite" value="'.strip_specialchar($data['models']).'" />
													<select name="modele">
														<option value=""></option>';
														$sql2 = "SELECT id, name FROM models WHERE manufacturers_id=".$id_marque." ORDER BY name";
														$req2 = mysql_query($sql2);
														while($data2 = mysql_fetch_assoc($req2))
														{
															$selected = '';
															if($data2['id']==$id_modele){ $selected = ' selected="selected"'; }
															echo '<option value="'.$data2['id'].'"'.$selected.'>'.$data2['name'].'</option>';
														}
												echo '</select>';
													if(empty($id_modele)){ echo ' <span class="red">X</span><br /><input type="submit" value="Associer" />'; }else{ echo ' <span style="color:#18c900">V</span>'; }
											echo '</form>';
										}
										else
										{
											echo 'Associez d\'abord une marque';
										}
									}
									else
									{
										echo '-';
									}
							echo '</td>
								<td>'.$data['date'].'</td>
								<td>
									<form method="post" action="'.$currenturl.'">
										<input type="hidden" name="action" value="supprimer" />
										<input type="hidden" name="erreur" value="'.$data['id'].'" />
										<input type="submit" value="X" />
									</form>
								</td>
							</tr>';
					}
				}
			?>            
		</table>
	</body>
</html>