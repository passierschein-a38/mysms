<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 184 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.tm4b.php $
*
* $Id: mysms.provider.tm4b.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_TM4B_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_TM4B_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');

/**
*  TM4B is a sms gateway connection implementation (https://www.tm4b.com)
*
* @package MySMS
* @subpackage Provider
**/

class TM4B extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function TM4B()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'TM4B';
        $this->_file = basename( __FILE__ );

		$this->_params['user'] 		=  'user';            	//default params
		$this->_params['password']	=  'password';            //default params
		$this->_params['route']		=  'first';            //default params
      }


   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg )
      {
        $user 		=  &$this->_params['user'];
        $pw 		=  &$this->_params['password'];     
        $rt			=  &$this->_params['route'];
        
        $payload = array(  'username' => $user,
        				   'password' => $pw,
        				   'type' 	  => 'broadcast',
        				   'to'		  => $to,
        				   'from' 	  => $from, 
        				   'msg'	  => $text, 
        				   'route'	  => $rt );
                
        //ugly fix, the gateway does not accept &amp; 
        
        foreach( $payload as $key => $val )
        {
			$query .= urlencode( $key ) . '=' . urlencode( $val ) . '&';
        }
        
        $url = 'http://www.tm4b.com/client/api/http.php?' . $query;
                
        $result = file_get_contents( $url, false );
                
        if( eregi( 'error', $result  ) )
        {
        	$errMsg = strip_tags( $result );
        	return false;
        }
        
        return true;      
  }//end send sms
  
} //end class TM4B
?>