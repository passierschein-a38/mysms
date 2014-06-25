<?php
#-----------------------------------------------------------------
# sms.at Gateway XML interface class
#-----------------------------------------------------------------

class sms_at {
  
   var $VERSION;
   var $REQUIRED_PHP_VERSION;

   var $account;
   var $account_type;
   var $pass;
   var $url;
   var $content_type;
   var $path;
   var $port;
   var $scheme;
   var $host;
   var $verbose;
   var $last_transfer_id;
   var $last_code;
   var $last_code_description;
   var $last_response;

   var $xml_account_type;
   var $xml_account;
   var $xml_pass;

   function sms_at($account_type, $account, $pass, $url) 
   {
       $this->VERSION              = "0.1";
       $this->REQUIRED_PHP_VERSION = "4.0";
       
       $parsed_url = parse_url($url);
       $host   = $parsed_url['host'];
       $path   = $parsed_url['path'];
       $port   = (isset($parsed_url['port']))? $parsed_url['port'] : '';
       $scheme = $parsed_url['scheme'];
       if (!$port && (in_array($scheme,array('','http'))) ) {
        $port = 80;
       }
       
       if ((strlen($host) < 4) || !$account_type || !$account || !$pass) { 
        exit("Invalid call of sms.at gateway class. Check arguments.\n");
       }
       if (!$port) { 
        exit("Invalid url when calling sms.at gateway class. Mising Port.\n");
       }

       $this->account      = $account;
       $this->account_type = ucfirst(strtolower($account_type));
       $this->pass         = $pass;
       $this->url          = $url;
       $this->content_type = 'text/xml';
       $this->host         = $host;
       $this->path         = $path;
       $this->port         = $port;
       $this->scheme       = $scheme;
       
       $this->xml_account       = $this->str2xml($account);
       $this->xml_pass          = $this->str2xml($pass);
       $this->xml_account_type  = $this->str2xml($this->account_type);

   }
   
   function set_verbose($value) {
    $this->verbose = $value;
   }
   
   
   #--------------------------------------------------------------------------------------------------
   # send_simple_text_sms 
   #
   #   Parameters:
   #    - $recipients  = Can be string: "recipient_address",
   #                     array of strings: ("recipient_address","recipient_address",..), 
   #                     array of arrays:  ((recipient_address,recipient_id),recipient_array,..)
   #                     or mixed array:   (recipient_array, string,..)
   #    - $message     = String. Raw message text in ISO-8859-1
   #    - $autosegment = XML string. "none"     - dont split SMS into parts when text > 160 chars
   #                                 "simple"   - split into parts with 160 characters each
   #                                 "8bitref"  - split into parts using an 8bit reference number
   #                                 "16bitref" - split into parts using a 16bit reference number
   #    - $cod         = Optional confirmation of delivery. 
   #                     0..no delivery notification,
   #                     1..request delivery notification
   #---------------------------------------------------------------------------------------------------
   function send_simple_text_sms($recipients,$message,$autosegment,$cod) {
    
    $alphabet       = ''; # 0..ISO-8859-1 in message (default), 1..8bit binary encoding, 2..16bit UCS2 encoding
    $class          = ''; # 1..write sms to sim (default), 0..dont write sms to sim (Flash Message)
    $id             = ''; # optional Id for message. Will be returned with COD report
    $sender_address = ''; # optional if different from account's default msisdn.
    $sender_type    = ''; # International, National, Shortcode, Alphanumeric
    $segments       = '';
    
    $request = $this->generate_mt_request($alphabet,$class,$id,$sender_address,$sender_type,
                                          $recipients,$segments,$this->str2hex($message),$autosegment,$cod);

    $response                         = $this->send_request($request);
    $this->last_response              = $response;
    list ($resp_header,$resp_content) = preg_split('/\r\n\r\n/', $response);
    
    list ($Code,$CodeDescription,$TransferId) = $this->read_xml_response($resp_content);
    
    $this->last_transfer_id      = $TransferId;
    $this->last_code             = $Code;
    $this->last_code_description = $CodeDescription;
    
    return array ($Code,$CodeDescription,$TransferId);
   }
   
   #--------------------------------------------------------------------------------------------------
   # send_sms 
   #
   #   Parameters:
   #    - $alphabet    = ''; # 0..ISO-8859-1 in message (default), 1..8bit binary encoding, 2..16bit UCS2 encoding
   #    - $class       = ''; # 1..write sms to sim (default), 0..dont write sms to sim (Flash Message)
   #    - $id          = ''; # optional Id for message. Will be returned with COD report
   #    - $sender_address = ''; # optional if different from account's default msisdn.
   #    - $sender_type    = ''; # International, National, Shortcode, Alphanumeric
   #    - $recipients  = Can be string: "recipient_address",
   #                     array of strings: ("recipient_address","recipient_address",..), 
   #                     array of arrays:  ((recipient_address,recipient_id),recipient_array,..)
   #                     or mixed array:   (recipient_array, string,..)
   #    - $segments    =  Can be string: "segment_content", (hex encoded! use sms_at->add_segment())
   #                      array of strings: ("segment_content","segment_content",..), 
   #                      array of arrays:  ((segment_content,segment_header),segment_array,..)
   #                      or mixed array:   (segment_array, string,..)
   #    - $message     = String. Raw message text in ISO-8859-1
   #    - $autosegment = XML string. "none"     - dont split SMS into parts when text > 160 chars
   #                                 "simple"   - split into parts with 160 characters each
   #                                 "8bitref"  - split into parts using an 8bit reference number
   #                                 "16bitref" - split into parts using a 16bit reference number
   #    - $cod         = Optional confirmation of delivery. 
   #                     0..no delivery notification,
   #                     1..request delivery notification
   #---------------------------------------------------------------------------------------------------
   function send_sms($alphabet,$class,$id,$sender_address,$sender_type,
                     $recipients,$segments,$message,$autosegment,$cod) {
    
    $request = $this->generate_mt_request($alphabet,$class,$id,$sender_address,$sender_type,
                                          $recipients,$segments,$this->str2hex($message),$autosegment,$cod);

    $response                         = $this->send_request($request);
    $this->last_response              = $response;
    list ($resp_header,$resp_content) = preg_split('/\r\n\r\n/', $response);
    
    list ($Code,$CodeDescription,$TransferId) = $this->read_xml_response($resp_content);
    
    $this->last_transfer_id      = $TransferId;
    $this->last_code             = $Code;
    $this->last_code_description = $CodeDescription;
    
    return array ($Code,$CodeDescription,$TransferId);
   }
   
   #------------------------------------------------------
   # send_request 
   #   - sends HTTP POST request
   #   - returns HTTP response
   #----------------------------------------------------
   function send_request($request) {
    $fp = fsockopen($this->host,$this->port);
    
    $header  = "POST ".$this->path." HTTP/1.0\r\n";
    $header .= "Content-Type: ".$this->content_type."\r\n";
    $header .= "Host: ".$this->host."\r\n";
    $header .= "Content-Length: ".strlen($request)."\r\n\r\n";

    if ($this->verbose) {echo "\n--HTTP Request--\n$header$request\n--\n";}

    fputs($fp, "$header");
    fputs($fp, "$request");
    
    $response = '';
    
    while(!feof($fp)) {
       $response .= fgets($fp, 128);
    }

    fclose($fp);
    
    if ($this->verbose) {echo "\n--HTTP Response--\n$response\n--\n";}
    
    return $response;
   }
   
   #------------------------------------------------------
   # read_xml_response 
   #   - dirty but sufficient response XML 'parser'
   #     returns empty values for invalid/empty response
   #------------------------------------------------------
   function read_xml_response($content) {
    $Code            = 0;
    $CodeDescription = '';
    $TransferId      = '';
    if (ereg ("<Response>(.*)</Response>", $content, $regs)) {
      $Response_content = $regs[1];
      if (ereg ("<Code>(.*)</Code>", $Response_content, $regs)) {
        $Code = $regs[1];
      }
      if (ereg ("<CodeDescription>(.*)</CodeDescription>", $Response_content, $regs)) {
        $CodeDescription = $regs[1];
      }
      if (ereg ("<TransferId>(.*)</TransferId>", $Response_content, $regs)) {
        $TransferId = $regs[1];
      }
    } 
    return array ($Code,$CodeDescription,$TransferId);
   }

  #--------------------------------------------------
  # generate_mt_request
  #--------------------------------------------------
  function generate_mt_request($alphabet,$class,$message_id,$sender_address,$sender_type,$recipients,$segments,$text,$autosegment,$cod) {
    $request  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
    
    $request .= "<Request>\n";
    $request .= "<AccountLogin Type=\"".$this->account_type."\">". $this->xml_account ."</AccountLogin>\n";
    $request .= "<AccountPass>". $this->xml_pass ."</AccountPass>\n";
    
    $msg_attr_alphabet = ($alphabet != '')   ? " Alphabet=\"$alphabet\"" : "";
    $msg_attr_class    = ($class != '')      ? " Class=\"$class\""       : "";
    $msg_attr_id       = ($message_id != '') ? " Id=\"$message_id\""     : "";
    $request .= "<Message Type=\"MTSMS\"".$msg_attr_alphabet.$msg_attr_class.$msg_attr_id.">\n";
    
    if ($sender_address) {
      $request .= "<Sender Type=\"$sender_type\">".$sender_address."</Sender>\n";
    }
    
    # Recipients
    $request .= "<Recipients>\n";
    if (is_array($recipients)) {
      foreach ($recipients as $recipient) {
        if (is_array($recipient)) {
          $recipient_address = $recipient[0];
          $recipient_id      = $recipient[1];
          $request .= "<Recipient Type=\"International\" Id=\"$recipient_id\">". $recipient_address ."</Recipient>\n";
        } else {
          $request .= "<Recipient Type=\"International\">". $recipient ."</Recipient>\n";
        }
      }
    } else {
      $request .= "<Recipient Type=\"International\">". $recipients ."</Recipient>\n";
    }
    $request .= "</Recipients>\n";
    
    if ($segments) {
      # Data Element
      
      $request .= "<Data>\n";
      
      if (is_array($segments)) {
        foreach ($segments as $segment) {
          if (is_array($segment)) {
            $segment_content = $segment[0];
            $segment_UDH     = $segment[1];
            $request .= "<Segment UDH=\"$segment_UDH\">". $segment_content ."</Segment>\n";
          } else {
            $request .= "<Segment>". $segment ."</Segment>\n";
          }
        }
      } else {
        $request .= "<Segment>". $segments ."</Segment>\n";
      }
      $request .= "</Data>\n";
      
    } else {
      # Text Element 
      $request .= "<Text AutoSegment=\"$autosegment\">". $text ."</Text>\n";
    }
    
    # COD
    $request .= "<Cod>".$cod."</Cod>\n";
    
    $request .= "</Message>\n";
    $request .= "</Request>\n";
    
    return $request;
  } 
  
  #------------------------------------------------------------------------------------
  # add_recipient
  #   - adds recipient to array of recipients.
  #     pushes recipient into given array for multiple recipients (recipients_array_ref) 
  #-------------------------------------------------------------------------------------
  function add_recipient (&$recipients_array_ref,$recipient_address,$recipient_id) {
    $recipient = array(0 => "$recipient_address", 1 => "$recipient_id");
    $recipients_array_ref[] = $recipient;
    return $recipients_array_ref;
  }
  
  #------------------------------------------------------------------------------------
  # add_segment
  #   - adds segment to array of segments.
  #     pushes segment into given array for multiple segments (segments_array_ref) 
    #-------------------------------------------------------------------------------------
  function add_segment (&$segments_array_ref,$segment_content_hex,$segment_UDH_hex) {
    $segment = array(0 => "$segment_content_hex", 1 => "$segment_UDH_hex");
    $segments_array_ref[] = $segment;
    return $segments_array_ref;
  }

   #--------------------------------------------------
   # str2hex - Encodes String to Hex 
   #--------------------------------------------------
   function str2hex($string) {
    $hex = "";
    for ($i = 0; $i < strlen($string); $i++) {
      $hex .= (strlen(dechex(ord($string[$i]))) < 2) ? "0" . dechex(ord($string[$i])) : dechex(ord($string[$i]));
    }
    return $hex;
   }

   #--------------------------------------------------
   # hex2str - Decodes Hex(string) to String
   #--------------------------------------------------
   function hex2str($hex) {
    for($i=0;$i<strlen($hex);$i+=2) {
      $str.=chr(hexdec(substr($hex,$i,2)));
    }
    return $str;
   }

   #--------------------------------------------------
   # str2xml - Escapes non xml (simple)
   #--------------------------------------------------
   function str2xml($string) {

    $string = preg_replace('/&/si' , '&amp;',  $string);
    $string = preg_replace('/\'/si', '&apos;', $string);
    $string = preg_replace('/>/si',  '&gt;',   $string);
    $string = preg_replace('/</si',  '&lt;',   $string);
    $string = preg_replace('/\"/si', '&quot;', $string);

    return $string;
   }

   function used_account() 
   {
       return $this->account;
   }

   function used_account_type() 
   {
       return $this->account_type;
   }

   function used_url() 
   {
       return $this->url;
   }

   function used_content_type() 
   {
       return $this->content_type;
   }
   
   function used_host() 
   {
       return $this->host;
   }
   
   function used_path() 
   {
       return $this->path;
   }
   
   function used_port() 
   {
       return $this->port;
   }
   
   function used_scheme() 
   {
       return $this->scheme;
   }

   function VERSION() 
   {
       return $this->VERSION;
   }

   function last_transfer_id() 
   {
       return $this->last_transfer_id;
   }
   
   function last_code() 
   {
       return $this->last_code;
   }
   
   function last_code_description() 
   {
       return $this->last_code_description;
   }
 
   function last_response() 
   {
       return $this->last_response;
   }
  

} // end of class sms_at

?>