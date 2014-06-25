<?php
/**
* $Id: mysms.provider.smscom.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMSCOM_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSCOM_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMSCom is a sms gateway connection implementation http://web.smscom.se/sendsms.aspx 
*
* @package MySMS
* @subpackage Provider
**/

class SMSCOM extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSCOM()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSCOM';
        $this->_file = basename( __FILE__ );

        $this->_params['account']  =  12345;            //default params
        $this->_params['password'] =  '12345';            //default params
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
        $text = utf8_decode( $text );
        
        $account 	    = $this->_params['account'];
        $password  		= $this->_params['password'];
                
        $data = array( 'acc'  => $account,
        			   'pass' => $password, 
        			   'msg'  => $text,
        			   'to'   => $to,
        			   'from' => $from,
        			   'prio'  => 2 );
        
        $host = 'http://web.smscom.se/sendsms.aspx?';
        	
        
        $query = '';
        
        foreach( $data as $key => $val )
        {
			$query .= urlencode( $key ) . '=' . urlencode( $val ) . '&';
        }
        
        $host .= substr( $query, 0, strlen($query)-1);
        $response = file_get_contents( $host );

        /**  	
			0 	Levererat till gateway 	Delivered to gateway
			1 	Inloggning mot gatewayen misslyckades 	Log into to gateway failed
			2 	Problem med meddelandet 	Problem with the message
			3 	Felaktig formatering på mobilnumret 	Incorrect formatting of the mobile number
			4 	Kredittäckning saknas 	Insufficient balance on account
			10 	Mottagen av gateway 	Received by the gateway
			11 	Leverans fördröjd 	Delivery is delayed
			21 	Levererad till GSM-nätet 	Delived to the GSM network
			22 	Levererat till mobiltelefonen 	Delivered to mobile phone
			30 	Kredittäckning saknas 	No credit
			41 	Innehållsfel i meddelandet 	Content error in message
			42 	Internt fel 	Internal error
			43 	Meddelande utgått 	Message has expired
			50 	Allmänt leveransfel 	General delivery error
			51 	Leveransfel till GSM-nät 	Delivery error to GSM network
			52 	Leveransfel till mobiltelefon 	Delivery error to mobile phone
			100 	Kredittäckning saknas 	No credit
			101 	Felaktigt konto uppgift 	Incorrect account details
			110 	Parameter fel 	Incorrect parameter
          **/

        $code = (int)$response;

        $gatewayCodes = array(  0 => 'Delivered to gateway',
         					1 => 'Log into to gateway failed',
         					2 => 'Problem with the message',
         					3 => 'Incorrect formatting of the mobile number',
         					4 => 'Insufficient balance on account',
         					10 => 'Received by the gateway',
         					11 => 'Delivery is delayed',
         					21 => 'Delived to the GSM network',
         					22 => 'Delivered to mobile phone',
         					30 => 'No credit',
         					41 => 'Content error in message',
         					42 => 'Internal error',
         					43 => 'Message has expired',
         					50 => 'General delivery error',
         					51 => 'Delivery error to GSM network',
         					52 => 'Delivery error to mobile phone',
         					100 => 'No credit',
         					101 => 'Incorrect account details',
         					110 =>  'Incorrect parameter' );
         					
        if( isset( $gatewayCodes[$code] )  == true )
        {
        	$errMsg = $gatewayCodes[$code];
        	return false;
        }
               
        return true;      
      }

} //end class SMSCOM
?>