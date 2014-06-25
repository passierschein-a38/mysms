<?php
/**
* $Id: mysms.provider.smsat.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMSAT_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSAT_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');
require_once('3rdParty/sms_at.inc.php');
/**
*  Dev is a dummy provider which always return true
*
* @package MySMS
* @subpackage Provider
**/

class SMSAT extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSAT()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSAT';
        $this->_file = basename( __FILE__ );
        $this->_params['AccountType'] =  'user';
        $this->_params['User'] =  'user or email';
        $this->_params['Password'] =  'password';
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms(&$text, &$from, &$to, &$errMsg)
      {
      	$user 	   = $this->_params['Username'];
        $password  = $this->_params['Password'];

      	$account_type = $this->_params['AccountType'];
  		$account      = $this->_params['User'];
  		$pass         = $this->_params['Password'];

  		$gateway_url  = 'http://gateway.sms.at/xml_interface/'; # address of sms.at gateway

	    $sms = new sms_at($account_type, $account, $pass, $gateway_url); # code line 2/3
  	    $sms->set_verbose(true); # echo http posts / view xml

  		list( $code, $description, $transfer_id ) = $sms->send_simple_text_sms($to, $text, 'none', 0);

  		if( $code == 2000  || $code == 2001 )
  		{
  			return true;
  		}

  		$errMsg = $description;

  		return false;
      }

} //end class SMSAT
?>