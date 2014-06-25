<?php
/**
* $Id: mysms.provider.innosend.php 251 2010-06-30 20:07:34Z axel $
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

if( defined( 'MYSMS_PROVIDER_INNOSEND_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_INNOSEND_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');


/**
*  INNOSEND is a sms gateway connection implementation (https://www.innosend.de)
*
* @package MySMS
* @subpackage Provider
**/

class INNOSEND extends Provider
{
   /**
   *  Constructor, setting up name, file and parameters
   */
      function INNOSEND()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'INNOSEND';
        $this->_file = basename( __FILE__ );

        $this->_params['Id'] 		    =  12345;            //default params
        $this->_params['Passwort']  	=  'passwort';            //default params
		$this->_params['GatewayTyp']    =  '13';            //default params

      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg )
      {

      	$text =  utf8_decode( $text );
        //innosend docs wrong
       // $text = urlencode( $text );

        $id 	=  &$this->_params['Id'];
        $pw 	=  &$this->_params['Passwort'];
        $type 	=  &$this->_params['GatewayTyp'];

        $data = array(	'id'	=> $id,
              			'pw'	=> $pw,
              			'text'	=> $text,
              			'type'	=> $type,
              			'empfaenger' => $to,
              			'absender'	=> $from );

        $data = $this->buildQuery( $data );

		$uri = 'http://www.innosend.de/gateway/sms.php?' . $data;
		
		$ret = file_get_contents( $uri );

		if( $ret == 100 )
		{
			return true;
		}

        return false;
      }//end send sms

} //end class smskaufen
?>