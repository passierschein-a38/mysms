<?php
/**
* $Id$
*
* @author Axel Sauerhöfer 
* @copyright Copyright &copy; 2009, Axel Sauerhöfer
* @version 1.5.9
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

if( defined( 'MYSMS_PROVIDER_MASSENVERSAND_PHP' ) == true )
{
   return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_MASSENVERSAND_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');

/**
*  Massenversand is a sms gateway connection implementation (http://www.MASSENVERSAND.de)
*
* @package MySMS
* @subpackage Provider
**/

class MASSENVERSAND extends Provider
{


   /**
   *  Constructor, setting up name, file and parameters
   */
      function MASSENVERSAND()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'MASSENVERSAND';
        $this->_file = basename( __FILE__ );
        $this->_params['gid'] =  "id";   //default params
        $this->_params['password'] =  "password";   //default params
        $this->_params['ssl'] =  "0";   //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text,  $from,  $to, &$errMsg )
      {      	    

      	$text = utf8_decode( $text );
      	
      	$id   		=  &$this->_params['gid'];
        $password  	=  &$this->_params['password'];
        $ssl		=  &$this->_params['ssl']; 
        
        $proto =  'http';
        
        if( $ssl == 1 )
        {
        	$proto = 'https';
        }
        
      	$baseUrl =  $proto . '://gate1.goyyamobile.com/sms/sendsms.asp?';
      	
      	$data = array( 'receiver' 	=> $to,
      				   'sender' 	=> $from,
      				   'msg' 	    => $text,
      				   'id'		    => $id,
      				   'pw'			=> $password,
      				   'msgtype'	=> 't'
      					 );
      				   
      					 
	   $q = $baseUrl . $this->buildQuery( $data ); 			
      					 
	   $response = file_get_contents( $q );
	   
	   if( 'OK' == strtoupper( $response ) )
	   {
	   		return true;
	   }
	   	   
	   $errMsg = $response;   
      	
	   return false;
            	
      } //end sms sms

      } //end class clickatell
?>