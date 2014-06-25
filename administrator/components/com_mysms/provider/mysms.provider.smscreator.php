<?php
/**
* $Id: mysms.provider.smscreator.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMSCREATOR_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSCREATOR_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMSCreator is a sms gateway connection implementation (https://www.smscreator.de)
*
* @package MySMS
* @subpackage Provider
**/

class SMSCREATOR extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSCREATOR()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSCREATOR';
        $this->_file = basename( __FILE__ );

        $this->_params['user'] =  'test';            //default params
        $this->_params['passwort'] =  'test';            //default params
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text,  $from, $to, &$errMsg )
      {
      	//all passed data are utf-8 encoded, so we must decode the data
      	$text = utf8_decode( $text );
      	
         $data  = "User=" . rawurlencode( $this->_params['user'] );
         $data .= "&Password=" . rawurlencode( $this->_params['passwort'] );
         
       //create send date time like this 22.10.2006 10:06:17
       //  $dt = rawurlencode( date( "d.m.Y H:i:s" ) );

       //take  a date from past, maybe server has another time settings like your joomla host, who creates the time
       $dt =  rawurlencode("22.10.2005 10:06:17");

         $data .= "&sendDate=$dt";
         $data .= "&SmsTyp=18";
         $data .= "&Caption=" . rawurlencode( substr( $text, 0, 10 ) );                  
         $data .= "&Sender=" . rawurlencode($from);
         $data .= "&Recipient=" . rawurlencode($to);
         $data .= "&SMSText=" . rawurlencode($text);
         
 
         $path = "/send.asmx/SendText";
         $host = "soap.smscreator.de";
         
          $fp = fsockopen( $host, 80 );

          if( !$fp )
          {
              $errMsg = "Es ist ein Fehler aufgetreten und zwar: keine Socket Verbindung zum Gateway";
              return false;
          }
          
          fputs( $fp, "POST $path HTTP/1.1\r\n" ); 
		  fputs( $fp, "Host: $host\r\n" ); 
		  fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" ); 
	      fputs( $fp, "Content-length: ". strlen( $data ) ."\r\n" ); 
		  fputs( $fp, "Connection: close\r\n\r\n" ); 
		  fputs( $fp, $data ); 

		  while( !feof( $fp ) ) 
		  { 
			$res .= fgets($fp, 128); 
		  } 
 
           @fclose($fp);

              //check errors
              if( eregi("500 Internal Server Error", $res) )
              {
                $errMsg = JText::_( 'MYSMS_ERROR' ) . ' 500 server error';
                return false;
              }

              $iStart = strpos($res,"SendSMS\">") + 9;
              $iEnde = strpos($res,">",$iStart);

              $s = substr($res,$iStart,$iEnde-$iStart);

              if( eregi( "ok", $s ) )
              {
                return true;
              }else
              {
                $errMsg = $s;
                return false;
              }
              
              return true;
          
          //{
             /*$url ="/send.asmx/SendText?";
             $url .= $data;
             $get = "GET $url HTTP/1.1\r\n";
             $get .= "Host: soap.smscreator.de\r\n";
             $get .= "Connection: Close\r\n\r\n";

             if( fwrite( $fp, $get ) === false )
             {
                    $errMsg = "Es ist ein Fehler aufgetreten und zwar: Fehler beim schreiben der HTTP Daten";
                    return false;
              }

              while( !feof( $fp ) ) 
              {
                 $res .= fgets($fp, 128);
              }

              @fclose($fp);

              //check erros
              if( eregi("500 Internal Server Error", $res) )
              {
                $errMsg = "Internal Server Error";
                return false;
              }

              $iStart = strpos($res,"SendSMS\">") + 9;
              $iEnde = strpos($res,">",$iStart);

              $s = substr($res,$iStart,$iEnde-$iStart);

              if( eregi( "ok", $s ) )
              {
                return true;
              }else
              {
                $errMsg = $s;
                return false;
              }*/
           //}
        }//end send sms
} //end class aspsms
?>
