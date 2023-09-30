<?php
$client = new SoapClient("http://www.ocrwebservice.com/services/OCRWebService.asmx?WSDL"
, array("trace"=>1, "exceptions"=>1)

);

$params = new StdClass();
$params->user_name = "olsenit";
$params->license_code = "ACD10236-7771-4632-A203-58286D6AC38A";
			 

$inimage = new StdClass();



$handle = fopen($argv[1], 'r');
$card_image = fread($handle, filesize($argv[1]));
fclose($handle);

$inimage->fileName = $argv[1];
$inimage->fileData = $card_image;

$params->OCRWSInputImage = $inimage;

$settings = new StdClass();
$settings->ocrLanguages = array("DANISH");
$settings->outputDocumentFormat  = "HTML";
$settings->convertToBW = FALSE;
$settings->getOCRText = TRUE;
$settings->createOutputDocument = TRUE;
$settings->multiPageDoc = FALSE;
$settings->ocrWords = FALSE;

$params->OCRWSSetting = $settings;

try 
{
$result = $client->OCRWebServiceRecognize($params);
} 
catch (SoapFault $fault) 
{
print($client->__getLastRequest());
print($client->__getLastRequestHeaders());
}
if (!isset($result->OCRWSResponse->fileData)) {
	print_r($result);die();
}
print_r($result->OCRWSResponse->fileData);
?>
