<?php

require("phpMQTT.php");
require("config.php");
	
$mqtt = new phpMQTT("consometre.fr", 1883, "Cercle1"); //Change client name to something unique

if(!$mqtt->connect()){
	exit(1);
}

$topics['CercleConfiance'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc()){
		
}


$mqtt->close();

function procmsg($topic,$msg){
    $db = new \PDO(DSN, USER, PASS);
    $arrayMsg = explode(" ",$msg);
    $reqModel = "SELECT * FROM model WHERE uniq_id = :uniqId";
    $prepModel = $db->prepare($reqModel);
    $prepModel->bindValue(':uniqId', $arrayMsg[0], \PDO::PARAM_STR);
    $prepModel->execute();
    $resModel = $prepModel->fetchAll(\PDO::FETCH_OBJ);

    $date = new \DateTime();
    $stringDate = $date->format('Y-m-d h:i:s');
    $req = "INSERT INTO data_object(model_id, data, date) VALUES (:model, :data, :date)";
    $prep = $db->prepare($req);
    $prep->bindValue(':model', $resModel[0]->id, \PDO::PARAM_INT);
    $prep->bindValue(':data', $arrayMsg[1], \PDO::PARAM_STR);
    $prep->bindValue(':date', $stringDate, \PDO::PARAM_STR);
    $prep->execute();
	echo "Message reçu et envoyé en base !\n";
}
	


?>
