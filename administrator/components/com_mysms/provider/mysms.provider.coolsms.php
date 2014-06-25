<?php
/**
* $Id: mysms.provider.coolsms.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_COOLSMS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_COOLSMS_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMSGLOBAL is a sms gateway connection implementation (https://www.coolsms.com)
*
* @package MySMS
* @subpackage Provider
**/

class COOLSMS extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function COOLSMS()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'COOLSMS';
        $this->_file = basename( __FILE__ );

        $this->_params['username'] =  12345;        	 //default params
        $this->_params['password'] =  12345;  		     //default params
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function sendSms( $text, $from, $to, &$errMsg)
      {
      	/*
      	echo '<pre>';
      	echo 'raw:' . var_dump( mb_strlen( $text ) ) . "\r\n";
      	echo 'enc:' . var_dump( mb_strlen( utf8_decode( $text ) ) ) . "\r\n";
      	echo 'url:' . var_dump( urlencode( utf8_decode( $text ) ) ) . "\r\n";
      	echo '</pre>';
      	
      	die;*/
      	
        $text_copy = utf8_decode( $text );
        
        //get all params
        $user 	   = $this->_params['username'];
        $password  = $this->_params['password'];

        $data = array(	'username'		=> $user,
             			'password'		=> $password,
              			'message'		=> $text_copy,
              			'from' 			=> $from,
        				'to'			=> $to,
        				'resulttype'	=> 'urlencoded' );

		$payload = $this->buildQuery( $data );
				
		$host = 'sms.coolsmsc.dk';
        $fp = fsockopen($host,80);

        if (!$fp)
        {
        	$errMsg = JText::_( 'MYSMS_ERROR' ) . ':  Network';
        	return false;
        }
        
        @fclose( $fp );

		$options = array(
		  'http'=>array(
		    'method'=>"POST",
		    'header'=>
		      "Accept-language: en\r\n".
		      "Content-type: application/x-www-form-urlencoded\r\n"
		      . "Content-length: " . strlen( $payload ) . "\r\n",
		      'content' => $payload
			)
		);
		
		$context = stream_context_create($options);

		$fp = fopen( 'http://sms.coolsmsc.dk/sendsms.php', 'r', false, $context );

		if( $fp === false )
		{
			 $errMsg = JText::_( 'MYSMS_ERROR' ) . ':  Network';
			 return false;
		}

		$res = stream_get_contents($fp);		
		$return = explode( '&', $res );
		
		$success = true;
		$stat    =  '';
		
		foreach ( $return as $s )
		{
			if( eregi( 'status', $s ) )
			{
				$t = explode( '=', $s );

				if( $t[1] == 'failure' )
				{
					$success = false;
				}else{
					$success = true;
				}				
			}
			
			
			if( eregi( 'result' , $s ) )
			{
				$t = explode( '=', $s );
				$stat = $t[1];				
			}			
		}
		
		if( $success == false )
		{
			$errMsg = urldecode( $stat ); 
			return false;
		}
			
		return true;
		       
      }//end send sms

} //end class COOLSMS
?>