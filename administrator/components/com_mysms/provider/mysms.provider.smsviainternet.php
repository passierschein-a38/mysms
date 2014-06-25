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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.smsviainternet.php $
*
* $Id: mysms.provider.smsviainternet.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMSVIAINTERNET_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSVIAINTERNET_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');

/**
*  SMSVIAINTERNET is a sms gateway connection implementation (https://www.SMSVIAINTERNET.it)
*
* @package MySMS
* @subpackage Provider
**/

class SMSVIAINTERNET extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSVIAINTERNET()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSVIAINTERNET';
        $this->_file = basename( __FILE__ );

        $this->_params['smstype']		=  'file.sms';            //default params
        $this->_params['smsgateway'] 	=  'H';            		//default params
		$this->_params['smsuser'] 		=  'user';            	//default params
		$this->_params['smspassword']	=  'password';            //default params
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg )
      {
        $user 		=  &$this->_params['smsuser'];
        $pw 		=  &$this->_params['smspassword'];
        $gw   		=  &$this->_params['smsgateway'];
        $type	 	=  &$this->_params['smstype'];

        
       // http://77.93.254.105/post/multisms/send.php
        
        $host = '77.93.254.105';
        $fp = fsockopen( $host, 80 );
        
        if( $fp == false )
        {
        	$errMsg = JText::_( 'MYSMS_ERROR' ) . ':  Network';
        	return false;
        }
        
        // we cannot use the buildQuery method because the server side does not understand &amp; :/
        $payload = "smsTEXT=$text&smsNUMBER=$to&smsSENDER=$from&smsTYPE$type&smsGATEWAY&$gw&smsUSER=$user&smsPASSWORD=$pw";

        
        //do it the old way because the server send 2 http status codes
        
        /*
         * Array
(
    [0] => HTTP/1.1 100 Continue
    [1] => Server: SMSDriver POST/1.0
    [2] => Date: mar, 31 mar 2009 18:52:30  GMT
    [3] => 
    [4] => HTTP/1.1 200 OK
    [5] => Server: SMSDriver POST/1.0
    [6] => Date: mar, 31 mar 2009 18:52:30  GMT
    [7] => Content-Length: 130
    [8] => Content-Type: text/html
    [9] => Set-Cookie: ASPSESSIONIDACCSTDCT=MDKLNEGAJIIHAMFJIDBLNBIM; path=/
    [10] => Cache-control: private
    [11] => 
    [12] => 
    [13] => 
    [14] => 
    [15] => 
    [16] => [17] => 
    [18] => -Err 007 Message not enabled
    [19] => [20] => [21] => 
)
         */
        fputs($fp, "POST /post/multisms/send.php HTTP/1.1\n");
        fputs($fp, "Host: $host\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: ".strlen($payload)."\n");
        fputs($fp, "Connection: close\n\n");
        fputs($fp, "$payload\n");
        
         while( !feof( $fp ) ) 
         {
         	$res .= fgets($fp, 128);
         }
         
         fclose($fp);       
         
         $data = strip_tags( $res );

         if( eregi( '-Err', $data ) )
         {
         	$data = explode( "\r\n", $data );
         	
         	foreach ( $data as $line )
         	{
         		
         		if( eregi( '-Err', $line ) )
         		{
         			$errMsg = JText::_( 'MYSMS_ERROR' ) . $line;
         			return false;
         		}
         	}
         	
         	 $errMsg = JText::_( 'MYSMS_ERROR' ) . ':  Provider';
         	 return false;
         }
         
		return true;
  }//end send sms
} //end class AGILETELECOM
?>