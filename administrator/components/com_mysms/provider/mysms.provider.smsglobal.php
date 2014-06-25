<?php
/**
* $Id: mysms.provider.smsglobal.php 251 2010-06-30 20:07:34Z axel $
*
* @author Axel Sauerhöfer 
* @copyright Copyright &copy; 2008, Axel Sauerhöfer
* @version 1.5.7
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

if( defined( 'MYSMS_PROVIDER_SMSGLOBAL_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SMSGLOBAL_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMSGLOBAL is a sms gateway connection implementation (https://www.smsglobal.com)
*
* @package MySMS
* @subpackage Provider
**/

class SMSGLOBAL extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SMSGLOBAL()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SMSGLOBAL';
        $this->_file = basename( __FILE__ );

        $this->_params['Username'] =  12345;        	 //default params
        $this->_params['Password'] =  12345;  		     //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {

        $text_copy = rawurlencode( $text );

        //get all params
        $user 	   = $this->_params['Username'];
        $password  = $this->_params['Password'];

        $data = array(	'user'		=> $user,
             			'password'	=> $password,
              			'action'	=> 'sendsms',
              			'text'		=> $text,
              			'from' 		=> $from,
        				'to'		=> $to );

		$payload = $this->buildQuery( $data );
		
		$host = 'http://www.smsglobal.com/http-api.php?' . $payload;
		
		$res  = file_get_contents( $host );
				
		if( eregi( 'ERROR:', $res ) )
      	{
      		$errMsg = strip_tags( $res );
      		return false;
      	}

		return true;
       
      }//end send sms

} //end class SMSGLOBAL
?>