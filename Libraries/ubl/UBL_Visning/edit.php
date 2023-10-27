<?php

require("/svn/svnroot/vendor/autoload.php");
$fn = "Examples/OIOUBL_Invoice.xml";

$serializer = JMS\Serializer\SerializerBuilder::create()->build();
$object = $serializer->deserialize(file_get_contents("$fn"), \MyNamespace\MyObject::class, 'xml');
