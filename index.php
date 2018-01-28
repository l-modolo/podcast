<?php
$xmlDoc = new DOMDocument;
$xmlDoc->load($_GET['url']) or die("Error: Cannot create object");
$xml = $xmlDoc->documentElement;
$arr = $xml->getElementsByTagName('item');
$domElemsToRemove = array();
$domElemsToEval = array();
$lastDate = "";
unset($lastDate);
$maxLenght = 0;
foreach ( $arr as $item ) { // DOMElement Object
    $enclosure = $item->getElementsByTagName('enclosure')->item(0);
    $length = $enclosure->getAttribute('length');
    $length = intval($length);
    $pubdate = $item->getElementsByTagName('pubDate')->item(0)->nodeValue;
    $date = preg_replace(
        '/(.*) \d{2}:\d{2}:\d{2} .*/',
        '$1',
        $pubdate
    );
    $date = strval($date);
    if ( isset($lastDate) && strcmp($lastDate, $date) !== 0 ) {
        foreach ( $domElemsToEval as $itemToEval ) {
            $enclosureToEval = $itemToEval->getElementsByTagName('enclosure')->item(0);
            $lengthToEval = $enclosureToEval->getAttribute('length');
            $lengthToEval = intval($lengthToEval);
            if ( $lengthToEval < $maxLenght ) {
                $domElemsToRemove[] = $itemToEval;
            }
        }
        $maxLenght = 0;
        unset($domElemsToEval);
        $domElemsToEval = array();
    }
    $lastDate = $date;
    if ( $length > $maxLenght ) {
        $maxLenght = $length;
    }
    $domElemsToEval[] = $item;
}
foreach ( $domElemsToRemove as $domElement ) {
    $domElement->parentNode->removeChild($domElement);
}
print $xmlDoc->saveXML();
?>
