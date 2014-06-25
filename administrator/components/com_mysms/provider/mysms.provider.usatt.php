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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.usatt.php $
*
* $Id: mysms.provider.usatt.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_USATT_PHP' ) == true )
{
  return;
}


/**
 * require our base class
 */
require_once('provider.php');

/**
*  AT&T is a sms gateway connection implementation
*
* @package MySMS
* @subpackage Provider
**/

class USATT extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function USATT()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'USATT';
        $this->_file = basename( __FILE__ );
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg )
      {      	
      		$email = sprintf( "%s@txt.att.net", $to );
      		
      		$config =& JFactory::getConfig();
      		$mailfrom = $config->getValue( 'mailfrom' );
      		
      		$header = 'From: ' . $mailfrom . "\r\n" .    				  
    				  'X-Mailer: com_mysms';
      		
      		$msg  = $text;
      		
      		$footer = "\r\n\r\n";
      		$footer .= "send by: " . $from;
      		
      		$body = $msg . $footer;      		
      		
      		$retcode = mail( $email, '', $body, $header );
      		
      		if( $retcode == false )
      		{
      			$errMsg = JText::sprintf( 'MYSMS_SEND_SMS_FAILED' , $to, $errMsg );
      			return false;		
      		}
      		    		
      		return true;   		      	        			
  }//end send sms
} //end class USATT
?>