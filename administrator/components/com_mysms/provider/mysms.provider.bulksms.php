<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author$
* $Rev$
* $HeadURL$
*
* $Id$
*
* All rights reserved. 
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

if( defined( 'MYSMS_PROVIDER_BULKSMS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_BULKSMS_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');

/**
*  Suresms is a sms gateway connection implementation (https://www.suresms.com)
*
* @package MySMS
* @subpackage Provider
**/

class BULKSMS extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function BULKSMS()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'BULKSMS';
        $this->_file = basename( __FILE__ );

        $this->_params['profileid']	=  '1234';            //default params
        $this->_params['senderid']	=  '1234';            //default params
        $this->_params['password'] 	=  'test';            //default params              
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {

        $id 	  =  &$this->_params['profileid'];
        $pw 	  =  &$this->_params['password'];
        $senderid =  &$this->_params['senderid'];              
        
 		$data = array(	'usr'	=> $id,
              			'pwd'	=> $pw,
              			'sndr'	=> $senderid,
 						'ph'	=>  $to,
 						'text'	=> $text,
 						'rpt'	=> 0  );        
 		
 		$baseUrl = 'http://smsodisha.in/api/pushsms.php?';
 		
 		$uri = $baseUrl . $this->buildQuery( $data );
 		
 		$result = file_get_contents( $uri );
 		
 		if( eregi( '-150', $result ) )
 		{
 			return true;
 		}
 		
 		$errMsg = $result;

		return false;
  }//end send sms
} //end class aspsms
?>