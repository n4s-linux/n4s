<?php

class Imap
{
    private $stream;
    private $mbox;
    private $is_connected = 0;

    private $host = "imap.gmail.com";
    private $username = "";
    private $password = "";
    private $port = "993";
    private $tls = "ssl"; //"notls"

    function __construct($stream)
    {
        $this->stream = $stream;
        if ($this->stream != false) {
            $this->is_connected = 1;
        }
    }

    /**
     * returnEmailHtml
     * This returns html content of email or plain content if there is no html
     * @param messageNumber
     * @return string
     */
    public function returnEmailHtml($messageNumber)
    {
        $msg = $this->returnEmailWithAttachments($messageNumber);
        
        return $msg['html'];
    }

    /**
     * returnAttachmentsArr
     * This returns array of attachments names and data
     * @param messageNumber
     * @return array
     */
    public function returnAttachmentsArr($messageNumber)
    {
        $msg = $this->returnEmailWithAttachments($messageNumber);

        return $ret['attachments'];
    }
    
    /**
     * returnAttachmentsArr
     * This returns array of attachments names and data
     * @param messageNumber
     * @return array
     */
    public function returnEmailWithAttachments($messageNumber)
    {
    	$msg = $this->returnEmailMessageArr($messageNumber, 1);
    
    	if (!empty($msg)) {
    		if(isset($msg['attachments']) && is_array($msg['attachments'])){
    			foreach($msg['attachments'] as $att){
    				$attachments[] = array('filename' => $att['name'], 'data' => $att['data']);
    			}
    			if (!isset($attachments))
    				$ret = null;
    			else
    				$ret = $attachments;
    		} else {
    			$ret = null;
    		}
    	} else {
    		$ret = null;
    	}
    	
    	if (!empty($msg)) {
    		if (empty($msg['plain']))
    			$msg['plain'] = "";
    		$html = (!empty($msg['html'])) ? $msg['html'] : $msg['plain'];
    	} else {
    		$html = "There is no message with this number";
    	}
    
    	return array('html' => $html, 'attachments'=>$ret);
    }
    

    /**
     * returnEmailHeaderArr
     * This returns detailed header information for the given message number
     * @param messageNumber
     * @return array
     */
    public function returnEmailHeaderArr($messageNumber)
    {
        $head = $this->returnHeaderInfoObj($messageNumber);
        $array['date'] = $head->date;
        $array['subject'] = (isset($head->subject)) ? $head->subject : "";;
        $array['to'] = $head->toaddress;
        $array['message_id'] = $head->message_id;
        $array['from'] = $head->from[0]->mailbox . '@' . $head->from[0]->host;
        $array['reply_toaddress'] = $head->reply_toaddress;
        $array['size'] = $head->Size;
        $array['msgno'] = $head->Msgno;

        if ($head->Unseen == 'U') {
            $array['status'] = 'Unread';
        } else {
            $array['status'] = 'Read';
        }
        return $array;
    }

    /**
     * returnEmailMessageArr
     * This returns the entire email for the given message number
     * @param  $messageNumber (int), $withEncodedAttachment (bool)
     * @return array
     */
    public function returnEmailMessageArr($messageNumber, $withEncodedAttachment = 0)
    {
        $array = array();
        $attachments = 0;
        $o = $this->returnMessageStructureObj($messageNumber);
        if (is_object($o)) {
            $array['header'] = $this->returnEmailHeaderArr($messageNumber);
            if (isset($o->parts) && is_array($o->parts)) {
                $array['attachments'] = array();
                // first build plain and/or html part of array
                foreach ($o->parts as $x => $i) {
                    if (isset($i->parts) && is_array($i->parts)) {
                        foreach ($i->parts as $j => $k) {
                            if ($k->subtype == 'PLAIN') {
                                $array['plain'] = $this->returnBodyStr($messageNumber, '1.1');
                            } else if ($k->subtype == 'HTML') {
                                $array['html'] = $this->returnBodyStr($messageNumber, '1.2');
                            }
                            else if(isset($k->disposition) && $k->disposition == 'ATTACHMENT'){
                                $attachments++;
                            }
                        }
                    } else {
                        if(isset($i->disposition)&& $i->disposition == 'ATTACHMENT'){
                            $attachments++;

                            if (is_array($i->parameters)) {
                                $name = ($i->parameters[0]->attribute == 'NAME')?$i->parameters[0]->value:$i->parameters[1]->value;
                            }
                            else $name = "";

                            $array['attachments'][] = array(
                                'type'=>$i->subtype,
                                'bytes'=>$i->bytes,
                                'name'=>$name,
                                'part'=>"2"
                            );
                        }
                        else if ($i->subtype == 'PLAIN') {
                            $array['plain'] = $this->returnBodyStr($messageNumber, '1');
                        } else if ($i->subtype == 'HTML') {
                            $array['html'] = $this->returnBodyStr($messageNumber, '2');
                        }

                    }

                    if($attachments > 1){
                        $array['attachments'] = array();
                        foreach($o->parts as $x => $i)
                        {
                            if(isset($i->disposition) && $i->disposition == 'ATTACHMENT')
                            {
                                $part = $x+1;
                                $name = ($i->parameters[0]->attribute == 'NAME')?$i->parameters[0]->value:$i->parameters[1]->value;
                                $array['attachments'][] = array(
                                    'type'=>$i->subtype,
                                    'bytes'=>$i->bytes,
                                    'name'=>$name,
                                    'part'=>$part,
                                    'msgno'=>$messageNumber
                                );
                            }
                        }
                    }

                }
                if($withEncodedAttachment){
                    if (!empty($array['attachments']))
                        $array['attachments'] = $this->returnAttachmentsData($messageNumber, $array['attachments']);
                }
            } // simple plain text email
            else if ($o->subtype == 'PLAIN') {
                $array['plain'] = $this->returnBodyStr($messageNumber, '1');
            } else {
                $array['error'][] = 'Error encountered parsing email';
            }
        } else { // report error

        }
        return $array;
    }


    /**
     * returnAttachmentsData
     * @param messageNumber(int), attachemnts(array)
     * @return array
     */
    public function returnAttachmentsData($messageNumber, $attachments)
    {
        if(is_array($attachments))
        {
            foreach($attachments as $k => $i){
                $attachments[$k]['data'] = base64_decode($this->returnBodyStr($messageNumber,$i['part']));
            }
            return $attachments;
        }
        return '';
    }

    /** * returnHeaderInfoObj
     * @see http://www.php.net/manual/en/function.imap-headerinfo.php
     * @param void
     * @return object
     */
    private function returnHeaderInfoObj($messageNumber)
    {
        return $this->header;
    }

    /**
     * returnMessageStructureObj
     * @see http://www.php.net/manual/en/function.imap-fetchstructure.php
     * @param unknown_type $messageNumber
     * @return object
     */
    private function returnMessageStructureObj($messageNumber)
    {
        return imap_fetchstructure($this->stream, $messageNumber);
    }

    /**
     * returnBodyStr
     * @see http://www.php.net/manual/en/function.imap-fetchbody.php
     * @param $messageNumber (int),part(int)
     * @return string
     */
    private function returnBodyStr($messageNumber, $section)
    {
        return imap_fetchbody($this->stream, $messageNumber, $section);
    }

}

?>
