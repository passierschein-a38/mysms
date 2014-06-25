<?php
/**
* $Id: mysms.provider.infobip.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_INFOBIP_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_INFOBIP_PHP', 1 );

/**
 * require our base class
 */
require_once('provider.php');

/**
*  AGILETELECOM is a sms gateway connection implementation (https://www.agiletelecom.com)
*
* @package MySMS
* @subpackage Provider
**/

class INFOBIP extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function INFOBIP()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'INFOBIP';
        $this->_file = basename( __FILE__ );

        $this->_params['user']			=  'user';           //default params
        $this->_params['password']	 	=  'password'; 		//default params
		$this->_params['https']			=  '0';	            //default params
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
        $https 		=  (bool)$this->_params['https'];

        $payload = array( 'user'     => $user,
        				  'password' => $pw,
        				  'sender'   => $from,
        				  'GSM'		 => $to,
        				  'SMSText'  => $text );
        
        $http = 'http';
        
        if( $https == true )
        {
        	$http = 'https';
        }
        
		foreach( $payload as $key => $val )
        {
			$query .= urlencode( $key ) . '=' . urlencode( $val ) . '&';
        }

        //remove last &
        $query = substr( $query, 0, strlen($query)-1);
        
        
        $url = $http . '://www.infobip.com/Addon/SMSService/SendSMS.aspx?' . $query;
        
        $content = file_get_contents( $url );
        
        $retCode = (int)$content;
        
        if( $retCode > 0 )
        {
        	
        	return true;
        }
             
        switch( $retCode )
        {
        	case '-1':
        		{
        			$errMsg = 'SEND_ERROR';
        			break;
        		}
        		
                case '-2':
        		{
        			$errMsg = 'NOT_ENOUGHCREDITS';
        			break;
        		}

                case '-3':
        		{
        			$errMsg = 'NETWORK_NOTCOVERD';
        			break;
        		}                		
        	
                case '-4':
        		{
        			$errMsg = 'SOCKET_EXCEPTION';
        			break;
        		}
        		
				case '-5':
        		{
        			$errMsg = 'INVALID_USER_OR_PASS';
        			break;
        		}    

				case '-6':
        		{
        			$errMsg = 'MISSING_DESTINATION_ADDRESS';
        			break;
        		}     

				case '-7':
        		{
        			$errMsg = 'MISSING_SMSTEXT';
        			break;
        		}        
        		
				case '-8':
        		{
        			$errMsg = 'MISSING_SENDERNAME';
        			break;
        		}         		
        		  		
				case '-9':
        		{
        			$errMsg = 'DSTADDRESS_INVALIDFORMAT';
        			break;
        		}   

				case '-10':
        		{
        			$errMsg = 'MISSING_USERNAME';
        			break;
        		}  

				case '-11':
        		{
        			$errMsg = 'MISSING_PASS';
        			break;
        		}          		
        	
        		$errMsg = JText::sprintf( 'MYSMS_SEND_SMS_FAILED', $to, $errMsg );
        		
        		return false;
        }
        
      	    
  }//end send sms
} //end class AGILETELECOM
?>