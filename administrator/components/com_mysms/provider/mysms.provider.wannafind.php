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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.wannafind.php $
*
* $Id: mysms.provider.wannafind.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_WANNAFIND_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_WANNAFIND_PHP', 1 );


/**
 * require our base class
 */
require_once('provider.php');


/**
*  SMS77 is a sms gateway connection implementation (https://www.sms77.de)
*
* @package MySMS
* @subpackage Provider
**/

class WANNAFIND extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function WANNAFIND()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'WANNAFIND';
        $this->_file = basename( __FILE__ );

        $this->_params['Username'] =  12345;            //default params
        $this->_params['Password'] =  'passwort';       //default params
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

        $data = array(	'username'	=> $user,
             			'password'	=> md5( $password ),
              			'recipient'	=> $to,
              			'message'	=> $text,
              			'from' 		=> $from );

		$payload = $this->buildQuery( $data );

		$host = 'sms.wannafind.dk';
        $fp = fsockopen($host,80);

        if (!$fp)
        {
        	$errMsg = JText::_( 'MYSMS_ERROR' ) . ':  Network';
        	return false;
        }

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

		$fp = fopen('http://sms.wannafind.dk/api/', 'r', false, $context);

		if( $fp === false )
		{
			 $errMsg =  'Konnte keine Verbindung zum Gateway aufbauen';
			 return false;
		}

		$res = stream_get_contents($fp);

		if( eregi( 'SUCCESS:', $res ) )
      	{
      		return true;
      	}

      	$errMsg = $res;
      	return false;
       
      }//end send sms

} //end class SMS77
?>