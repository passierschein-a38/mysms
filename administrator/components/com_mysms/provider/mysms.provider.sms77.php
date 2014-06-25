<?php
/**
* $Id: mysms.provider.sms77.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMS77_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMS77_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMS77 is a sms gateway connection implementation (https://www.sms77.de)
*
* @package MySMS
* @subpackage Provider
**/

class SMS77 extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMS77()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMS77';
        $this->_file = basename( __FILE__ );

        $this->_params['Username'] =  12345;            //default params
        $this->_params['Password'] =  'passwort';            //default params
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
        $user 	   = $this->_params['Username'];
        $password  = $this->_params['Password'];

         //create message data
        $url="http://gateway.sms77.de/?u=$user&p=$password&to=$to&from=$from&text=$text&type=quality";


        $opts = array(
  					'http'=>array(
    					'method'=>"GET",
    					'header'=>"Host: gateway.sms77.de\r\n" .
              			"Connection: Close\r\n"
  					)
				);

		$context = stream_context_create($opts);

		$fp = fopen($url, 'r', false, $context);

		if( $fp === false ){
			 $errMsg =  'Konnte keine Verbindung zum Gateway aufbauen';
			 return false;
		}

		$res = stream_get_contents($fp);

		switch( $res )
		{
			case 100:
				return true;
				break;
			case 900:
				$errMsg = $errMsg = 'Benutzername/Passwort falsch';
				break;
			default:
				$errMsg = 'Versand fehlgeschlagen: RÃ¼ckgabewert: ' . $res ;
				break;
		}

		return false;
      }//end send sms

} //end class SMS77
?>