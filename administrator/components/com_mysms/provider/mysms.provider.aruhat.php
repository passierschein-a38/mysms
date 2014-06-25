<?php
/**
* $Id: mysms.provider.aruhat.php 184 2009-12-11 17:06:40Z axel $
*
* @author Axel Sauerhöfer 
* @copyright Copyright &copy; 2009, Axel Sauerhöfer
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

if( defined( 'MYSMS_PROVIDER_ARUHAT_PHP' ) == true )
{
   return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_ARUHAT_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');

/**
*  Aruhat is a sms gateway connection implementation (http://www.Aruhat.com)
*
* @package MySMS
* @subpackage Provider
**/

class ARUHAT extends Provider
{


   /**
   *  Constructor, setting up name, file and parameters
   */
      function ARUHAT()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'ARUHAT';
        $this->_file = basename( __FILE__ );
        $this->_params['username'] =  "user";   //default params
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
      	
      	$username   =  &$this->_params['username'];
        $password  =   &$this->_params['password'];
        
      	$baseUrl = 'http://vibgyor.aruhat.com/sendsms.jsp?';
      	
      	$data = array( 'user' 		=> $username ,
      				   'password' 	=> $password,
      				   'mobiles'    => $to,
      					'sms'		=> $text,
      					'group'		=> -1,
      					'senderid'  => $from,
      					 );
      				   

		foreach( $data as $key => $val )
        {
			$q .= urlencode( $key ) . '=' . urlencode( $val ) . '&';
        }

        //remove last &
        $q = substr( $q, 0, strlen($q)-1);
      	
      	$request = $baseUrl . $q;
      	$response = file_get_contents( $request );
      	      
      	//simple xml is available
      	if( function_exists( 'simplexml_load_string' ) )
      	{
      		$xml = simplexml_load_string( $response );
      		
			$r = @$xml->xpath( '//error/error-code' ); 

			//xpath failed no error !
			if( $r == false )
			{

				/*<?xml version="1.0" encoding="iso-8859-1"?>
				<sms>
					<messageid>1</messageid>
				</sms>*/
				
				$x = @$xml->xpath( '//sms/messageid' );
				
				$id = array_pop( $x );
				
				if( $id == 1 )
				{
					return false;
				}
				
				return true;	
								
			}else{
			
				$errCode = array_pop( $r );		
				$r = @$xml->xpath( '//error/error-description' );
				$errDesc = array_pop( $r );
						
				$errMsg = $errCode . ': ' . $errDesc;
				return false;
			}
      	}
      	
      	//fallback without simpel xml api
      	
      	if( eregi( 'error', $response ) )
      	{
      		return false;
      	}
      	
      	return true;
            	
      } //end sms sms

      } //end class clickatell
?>