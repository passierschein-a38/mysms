<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 184 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.teleword.php $
*
* $Id: mysms.provider.teleword.php 184 2009-12-11 17:06:40Z axel $
*
* All rights reserved. 
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

if( defined( 'MYSMS_PROVIDER_TELEWORD_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_TELEWORD_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  TELEWORD is a sms gateway connection implementation (https://www.teleword.de)
*
* @package MySMS
* @subpackage Provider
**/

class TELEWORD extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function TELEWORD()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'TELEWORD';
        $this->_file = 'teleword.php'; //maybe use __FILE__ in next version

        $this->_params['Account'] =  12345;            //default params
        $this->_params['Passwort'] =  'passwort';            //default params
        $this->_params['Sprache'] =  'DE'; //or UK           //default params
        $this->_params['AktionsNummer'] = 1;
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
        $text = rawurlencode( $text );

        //get all params
        $telewordlanguage = $this->_params['Sprache'];
        $telewordaccount  = $this->_params['Account'];
        $telewordaction   = $this->_params['AktionsNummer'];
        $telewordpassword = $this->_params['Passwort'];
        $sender           = $from;
        $num              = $to;
        $xms              = $text;
        $mobile           = 'SMS';

         //create message data
        $query="telewordlanguage=$telewordlanguage&telewordaccount=$telewordaccount".
        "&telewordaction=$telewordaction&sender=$sender&num=$num&mobile=$mobile&xms=$xms&teleword=$telewordpassword";

        //create socket
        $fp = fsockopen('www.teleword.net', 80, $teleword_errno, $teleword_errstr, 10);

        //check socket
        if( !$fp ){
             echo "<script> alert('com_mysms (teleword) --> socket error'); window.history.go(-1); </script>\n";
             exit();
        }


        //send message data
        fputs($fp, "POST /action/ HTTP/1.0\n");
        fputs($fp, "Host: www.teleword.net\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Connection: close\n");
        fputs($fp, "Content-length: ".strlen($query)."\n\n$query");

        //check response
         while(!feof($fp)){
            $response .= fgets($fp,128);
         }

         //close socket
         fclose($fp);

         //encode text before saving in db !!!
        $text = urldecode  ( $text );
        $text = htmlentities( strip_tags($text), ENT_QUOTES );

         preg_match_all("/(TeleWord\-([A-Za-z0-9\_\-]+): +([^\n\r]+))/s",$response,$matches);

          for ($i=0; $i<count($matches[0]); $i++) {
            $teleword[strtolower($matches[2][$i])]=$matches[3][$i];
          }

          foreach (array_keys($teleword) as $key) {
              $teleword[$key]=urldecode($teleword[$key]);
          }

          if (preg_match("/^[A-Za-z]{2}$/", $teleword['language'])) {
              $teleword['language']=$telewordlanguage;
          }

          if ($teleword['error'] == '') {
               $teleword['language']    =$telewordlanguage;
               $teleword['error']       ='SERVER';
               $teleword['errortitleuk']='SERVER ERROR';
               $teleword['errortitlede']='SERVER FEHLER';
               $teleword['errortextuk'] ='There is no confirmation message and no error message.';
               $teleword['errortextde'] ='Es liegt weder eine Erfolgs- noch eine Fehlermeldung vor.';
          }


          if (preg_match("/SUCCESS/i", $teleword{'error'})) {
             return true;
          }else {
                $teleword['language']=strtolower($teleword['language']);

                $errMsg = "Es ist ein Fehler aufgetreten und zwar: " . $teleword['error'] . "<br/>";
                $errMsg .= $teleword['errortitle'.$teleword['language']]. "<br/>";
                $errMsg .= $teleword['errortext'. $teleword['language']].  "<br/>";
                return false;
          }

        return false;

      }//end send sms

} //end class teleword
?>