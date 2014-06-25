<?php
/**
* $Id: mysms.provider.sloono.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SLOONO_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SLOONO_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');


/**
*  Sloono is a sms gateway connection implementation (http://www.sloono.de)
*
* @package MySMS
* @subpackage Provider
**/

class SLOONO extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SLOONO()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SLOONO';
        $this->_file = basename( __FILE__ );

        $this->_params['Username'] =  'user';            //default params
        $this->_params['Password'] =  'passwort';            //default params
        $this->_params['Typ'] =  0;            //default params
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
        $password  = md5($this->_params['Password']);
        $typ       = $this->_params['Typ'];

        $url  = 'http://www.sloono.de/API/httpsms.php?';

        $url .= $this->buildQuery( array( 'user'   		=> $user,
        								  'password' 	=> $password,
        								  'to'  		=> $to,
        								  'typ'  		=> $typ,
        								  'text'	  	=> $text ) );

		 $res = @file_get_contents( $url );

		 if( $res == false )
		 {
		 	$errMsg = 'Unable to send sms';
		 	return false;
		 }


		return true;
      }//end send sms

} //end class SLOONO
?>