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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.w2sms.php $
*
* $Id: mysms.provider.w2sms.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_W2SMS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_W2SMS_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  W2SMS is a sms gateway connection implementation (http://www.w2sms.de)
*
* @package MySMS
* @subpackage Provider
**/

class W2SMS extends Provider
{


   /**
   *  Constructor, setting up name, file and parameters
   */
      function W2SMS()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'W2SMS';
        $this->_file = basename( __FILE__ );
        $this->_params['host'] =  "sms.w2sms.com";   //default params
        $this->_params['path'] =  "/smsgate.cgi";   //default params
        $this->_params['login'] =  "user";          //default params
        $this->_params['password'] =  "password";   //default params
        $this->_params['timeout'] =  30;            //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
   function sendSms( $text, $from, $to, &$errMsg)
   {
       
        $text = urlencode ( $text );

       $login =  &$this->_params['login'];
       $pw    =  &$this->_params['password'];

       //create correct data
       $data = "action=sms_form_send&login=$login&password=$pw&" .
               "Dest=$to&Body=$text&Org=$from";

        $host =  $this->_params['host'];
        $path =  $this->_params['path'];


        //post data to host e.g. send the sms
        $x = $this->PostToHost( $host, $path, "", $data );

        $text = urldecode  ( $text );
        $text = htmlentities( strip_tags($text), ENT_QUOTES );

        //checkup return data
        $result["httpstat"]       = "-";
        preg_match("/Sms-Status: (\d)/i",$x,$matches);
        $result["sms_status"]     = $matches[1];
        preg_match("/Sms-Error-No: (\d+)/i",$x,$matches);
        $result["sms_error_no"]   = $matches[1];
        preg_match("/Sms-Error-Text: (.*?)\n/i",$x,$matches);
        $result["sms_error_text"] = $matches[1];

       /*Debug echo "httpstat: "       . $result["httpstat"]       . "\n";
        echo "sms_status: "     . $result["sms_status"]     . "\n";
        echo "sms_error_no: "   . $result["sms_error_no"]   . "\n";
        echo "sms_error_text: " . $result["sms_error_text"] ."\n";*/

        if ( $result["sms_status"] == 1 ) {
           return true;
        } else {
          $errMsg = "Es ist ein Fehler aufgetreten und zwar: " . $result["sms_error_text"];
          return false;
        }

      }    //end function  sendSms

    /**
   *  The PostToHost is for sending the data to the sms gateway
   *  @param string $host
   *  @param string $path
   *  @param string $referer
   *  @param string $data_to_send
   */

      function PostToHost($host, $path, $referer, $data_to_send) {

         //simple http post
         $fp = fsockopen($host,80);
         if (!$fp) {
            echo "<script> alert('com_mysms (w2sms) --> socket error'); window.history.go(-1); </script>\n";
            exit();
         }
         fputs($fp, "POST $path HTTP/1.1\n");
         fputs($fp, "Host: $host\n");
         fputs($fp, "Referer: $referer\n");
         fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
         fputs($fp, "Content-length: ".strlen($data_to_send)."\n");
         fputs($fp, "Connection: close\n\n");
         fputs($fp, "$data_to_send\n");
         while(!feof($fp)) {
             $res .= fgets($fp, 128);
         }
         fclose($fp);
          return $res;
       }
} //end class w2sms
?>