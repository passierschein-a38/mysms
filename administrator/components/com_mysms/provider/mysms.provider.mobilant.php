<?php
/**
* $Id: mysms.provider.mobilant.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_MOBILANT_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_MOBILANT_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');

/**
*  MOBILANT is a sms gateway connection implementation (http://www.mobilant.de)
*
* @package MySMS
* @subpackage Provider
**/

class MOBILANT extends Provider
{
   /**
         *  Constructor, setting up name, file and parameters
      */

      function MOBILANT()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'MOBILANT';
        $this->_file = basename( __FILE__ );
        $this->_params['host'] =  "gateway.mobilant.net";   //default params
        $this->_params['path'] =  "/?";   //default params
        $this->_params['key'] =  "KEY";          //default params
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

        $key =  &$this->_params['key'];

        //create correct data
        $data = "key=".$key."&Text=".$text."&handynr=".$to."&kennung=".$from."";

        $host =  $this->_params['host'];
        $path =  $this->_params['path'];

        //post data to host e.g. send the sms
        $x = file("http://$host$path$data");

        //checkup return data
        if ( $x[0] == 100 ) 
        {
           return true;
        } else {
          $errMsg = "Es ist ein Fehler aufgetreten";
          return false;
        }

      }    //end function  sendSms

} //end class mobilant
?>