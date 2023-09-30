<?php

/**

* Input an object, returns a json-ized string of said object, pretty-printed

*

* @param mixed $obj The array or object to encode

* @return string JSON formatted output

*/

function json_encode_pretty($obj, $indentation = 0) {

switch (gettype($obj)) {

case 'object':

$obj = get_object_vars($obj);

case 'array':

if (!isset($obj[0])) {

$arr_out = array();

foreach ($obj as $key => $val) {

$arr_out[] = '"' . addslashes($key) . '": ' . json_encode_pretty($val, $indentation + 1);

}

if (count($arr_out) < 2) {

return '{' . implode(',', $arr_out) . '}';

}

return "{\n" . str_repeat(" ", $indentation + 1) . implode(",\n".str_repeat(" ", $indentation + 1), $arr_out) . "\n" . str_repeat(" ", $indentation) . "}";

} else {

$arr_out = array();

$ct = count($obj);

for ($j = 0; $j < $ct; $j++) {

$arr_out[] = json_encode_pretty($obj[$j], $indentation + 1);

}

if (count($arr_out) < 2) {

return '[' . implode(',', $arr_out) . ']';

}

return "[\n" . str_repeat(" ", $indentation + 1) . implode(",\n".str_repeat(" ", $indentation + 1), $arr_out) . "\n" . str_repeat(" ", $indentation) . "]";

}

break;

case 'NULL':

return 'null';

break;

case 'boolean':

return $obj ? 'true' : 'false';

break;

case 'integer':

case 'double':

return $obj;

break;

case 'string':

default:

$obj = str_replace(array('\\','"',), array('\\\\','\"'), $obj);

return '"' . $obj . '"';

break;

}

}
