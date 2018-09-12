<?php
/**
 * Convert an array to XML
 * @param array $array
 * @param SimpleXMLElement $xml
 */
function arrayToXml($array, &$xml){
    foreach ($array as $key => $value) {
        if(is_array($value)){
            if(is_int($key)){
                $key = "e";
            }
            $label = $xml->addChild($key);
            $this->arrayToXml($value, $label);
        }
        else {
            $xml->addChild($key, $value);
        }
    }
}

$raw_data = file_get_contents('https://api.import.io/store/connector/00992084-552b-486b-803e-98969b240c8d/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fcabin-cruiser%2Fbeneteau-antares-30%2F106208&&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb');
$array = json_decode ($raw_data, true);

$xml = new SimpleXMLElement('<root/>');
$this->arrayToXml($array, $xml);
