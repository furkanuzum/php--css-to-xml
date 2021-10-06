<?php

$inputFiles = glob("input/*.txt");

foreach ($inputFiles as $fileName) {
  echo "Start xml generation for $fileName \n";
  $xml = generateXml($fileName);
  echo "Finished xml generation for $fileName \n";
  
  $outputFileName = "output/" . basename($fileName, ".txt") . ".xml";
  $dom = new DOMDocument();
  $dom->formatOutput = true;
  $dom->loadXML($xml->asXML());
  //echo $dom->saveXML();
  echo $dom->save($outputFileName);
  echo "Saved as $outputFileName \n";
  

}
function generateXml($fname)
{
  if (($open = fopen($fname, "r")) !== FALSE) {
    while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
      // fputs ($data, "\xEF\xBB\xBF"); // vilh, change to UTF-8!
      $input[] = $data;
    }
    fclose($open);
  }
  

  $orderXml = new SimpleXMLElement('<order/>');
  $headerXml = $orderXml->addChild("header");
  $detailXml = $orderXml->addChild("lines");

  $headerArray = array_slice($input, 0, 2);
  foreach ($headerArray[0] as $index => $name) {
    $value = $headerArray[1][$index];
    // print "$index: $name $value \n";
    $headerXml->addChild($name, $value);
  }
  // echo $orderXml->asXML();


  $detailNames = array_slice($input, 2, 1)[0];
  $linesArray = array_slice($input, 3);
  foreach ($linesArray as $index => $line) {
    $lineXml = $detailXml->addChild("line");

    foreach ($detailNames as $index => $name) {
      $value = $line[$index];
      // print "$index: $name $value \n";
      $lineXml->addChild($name, $value);
    }
    // $detailXml->addChild($lineXml);
  }
  return  $orderXml;
}
