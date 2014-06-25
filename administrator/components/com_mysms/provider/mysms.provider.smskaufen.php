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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.smskaufen.php $
*
* $Id: mysms.provider.smskaufen.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMSKAUFEN_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSKAUFEN_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMSKAUFEN is a sms gateway connection implementation (https://www.smskaufen.de)
*
* @package MySMS
* @subpackage Provider
**/

class SMSKAUFEN extends Provider
{
   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSKAUFEN()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSKAUFEN';
        $this->_file = basename( __FILE__ );

        $this->_params['Id'] 		=  12345;            //default params
        $this->_params['Passwort']  =  'passwort';            //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
        $id =  &$this->_params['Id'];
        $pw =  &$this->_params['Passwort'];

        $data = array(	'id'	=> $this->_params['Id'],
              			'pw'	=> $this->_params['Passwort'],
              			'text'	=> $text,
              			'type'	=> 13,
              			'empfaenger' => $to,
              			'absender'	=> $from );


        $query = 'sms/gateway/sms.php?';
		$query .= $this->buildQuery($data);

        //create socket
        $fp = fsockopen('www.smskaufen.com', 80, $errno, $errstr, 10);

        //check socket
        if( !$fp ){
             echo "<script> alert('com_mysms (teleword) --> socket error'); window.history.go(-1); </script>\n";
             exit();
        }

        $get  = "GET /$query HTTP/1.1\r\n";
        $get .= "Host: www.smskaufen.com\r\n";
        $get .= "Connection: Close\r\n\r\n";

         if(  fwrite($fp, $get) === false ){
              $errMsg = "Es ist ein Fehler aufgetreten und zwar: Fehler beim schreiben der HTTP Daten";
              return false;
         }

         while(!feof($fp)) {
            $res .= fgets($fp, 1024);
         }

        //close socket
        fclose($fp);
   
        if( eregi('100', $res) )
        {
          return true;
        }

        return false;
      }//end send sms

} //end class smskaufen
?>