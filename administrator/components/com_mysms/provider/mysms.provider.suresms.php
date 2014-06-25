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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/mysms.provider.suresms.php $
*
* $Id: mysms.provider.suresms.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_SURESMS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_SURESMS_PHP', 1 );


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

class SURESMS extends Provider
{

   /**
   *  Constructor, setting up name, file and parameters
   */
      function SURESMS()
      {
        Provider::Provider();   //call base class constructor
        $this->_name = 'SURESMS';
        $this->_file = basename( __FILE__ );

        $this->_params['username']	=  'test';            //default params
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

        $user 	=  &$this->_params['username'];
        $pw 	=  &$this->_params['password'];

        //new send a email

        //to phonenumber.username.password@suresms.com
        //subject empty
        //msg msg
		$rec = "$to.$user.$pw@sms.suresms.com";

		$success = mail( $rec, '', $text );

		if( $success === false )
		{
			$errMsg = "Failed to send sms";
			return false;
		}

		return true;
  }//end send sms
} //end class aspsms
?>