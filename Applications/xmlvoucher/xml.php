<?php
libxml_use_internal_errors(true);


function libxml_display_errors() {
	echo "Xml-Error:";
	print_r($errors);
	die();
}
?>
