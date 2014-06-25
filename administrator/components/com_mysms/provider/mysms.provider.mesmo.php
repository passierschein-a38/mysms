<?php
/**
* $Id: mysms.provider.mesmo.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_MESMO_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_MESMO_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  MESMO is a sms gateway connection implementation (https://www.any-sms.de)
*
* @package MySMS
* @subpackage Provider
**/

class MESMO extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function MESMO()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'MESMO';
        $this->_file = basename( __FILE__ );

        $this->_params['kundennummer'] =  12345;            //default params
        $this->_params['passwort'] =  'passwort';            //default params

      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
        $text = urlencode( $text );

        //create get string for mesmo
        //$url = 'http://gateway.any-sms.de/send_sms.php?';
        $url = 'send_sms.php?';
        $url .= 'id=' . $this->_params['kundennummer'];
        $url .= '&pass=' . $this->_params['passwort'];
        $url .= '&gateway=30';
        $url .= '&nummer=' . $to;
        $url .= '&absender=' . $from;
        $url .= '&text=' . $text;
        $url .= '&notify=0';
        $url .= '&flash=0';
        $url .= '&xml=0';

        $fp = fsockopen("gateway.any-sms.de", 80 );

        if( !$fp ){
          $errMsg = "Es ist ein Fehler aufgetreten und zwar: keine Socket Verbindung zum Gateway";
          return false;
        }{
          $get = "GET /$url HTTP/1.1\r\n";
          $get .= "Host: gateway.any-sms.de\r\n";
          $get .= "Connection: Close\r\n\r\n";

         if(  fwrite($fp, $get) === false ){
              $errMsg = "Es ist ein Fehler aufgetreten und zwar: Fehler beim schreiben der HTTP Daten";
              return false;
         }

           while(!feof($fp)) {
             $res .= fgets($fp, 128);
           }

           $pos = strpos( $res, 'err:' );
           if( $pos === false ){
               $errMsg = "Es ist ein Fehler aufgetreten und zwar: Server Antwort konnte nicht verarbeitet werden";
               return false;
           }

           $res = substr( $res, $pos, strlen($res) );
           $ar = explode(':', $res );

           if( !isset($ar[1]) ){
             $errMsg = "Es ist ein Fehler aufgetreten und zwar: Server Antwort konnte nicht verarbeitet werden";
             return false;
           }

           $serverRetCode = $ar[1];

           if(  $serverRetCode == 0 ){ //sms send successfully
                return true;
           }

           switch( $serverRetCode )
           {
             case -1:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: ungültige Benutzerkennung";
                 return false;
                 break;
             case -2:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: falsche IP";
                 return false;
                 break;
             case -3:
             case -4:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: kein ausreichendes Guthaben";
                 return false;
                 break;
             case -5:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: SMS konnte nicht verschickt werden";
                 return false;
                 break;
             case -6:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: Gateway in dieses Netz nicht verfügbar";
                 return false;
                 break;
             case -7:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: Handynummer zu kurz oder zu lang";
                 return false;
                 break;
             case -9:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: Spamsprerre";
                 return false;
                 break;
             case -18:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: fehlende Preisangabe";
                 return false;
                 break;
             default:
                 $errMsg = "Es ist ein Fehler aufgetreten und zwar: unbekannter Fehler";
                 return false;
             }   //end switch
           }
           fclose($fp);
        }//end send sms

} //end class aspsms
?>