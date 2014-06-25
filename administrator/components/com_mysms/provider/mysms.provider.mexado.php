<?php
/**
* $Id: mysms.provider.mexado.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_MEXADO_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_MEXADO_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');

/**
*  Medaxo is a provider implementation for http://www.mexado.com
*
* @package MySMS
* @subpackage Provider
**/

class Mexado extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function MEXADO()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'MEXADO';
        $this->_file = basename( __FILE__ );
		$this->_params['username'] 	=  'test';            //default params
        $this->_params['password'] 	=  'test';            //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms(&$text, &$from, &$to, &$errMsg)
      {
        //decode message text
        $text = rawurlencode( $text );

        $user 	   = $this->_params['username'];
        $password  = $this->_params['password'];

		//create message data
        $url="http://www.mexado.com/api/smsapi.php?";


        $url .= $this->buildQuery( array( 'username' => $user,
        				          'password' => $password,
        				          'to'		 => $to,
        				          'from'	 => $from )  );


        $opts = array(
  					'http'=>array(
    					'method'=>"GET",
    					'header'=>"Host: www.mexado.com\r\n" .
              			"Connection: Close\r\n"
  					)
				);

		$context = stream_context_create($opts);

		$fp = fopen($url, 'r', false, $context);

		if( $fp === false )
		{
			 $errMsg =  'Konnte keine Verbindung zum Gateway aufbauen';
			 return false;
		}

		$res = stream_get_contents($fp);

		//error occured
		if( !eregi('OK', $res ) )
		{
			$errMsg = 'Error';
			return false;
		}

		return true;

      }

} //end class Mexado
?>