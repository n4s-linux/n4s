<?php

if(isset($argv[1])) {
	$schema = $argv[1];

	$schemaName = explode('.', $schema);
	$schemaName = $schemaName[0];
	
//	$xmlFile = $schemaName . ".xml";
	$xmlFile = $schemaName . "_" . date('d-m-Y_hisA'). ".xml";
	$contents = file_get_contents($schema);
	$result = XML_unserialize($contents);
	
	$search4Attrib = true;
	while($search4Attrib == true){
	
		$attribs = searchAttribs($result);
		if(isset($attribs[1])){
			$result = insertAttribs($result, $attribs[0], $attribs[1]);	
		}
		else {
		$search4Attrib = false;
		}
	}
	
	$search4Sequence = true;
	while($search4Sequence == true){
		$sequence = searchSequence($result);
		if(!empty($sequence)){
			$result = insertSequence($result, $sequence[0]);
		} else {
		$search4Sequence = false;
		}
	}

	$file = fopen($xmlFile, "w");
	$final = XML_serialize($result, null , null , $file);
	fclose($file);
	
	validate($xmlFile, $schema);
}

function validate($xmlFile, $schema){

	$xml = new DOMDocument();
	$xml->load($xmlFile);

	if (!$xml->schemaValidate($schema)) {
		print 'DOMDocument::schemaValidate() Generated Errors!';
	} else {
		echo "\n validation ok! ";
	}	
}

function insertSequence($data, $name, $level = 0){
	$retVal = array();
	foreach($data as $key => $val){
		if(is_array($val)){
			if(isset($val['name']) && $val['name'] == $name){
				$val['sequence'] = true;
				$retVal[$key] = $val;
			}else {
				$retVal[$key] = insertSequence($val, $name, $level +1);
			}
		} else { 
				$retVal[$key] = $val;
		}
	}
	return $retVal;
}

function searchSequence($data, $level = 0) {
	
	$goFind = false;
	$data2 = array();
	foreach($data as $key => $val){
		if($goFind == true && is_array($val)) {
			$isSequence = findSequence($val);
			$nameWithAttrib = $data[$name]['name'];
			if($isSequence) {
				return array($nameWithAttrib, $isSequence);
			} else {
			$goFind = false;
				continue;
			}
		}
		if(is_array($val)){
			if(isset($val['name']) && !isset($val['sequence'])){
				 $name = $key;
				 $goFind = true;
			} else {
				$goFind = false;
				$data2 = searchSequence($val, $level +1);
			}
		} else $goFind = false;
	}
	return $data2;
}
function findSequence($data, $level = 0) {
	$isSequence = false;
	while($level < 2){
		foreach($data as $key => $val){
			if($key === "xs:sequence"){
					$isSequence = true;
					break;
			} else {
				if(is_array($val)){
					$isSequence = findSequence($val, $level+1);
				}
			};
		}
	return $isSequence;
	}
	
}

function insertAttribs($data, $name, $attrib, $level = 0){
	$retVal = array();
	foreach($data as $key => $val){
		if(is_array($val)){
			if(isset($val['name']) && $val['name'] === $name){
				$val['name'] = $name . " ". $attrib;
				$retVal[$key] = $val;
			}elseif(isset($val['name']) && $val['name'] == $attrib){
				continue;
			}else {
				$retVal[$key] = insertAttribs($val, $name, $attrib, $level +1);
			}
		} else { 
			if($key !== "xs:attribute"){
				$retVal[$key] = $val;
			} else {
				continue;
			}
		}
	}
	return $retVal;
}

function searchAttribs($data, $level = 0) {
	$goFind = false;
	$data2 = array();
	foreach($data as $key => $val){
		if($goFind == true && is_array($val)) {
			$attrib = findAttrib($val);
			$nameWithAttrib = $data[$keyName]['name'];
			if(!empty($attrib)) {
				return array($nameWithAttrib, $attrib);
			} 
		}
		if(is_array($val)){
			if(isset($val['name'])){
				 $keyName = $key;
				 $goFind = true;
			} else {
				$goFind = false;
				$data2 = searchAttribs($val, $level +1);
			}
		} else $goFind = false;
	}
	return $data2;
}

function findAttrib($data, $level = 0) {
	$attrib = null;
	while($level < 2){
	foreach($data as $key=> $val){
		if($key == "xs:attribute attr"){
			if(is_array($val)){
				$attrib = $val['name'];
				break;
			}
		} else {
			if(is_array($val)){
				$attrib = findAttrib($val, $level+1);
			}
		};
	}
	return $attrib;
	}
}	
################################################################################
###
#
# XML Library, by Keith Devens, version 1.2b
# <a href="http://keithdevens.com/software/phpxml" target="_blank">http://keithdevens.com/software/phpxml</a>
#
# This code is Open Source, released under terms similar to the Artistic License.
# Read the license at <a href="http://keithdevens.com/software/license" target="_blank">http://keithdevens.com/software/license</a>
#
################################################################################
###

################################################################################
###
# XML_unserialize: takes raw XML as a parameter (a string)
# and returns an equivalent PHP data structure
################################################################################
###
function & XML_unserialize(&$xml){
	$xml_parser = new XML();
	$data = &$xml_parser->parse($xml);
	$xml_parser->destruct();
	return $data;
}
################################################################################
###
# XML_serialize: serializes any PHP data structure into XML
# Takes one parameter: the data to serialize. Must be an array.
################################################################################
###
function XML_serialize($data, $level = 0, $prior_key = NULL , $file = NULL){

	if($level == 0){ fwrite($file, '<?xml version="1.0" ?>'."\n"); }
	while(list($key, $value) = each($data))
	
	//echo "\n\n\n SERIALIZE \n\n\n";
//	echo "\n key: $key \n";
	if(!strpos($key, ' attr')) #if it's not an attribute
	#we don't treat attributes by themselves, so for an empty element
	# that has attributes you still need to set the element to NULL
	if(is_array($value) && array_key_exists(0, $value)){
	
		XML_serialize($value, $level, $key, $file);
	}else{
		$tag = $prior_key ? $prior_key : $key;
		
		if(array_key_exists("$key attr", $data)){ #if there's an attribute for this element
			
			while(list($attr_name, $attr_value) = each($data["$key attr"]))
			
			if($attr_name == 'name'){
				$attr_valueExploded = explode(' ', $attr_value);
				if(is_array($value)){
					if(isset($attr_valueExploded[1])) {
						echo "\nEntering '".htmlspecialchars($attr_valueExploded[0])."'...";
						fwrite($file, str_repeat("\t", $level).'<'.htmlspecialchars($attr_value)."=\"". ask($attr_valueExploded[1])."\"> \n");
					} else {
						echo "\nEntering '".htmlspecialchars($attr_value)."'...";
						fwrite($file, str_repeat("\t", $level).'<'.htmlspecialchars($attr_value)."> \n");
					}		
				}	
				else fwrite($file, str_repeat("\t", $level).'<'.htmlspecialchars($attr_value));
				$name = htmlspecialchars($attr_value);
			}
			reset($data["$key attr"]);
		}
		
		if(isset($name) && !is_array($value)) fwrite($file,  '>'.ask($name)."</$name>\n");
		elseif(isset($name)){ 
			$nameExploded =  explode(' ', $name);
			if(isset($nameExploded[1])) $name = $nameExploded[0];
			fwrite($file,  XML_serialize($value, $level+1, null, $file) .str_repeat("\t", $level)."</$name>\n");
		}
		else fwrite($file,  XML_serialize($value, $level+1, null, $file));
		
		if(isset($data["$key attr"]["sequence"])){
				$sequence =  $data["$key attr"]["sequence"];
				$sequenceName = $data["$key attr"]["name"];
				unset($data["$key attr"]["sequence"]);
				
				$seq =true;
				while($seq == true){
					
					echo "\nWould You like to add another $name? (y/n)";
					$repeat = trim(strtolower(fgets(STDIN)));
					
					if ($repeat == 'y' || $repeat == '') {
						$retArray = array();
						$retArray["$key attr"] = $data["$key attr"];
						$retArray["$key"] = $data["$key"];
						
						$data2 = XML_serialize($retArray, $level, null, $file);
					} else { 
					$seq = false;	
					} 
				}
			}
		
		
	}
	reset($data);

}
################################################################################
###
# XML class: utility class to be used with PHP's XML handling functions
################################################################################
###
class XML{
	var $parser;   #a reference to the XML parser
	var $document; #the entire XML structure built up so far
	var $parent;   #a pointer to the current parent - the parent will be an array
	var $stack;    #a stack of the most recent parent at each nesting level
	var $last_opened_tag; #keeps track of the last tag opened.

	function XML(){
		$this->parser = &xml_parser_create();
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'open','close');
		xml_set_character_data_handler($this->parser, 'data');
	}
	function destruct(){ xml_parser_free($this->parser); }
	function parse($data){
		$this->document = array();
		$this->stack    = array();
		$this->parent   = &$this->document;
		return xml_parse($this->parser, $data, true) ? $this->document : NULL;
	}
	function open(&$parser, $tag, $attributes){
		$this->data = ''; #stores temporary cdata
		$this->last_opened_tag = $tag;
		if(is_array($this->parent) and array_key_exists($tag,$this->parent)){ #if you've seen this tag before
			if(is_array($this->parent[$tag]) and array_key_exists(0,$this->parent[$tag])){ #if the keys are numeric
				#this is the third or later instance of $tag we've come across
				$key = count_numeric_items($this->parent[$tag]);
			}else{
				#this is the second instance of $tag that we've seen. shift around
				if(array_key_exists("$tag attr",$this->parent)){
					$arr = array('0 attr'=>&$this->parent["$tag attr"], &$this->parent[$tag]);
					unset($this->parent["$tag attr"]);
				}else{
					$arr = array(&$this->parent[$tag]);
				}
				$this->parent[$tag] = &$arr;
				$key = 1;
			}
			$this->parent = &$this->parent[$tag];
		}else{
			$key = $tag;
		}
		if($attributes) $this->parent["$key attr"] = $attributes;
		$this->parent  = &$this->parent[$key];
		$this->stack[] = &$this->parent;
	}
	function data(&$parser, $data){
		if($this->last_opened_tag != NULL) #you don't need to store whitespace in between tags
		$this->data .= $data;
	}
	function close(&$parser, $tag){
		if($this->last_opened_tag == $tag){
			$this->parent = $this->data;
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack) $this->parent = &$this->stack[count($this->stack)-1];
	}
}
function count_numeric_items(&$array){
	return is_array($array) ? count(array_filter(array_keys($array), 'is_numeric')) : 0;
}

function ask($name){
	echo "Please enter  '$name': ";
	$getValue = trim(fgets(STDIN, 32));
	return $getValue;
}
?>
