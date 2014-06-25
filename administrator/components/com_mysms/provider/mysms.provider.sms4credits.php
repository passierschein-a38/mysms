<?php
/**
* $Id: mysms.provider.sms4credits.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SMS4CREDITS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMS4CREDITS_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');


/**
*  Sms4Credits is a sms gateway connection implementation (http://www.sms4credits.de)
*
* @package MySMS
* @subpackage Provider
**/

class SMS4CREDITS extends Provider
{


   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMS4CREDITS()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMS4CREDITS';
        $this->_file = basename( __FILE__ );
        $this->_params['nick'] =  "nick";   //default params
        $this->_params['password'] =  "123";   //default params
        $this->_params['buchungstext'] =  "send by com_mysms";   //kontobuchungstext
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
        $text = urlencode ( $text );

        $nick =  &$this->_params['nick'];
        $password  =  &$this->_params['password'];
        $ttext     = urlencode(  &$this->_params['buchungstext'] );

        $gateway = "http://api.sms4credits.de/api/sms_inland_mit_absender.php?nick=$nick&pw=$password&empfaenger=$to&absender=$from&text=$text&ttext=$ttext";

        $ret = file($gateway);
        $ret = explode('|', $ret[0]);

        $ok = false;
        switch($ret[0])
        {
          case  '1001':
                $ok = true;
                break;
          case '1010':
               $errMsg =  'Unbekanter fehler';
               break;
          case '1009':
               $errMsg =  'Falsche Handynummer';
               break;
          case '1008':
               $errMsg =  'Kein Text angegeben';
               break;
          case '1007':
               $errMsg =  'SMS Länger als 160 zeichen';
               break;
           case '1006':
               $errMsg =  'Kein Abender angegeben';
               break;
           case '1005':
               $errMsg =  'Kein Empfänger angegeben';
               break;
           case '1004':
               $errMsg =  'Zu wenig Punkte';
               break;
           case '1003':
               $errMsg =  'PW Falsch';
               break;
           case '1002':
               $errMsg =  'User nicht vorhanden';
               break;
        }

        return $ok;

      }

      } //end class sms4credits
?>