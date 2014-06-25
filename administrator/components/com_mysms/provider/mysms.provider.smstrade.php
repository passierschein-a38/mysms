<?php
/**
* $Id$
*
* @author Axel Sauerhöfer 
* @copyright Copyright &copy; 2008, Axel Sauerhöfer
* @version 1.5.7
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

if( defined( 'MYSMS_PROVIDER_SMSTRADE_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSTRADE_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMSGLOBAL is a sms gateway connection implementation (https://www.coolsms.com)
*
* @package MySMS
* @subpackage Provider
**/

class SMSTRADE extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSTRADE()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSTRADE';
        $this->_file = basename( __FILE__ );

        $this->_params['key'] =  12345;        	 //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
      	
        $text_copy = utf8_decode( $text );
        
        //get all params
        $key 	   = $this->_params['key'];
        

        $data = array(	'key'			=> $key,
              			'message'		=> $text_copy,
              			'from' 			=> $from,
        				'to'			=> $to,
        				'route'			=> 'direct' );

		$payload = $this->buildQuery( $data );
				
		$host = 'gateway.smstrade.de';
        $fp = fsockopen($host,80);

        if (!$fp)
        {
        	$errMsg = JText::_( 'MYSMS_ERROR' ) . ':  Network';
        	return false;
        }
        
        @fclose( $fp );

        $url = 'http://gateway.smstrade.de?' . $payload;
        
		$response = file( $url );
		
		if( 100 == $response[0] )
		{
			return true;
		}
			

		//Array mit ResonseCode
		$response_code_arr[0] = "Keine Verbindung zum Gateway";
		$response_code_arr[10] = "Empfänger fehlerhaft";
		$response_code_arr[20] = "Absenderkennung zu lang";
		$response_code_arr[30] = "Nachrichtentext zu lang";
		$response_code_arr[31] = "Messagetyp nicht korrekt";
		$response_code_arr[40] = "Falscher SMS-Typ";
		$response_code_arr[50] = "Fehler bei Login";
		$response_code_arr[60] = "Guthaben zu gering";
		$response_code_arr[70] = "Netz wird von Route nicht unterstützt";
		$response_code_arr[71] = "Feature nicht über diese Route möglich";
		$response_code_arr[80] = "SMS konnte nicht versendet werden";
		$response_code_arr[90] = "Versand nicht möglich";
		$response_code_arr[100] = "SMS wurde erfolgreich versendet.";
		
		$errMsg = $response_code_arr[ $response[0] ];
			
		return false;
		       
      }//end send sms

} //end class SMSTRADE
?>