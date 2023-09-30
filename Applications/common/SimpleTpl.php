<?php
	namespace Mikjaer\SimpleTpl;
	class SimpleTpl
        {
            private $stack;
            private $linenumber;

            private $debug = false;
                
	    private $variable_name = '[0-9a-zA-Z_\$]+';

            public function debug($debug)
            {
                $this->debug = $debug;
            }

            public function append($key, $value)
            {
                $this->stack[$key][] = $value;
            }

            public function merge($key, $value)
            {
                $this->stack[$key] = array_merge ( $this->stack[$key], $value );
            }

            public function assign($key, $value)
            {
                $this->stack[$key] = $value;
            }
     
            private function intValue($value)
            {
                
                $tokkens = preg_split('/(\[[0-9a-z-A-Z\$\.]+\])|\.|\$?('.$this->variable_name.')/', $value , -1, PREG_SPLIT_DELIM_CAPTURE + PREG_SPLIT_NO_EMPTY);
                $current = $this->stack;

                foreach ($tokkens as $tokken)
                {
                    if (preg_match('/\[([0-9]+)\]/',$tokken,$m))
                    {
                        $current = $current[$m[1]];
                    }
                    else if (preg_match('/\[(.+)\]/',$tokken,$m))
                    {
                        $current = @$current[intval($this->intValue($m[1]))];
                    }
                    else
                    {
                        if (isset($current[$tokken]))
                            $current = $current[$tokken];
                        else 
                            $current = "";
                    }
                }
                return $current;
            }

            private function runtimeError($error)
            {
                http_response_code(500);
                if ($this->debug)
                    die("SimpleTPL ERROR in line ".$this->linenumber[$this->eip]["line"]." in ".$this->filename.": $error");
                else
                    die("SimpleTPL Runtime error");
            }

            public function runtimeWarning($warning)
            {
                if ($this->debug)
                    print("<strong>SimpleTPL WARNING</strong> in line ".$this->linenumber[$this->eip]["line"]." in ".$this->filename.": ".$warning."<br>");

#print "<pre>"; print_r($this->linenumber); die();
                error_log("SimpleTPL WARNING ".$this->filename." (".$this->linenumber[$this->eip]["line"]."): ". $warning);
            }

            private function intParseParams($params)
            {
                $ret = array();
                preg_match_all("/([a-zA-Z]+)=([\"a-zA-Z0-9\$\.\[\]]+)/",$params,$matches);
                
		if (count($matches[1])>0)
                    for ($i=0; $i<=count($matches); $i++)
                        if ((isset($matches[1][$i])) && (isset($matches[2][$i])))
                            $ret[$matches[1][$i]]=$matches[2][$i];
                
                
                return $ret; 
            }

            private function intParseIdentifier($identifier)
            {
                if (preg_match('/^[\'"](.*)[\'"]/', $identifier,$m))  // If we are given a constant i.e. "foobar" return it as is (without quotes)
                    return $m[1];
                if (is_numeric($identifier))
                    return $identifier;
                if ($identifier[0] == "$")
                    return $this->intValue(substr($identifier,1));

                $this->runtimeError("Unknown identifier: $identifier");
            }

            private function intRender($tokkens)
            {
                $t = $tokkens;
                
                $this->eip = 0;

                $loops = array();

                $ifs = array();

                $ret = array();

                $suppress = false;

                while ($this->eip < count($t))
                {
                    if (preg_match('/{(if|\/if|fi|elseif|else)[ ]*(.*)}/i',$t[$this->eip],$m))    # if clause
                    {
                        $keyword = strtoupper($m[1]);
                        $clause = $m[2];
                        
                        if ($keyword == "ELSEIF")
                        {
                                if (count($ifs) > 0)            // Check if we are inside an if-structure
                                    if ($ifs[count($ifs)-1])    // Have this structure allready had a hit
                                        $keyword='ELSE';        // Then handover to else ... which will search for our /if
                                    else    
                                        $keyword='IF';          // If not handover to if 
                                else
                                    $this->runtimeError("Parentless /if found");
                            }


                        switch ($keyword)
                        {
                            case "IF":
                                if (preg_match('/[ ]*(\!)?[ ]*(.*?)[ ]*(==|!=|<|>)[ ]*(.*)/',$clause,$m)) // IF $value == $value
                                {
                                    $not = $m[1] == '!';
                                    $left = $this->intParseIdentifier($m[2]);
                                    $operator = $m[3];
                                    $right = $this->intParseIdentifier($m[4]);

                                    $res = false;
                                    switch ($operator)
                                    {
                                        case "==":
                                            $res = $left == $right;
                                            break;
                                        case "!=":
                                            $res = $left != $right;
                                            break;
                                        case ">":
                                            $res = $left > $right;
                                            break;
                                        case "<":
                                            $res = $left < $right;
                                            break;
                                    }
                              
                                } else if (preg_match('/(\!)?(.*)/',$clause,$m))   // IF $value
                                {
                                        $not = $m[1] == '!';
                                        
                                        if ($this->intParseIdentifier($m[2]) != "")
                                            $res = true;
                                        else
                                            $res = false;
                                } else {
                                    $this->runtimeError("Malformed if statement");
                                }
                                    
                                if ($not)
                                    $res = ! $res;

                                if ($res)
                                    $ifs[]=$res;

                                if (!$res)  // Clause failed, wee need to supress the content of the if-block
                                {
                                        
                                    $current_level = count($ifs);
                                    $ifs[]=$res;
                                        
                                    while (count($ifs) > $current_level)
                                    {
                                        $this->eip ++;
                                            
                                        if (preg_match('/{if/',$t[$this->eip]))    // One nested if found
                                            $ifs[]=false;

                                        if (preg_match('/{\/if}/',$t[$this->eip]))    // If terminated
                                            array_pop($ifs); 
                                           
                                        if ((count($ifs)-1 == $current_level) and (preg_match('/{else}/',$t[$this->eip])))    // Else ... let the loop handle the rest
                                            break;
                                        
                                        if ((count($ifs)-1 == $current_level) and (preg_match('/{elseif/',$t[$this->eip+1])))    // If next tokken is an elseif, we handover to the loop 
                                            break;
                                        
                                        if ($this->eip==count($t)) 
                                            $this->runtimeError("Neverending if-sentence");
                                    }
                                 }
                            break;
                            case "ELSE":
                                $current_level = count($ifs) - 1;
                                while (count($ifs) > $current_level)
                                {
                                    $this->eip ++;
                                             
                                    if (preg_match('/{if/',$t[$this->eip]))    // One nested if found
                                        $ifs[]=false;

                                    if (preg_match('/{\/if}/',$t[$this->eip]))    // If terminated
                                        array_pop($ifs); 

                                    if ($this->eip==count($t)) 
                                        $this->runtimeError("Neverending else-sentence");
                                }
                            break;
                            case "/IF":
                                if (count($ifs) > 0)
                                    array_pop($ifs);
                                else
                                    $this->runtimeError("Parentless /if found");
                            break;
                        }
                    }
                    
                    else if (preg_match('/{([\/a-zA-Z]+)(.*)}/',$t[$this->eip],$m))    # Straight variable names
                    {
                        $keyword = $m[1];
                        $params = $this->intParseParams($m[2]);
                        if ($keyword == "assign")
                        {
			    $val = $this->intParseIdentifier($params["value"]);
                            $var = substr($params["var"],1);
                            $this->assign($var, $val);
                        }

                        if ($keyword == "enableDebugging")
                            $this->debug(true);

                        if ($keyword == "disableDebugging")
                            $this->debug(false);

                        if ($keyword == "for")
                        {
                            if (!isset($params["name"]))
                                $this->runtimeError("Error, for-loop should have a name.");
                            
                            $name = $params["name"];
                            unset($loops[$name]);
                            $loops[$name]=$params; 

                            if (!isset($params["start"]))
                                $this->runtimeError("Error, for-loop should have a start value.");

                            if (!isset($params["stop"]))
                                $this->runtimeError("Error, for-loop should have a stop value.");

                            if (!isset($params["step"]))
                                    $loops[$name]["step"]=1; // If you do not supply a step value, asume 1

                            $loops[$name]["eip"]=$this->eip; 
                            $loops[$name]["count"]=$params["start"]; 
                            $this->stack[$name] = $loops[$name];
                        }

                        if ($keyword == "/for")
                        {
                            $name = end($loops)["name"];
                            
                            if ($loops[$name]["start"] < $loops[$name]["stop"]) // Vi taeller op
                            {
                                $loops[$name]["count"]=$loops[$name]["count"]+$loops[$name]["step"];
                
                                if ($loops[$name]["count"] <= $loops[$name]["stop"])
                                    $this->eip = $loops[$name]["eip"];
                                else
                                    array_pop($loops);
                            } 
                            elseif ($loops[$name]["start"] > $loops[$name]["stop"]) // Vi taeller ned 
                            {
                                $loops[$name]["count"]=$loops[$name]["count"]-$loops[$name]["step"]; 
                                
                                if ($loops[$name]["count"] >= $loops[$name]["stop"])
                                    $this->eip = $loops[$name]["eip"];
                                else
                                    array_pop($loops);
                            }
                            else    // vi taeller ikke 
                            {
                            
                            }
                            $this->stack[$name] = $loops[$name];
                        }

                        if ($keyword == "foreach")
                        {
                            if (!isset($params["name"]))
                                $this->runtimeError("Error, foreach-loop should have a name.");
                            $name = $params["name"];
                        
                            if (!isset($loops[$name]))  // First time
                            {
                                unset($loops[$name]);
                               
                               if (!isset($params["loop"]))
                                    $this->runtimeError("Error, foreach-loop should have a loop array.");

                                $loops[$name]["name"] = $name;
                                $loops[$name]["loop"] = substr($params["loop"],1); 
                                
                                if ( !is_array($this->intValue($params["loop"])))
                                {
                                    if (@$params["ignoreWarnings"] != "true")
                                        $this->runtimeWarning("Unknown variable ".$params["loop"]." in loop '".$loops[$name]["name"]."'");
                                    $loops[$name]["keys"] = array();
                                }
                                else 
                                    $loops[$name]["keys"] =array_keys($this->intValue($params["loop"]));

                                $loops[$name]["eip"] =$this->eip; 
                            }
                            if (!is_array($loops[$name]["keys"]))
                            {
                                if (@$params["ignoreWarnings"] != "true")
                                    $this->runtimeWarning("Input is not an array ".$loops[$name]["name"]." in loop '".$loops[$name]["name"]."'");
                                $loops[$name]["index"] = false;
                            }
                            else
                            {
                                $loops[$name]["index"] = array_shift($loops[$name]["keys"])."\n";
                            }
                           $this->stack[$name] = $loops[$name];
                        }

                        if ($keyword == "/foreach")
                        {
                            $name = end($loops)["name"];
                            if (count($loops[$name]["keys"])!=0)
                                $this->eip = $loops[$name]["eip"]-1;
                            else
                                unset($loops[$name]);
                        }

                    } else {
                    if (preg_match('/{\$(.+)}/',$t[$this->eip],$m))
                    {
                        if (!$suppress)
                            $ret[] = $this->intValue($m[1]);
                    }
                    else    
                        if (!$suppress)
                            $ret[] = $t[$this->eip];
                    }

                $this->eip++;
            }
            return $ret;
            }

        public function render($tpl)
        {
            if (!$this->filename)
                $this->filename="piped input";

            foreach (explode("\n",$tpl) as $num=>$line)
            {
                $commands = $blanks = $other = false; 
                foreach (preg_split('/({.+?})/', $line , -1, PREG_SPLIT_DELIM_CAPTURE) as $_)
                {
                    $tokkens[]=$_;    
                    $this->linenumber[count($tokkens)]=array("line"=>$num+1,"tokken"=>$_);
                    
                    if (strlen($_) != 0)
                    {
                        if ((@$_[0] == "{") and (@$_[1] != "$"))
                            $commands = true;
                        elseif ($_ == "")
                            $blanks = true;
                        else
                            $other = true;
                    }
                }
                
                if ($other)
                    $tokkens[]="\n";

            }
            
            $tokkens = $this->intRender($tokkens);
            
            return implode($tokkens);
        }

        public function fetch($tpl)
        {
            $this->filename = $tpl;
            return $this->render(file_get_contents($tpl));
        }

        public function display($tpl)
        {
            print $this->fetch($tpl);
        }
    }
?>
