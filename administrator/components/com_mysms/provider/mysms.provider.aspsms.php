<?php
/**
* $Id: mysms.provider.aspsms.php 199 2009-12-25 15:35:32Z axel $
*
* @author Axel Sauerhöfer 
* @copyright Copyright &copy; 2008, Axel Sauerhöfer
* @version 1.5.6
* @email mysms[at]quelloffen.com
* @package MySMS
*
* All rights reserved.  MySMS Component for Joomla!
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* MySMS! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*
**/
//check if joomla call us
defined( '_JEXEC' ) or die( 'Restricted access' );

if( defined( 'MYSMS_PROVIDER_ASPSMS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_ASPSMS_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');

/**
*  ASPSMS is a sms gateway connection implementation (http://www.aspsms.com)
*
* @package MySMS
* @subpackage Provider
**/

class ASPSMS extends Provider
{


   /**
   *  Constructor, setting up name, file and parameters
   */
      function ASPSMS()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'ASPSMS';
        $this->_file = basename( __FILE__ );

        //the servers not params, they are not changeable
        $this->_servers = array( 'xml1.aspsms.com:5061','xml1.aspsms.com:5098','xml2.aspsms.com:5061','xml2.aspsms.com:5098' );
        $this->_params['userkey'] = 'myuserkey';   //default params
        $this->_params['password'] = 'mypassword';   //default params
      }


    /**
   *  The PostToHost is for sending the data to the sms gateway
   *  @param string $xml
   */

      function PostToHost( $xml )
      {
      	
        if( is_array($this->_servers) && count($this->_servers > 0) )
        {

            //try all servers in list, this is recommended by provider
            foreach($this->_servers as $server ){
                 $s = explode(':', $server);
                 $fp =  fsockopen($s[0],$s[1]);

                 if( !$fp ){
                     continue; //try next server
                 }
                 //send post data
                 fputs($fp, "POST /xmlsvr.asp HTTP/1.0\r\n");
                 fputs($fp, "Content-Type: text/xml\r\n");
                 fputs($fp, "Content-Length:".strlen($xml)."\r\n");
                 fputs($fp, "\r\n");
                 fputs($fp,$xml);

                 //read out result
                 while(!feof($fp)) {
                      $res .= fgets($fp, 1024);
                 }

                 //close connection
                 fclose($fp);
                 return $res;
          }//foreach
        }else{
             echo "<script> alert('com_mysms (aspsms) --> no aspsms server found'); window.history.go(-1); </script>\n";
             exit();
        }//server array check

        //if our code is here we can't open a connection to a server
       if (!$fp) {
         echo "<script> alert('com_mysms (aspsms) --> socket error'); window.history.go(-1); </script>\n";
         exit();
       }

      } //end function

   /**
   *  The parseResult is for parsing the response message from aspsms server
   *  The response is in xml format
   *  @param string $result
   *  @param int $retCode
   *  @param string $retDesc
   */

      function parseResult(&$result, &$retCode, &$retDesc)
      {

        //remove http header from response
        $pos = strpos( $result, '<aspsms>');

        //if we didn't find a <aspsms> tag something wrong
        if( $pos === false ){
            echo "<script> alert('com_mysms (aspsms) --> http response error'); window.history.go(-1); </script>\n";
            exit();
        }

        $result = substr( $result, $pos, strlen($result) );

        $parser = xml_parser_create ();
        $r = xml_parse_into_struct($parser, $result, $vals, $index);

        if( $r == 0 ){ //parse failed
           echo "<script> alert('com_mysms (aspsms) --> xml parser error'); window.history.go(-1); </script>\n";
           exit();
        }

        foreach($index as $key=>$val )
        {
           switch($key)
           {
              case 'ASPSMS': // not needed
                   continue;
              case 'ERRORCODE':
                    $retCode =   $vals[$val[0]]['value'];
                    break;
              case 'PARSERERRORCODE':
                   $retCode =   $vals[$val[0]]['value'];
                    break;
              case 'PARSERERRORDESCRIPTION':
                    $retDesc =   $vals[$val[0]]['value'];
                    break;
              case 'ERRORDESCRIPTION':
                    $retDesc =   $vals[$val[0]]['value'];
                    break;
           } //switch
       }//foreach
      }//end function


      function umlautFilter(&$string)
      {
        $ar = array("ä"=>"&#228;", "ö"=>"&#246;", "ü"=>"&#252;", "ß"=>"&#223;",
                    "Ä"=>"&#196;", "Ö"=>"&#214;", "Ü"=>"&#220;", "À"=>"&#192;",
                    "È"=>"&#200;", "É"=>"&#201;", "Ê"=>"&#202;", "è"=>"&#232;",
                    "é"=>"&#233;", "ê"=>"&#234;", "à"=>"&#224;", "ï"=>"&#239;",
                    "ù"=>"&#249;", "û"=>"&#251;", "ü"=>"&#252;", "ç"=>"&#231;",
                    "Æ"=>"&#198;", "?"=>"&#330;", "Œ"=>"&#338;", "œ"=>"&#339;",
                    "€"=>"&#8364;","«"=>"&#171;", "»"=>"&#187;");


        foreach($ar as $key=>$val){
           $string = str_replace( $key, $val, $string );
        }
        echo $string;
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text,  $from,  $to, &$errMsg)
      {

		$text = utf8_decode( $text );
	  
         //do create xml and send this over gateway


         // aspsms specification wrote that me mus changed our entities, but if we do this, the text in sms shows the converted values
         // if we don't change the entities the sms is correct, maybe a bug in gateway, send email to sms provider

         //first change german special charactes (umlaute)
         // $this->umlautFilter( $text );

         //remove special characters, gateway strips the message
         //$text = htmlentities( strip_tags($text), ENT_QUOTES );

         $xml  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n";
         $xml .= "<aspsms>\r\n";
         $xml .= "  <Userkey>" . $this->_params['userkey'] . "</Userkey>\r\n";
         $xml .= "  <Password>" . $this->_params['password'] . "</Password>\r\n";
         $xml .= "  <Originator>" . $from . "</Originator>\r\n";
         $xml .= "  <Recipient>\r\n";
         $xml .= "    <PhoneNumber>" . $to . "</PhoneNumber>\r\n";
         $xml .= "  </Recipient>\r\n";
         $xml .= "  <MessageData>" . $text . "</MessageData>\r\n";
         $xml .= "  <Action>SendTextSMS</Action>\r\n";
         $xml .= "</aspsms>\r\n";

         $ret = $this->PostToHost($xml);
         $this->parseResult($ret, $retCode, $retDesc);

         if( $retCode == 1 )
         { //sendind ok
          	return true;
         }

         //sending failed
          $errMsg = "Es ist ein Fehler aufgetreten und zwar: " . $retDesc;
          return false;
          
         return false;
      }

} //end class aspsms
?>