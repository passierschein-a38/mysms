<?php
/**
* $Id: mysms.provider.clickatell.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_CLICKATELL_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_CLICKATELL_PHP', 1 );



/**
 * require our base class
 */
require_once('provider.php');


/**
*  Clickatell is a sms gateway connection implementation (http://www.clickatell.com)
*
* @package MySMS
* @subpackage Provider
**/

class CLICKATELL extends Provider
{


   /**
   *  Constructor, setting up name, file and parameters
   */
      function CLICKATELL()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'CLICKATELL';
        $this->_file = basename( __FILE__ );
        $this->_params['api_id'] =  "123";   //default params
        $this->_params['user'] =  "user";   //default params
        $this->_params['password'] =  "password";   //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text,  $from,  $to, &$errMsg )
      {      
        $text = urlencode ( utf8_decode( $text ) );

        $api_id =  &$this->_params['api_id'];
        $user   =  &$this->_params['user'];
        $password  =  &$this->_params['password'];

        $baseurl ="http://api.clickatell.com";
        $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

        $ret = file($url);
        $sess = split(":",$ret[0]);

         if ($sess[0] != "OK") {
            $errMsg = "SMS konnte nicht versendet werden: ".  $ret[0];
            return false;
         }

         $sess_id = trim($sess[1]);
         $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=$from";
         $ret = file($url);

         $send = split(":",$ret[0]);

         if ($send[0] == "ID")
         {
         	return true;
         }
         
        $errMsg = "Es ist ein Fehler aufgetreten und zwar: " . $send[1];
		return false;
      }

      } //end class clickatell
?>