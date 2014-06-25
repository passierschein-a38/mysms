<?php
/**
* $Id: mysms.provider.hardwired.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_HARDWIRED_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_HARDWIRED_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');

/**
*  Hardwired is a sms gateway connection implementation (https://www.hardwire.dk)
*
* @package MySMS
* @subpackage Provider
**/

class HARDWIRED extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function HARDWIRED()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'HARDWIRED';
        $this->_file = basename( __FILE__ );

        $this->_params['username']	=  'test';            //default params
        $this->_params['password'] 	=  'test';            //default params
		$this->_params['smsc'] 		=  'tdc';            //default params
		$this->_params['shortcode']	=  '1961';            //default params
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg )
      {
        $user 		=  &$this->_params['username'];
        $pw 		=  &$this->_params['password'];
        $smsc   	=  &$this->_params['smsc'];
        $shortcode 	=  &$this->_params['shortcode'];

        $url  = 'http://api.hardwired.dk/rpc/sendsms/?';
        $url .= $this->buildQuery( array( 'text'   => $text,
        								  'recipient' => $to,
        								  'username'  => $user,
        								  //'password'  => md5($pw),
											'password' => 'fre150375',
        								  'from'	  => $from,
        								  'shortcode' => $shortcode,
        								  'smsc'	  => $smsc,
        								  'price'	  => 0 ) );

		$response = file_get_contents( $url );

		if( eregi( 'ERR', $response ) )
		{
			$errMsg = strip_tags( $response );
			return false;
		}

		return true;
  }//end send sms
} //end class aspsms
?>