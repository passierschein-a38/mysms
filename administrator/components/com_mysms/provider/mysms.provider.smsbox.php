<?php
/**
* $Id: mysms.provider.smsbox.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMSBOX_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSBOX_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');


/**
*  Smsbox is a sms gateway connection implementation (https://www.smsbox.fr)
*
* @package MySMS
* @subpackage Provider
**/

class SMSBOX extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSBOX()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSBOX';
        $this->_file = basename( __FILE__ );

        $this->_params['login']	   	=  'test';            //default params
        $this->_params['password'] 	=  'test';            //default params
		$this->_params['mode'] 		=  'economique';      //default params
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms(&$text, &$from, &$to, &$errMsg)
      {
        //doc on website failed
        //$text = rawurlencode( $text );

        $user 	=  &$this->_params['user'];
        $pw 	=  &$this->_params['password'];
        $mode 	=  &$this->_params['mode'];


        $data = array(	'login'		=> $this->_params['login'],
              			'pass'		=> $this->_params['password'],
              			'mode'		=> $this->_params['mode'],
              			'msg'		=> $text,
              			'dest'  	=> $to,
              			'origine'	=> $from );


        $url = "http://api.smsbox.fr/api.php?" . $this->buildQuery( $data );
		$return = file( $url );

		if( strtolower($return[0]) == 'ok' ){
			return true;
		}

		switch( $return[0] )
		{
			case 'ERROR 01':
				$errMsg = 'Des paramètres sont manquants';
				break;
			case 'ERROR 02':
				$errMsg = 'Identifiants incorrects ou compte banni';
				break;
			case 'ERROR 03':
				$errMsg = 'Crédit épuisé ou insuffisant';
				break;
			case 'ERROR 04':
				$errMsg = 'Numéro de destination invalide ou mal formaté';
				break;
			case 'ERROR 05':
				$errMsg = 'Erreur d\'éxécution interne à notre application';
			case 'ERROR':
				$errMsg = 'L\'envoi a échoué pour une autre raison (liste noire, opérateur indisponible, ...)';
				break;
			default:
				$errMsg = 'unknwon error code';
		}

		return false;
      }//end send sms
} //end class aspsms
?>