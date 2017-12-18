<?php

$xmlDoc = new DOMDocument;
$xmlDoc->load($_GET['url']) or die("Error: Cannot create object");

if (preg_match('/10076/', $_GET['url'])) {
    print $xmlDoc->saveXML();
} else {
  $xml = $xmlDoc->documentElement;
  $arr = $xml->getElementsByTagName('item');
  $domElemsToRemove = array();
  foreach ($arr as $item) { // DOMElement Object
      $enclosure = $item->getElementsByTagName('enclosure')->item(0);
      $url =  $enclosure->getAttribute('url');
      $is_complet = preg_match('/\-0\.mp3$/', $url);
      if (preg_match('/rss_10212/', $_GET['url'])) {
        $is_complet = preg_match('/\-6\.mp3$/', $url);
      }
      if (! $is_complet) {
          $domElemsToRemove[] = $item;
      }

  }
  foreach ( $domElemsToRemove as $domElement ) {
    $domElement->parentNode->removeChild($domElement);
  }
  print $xmlDoc->saveXML();
}
