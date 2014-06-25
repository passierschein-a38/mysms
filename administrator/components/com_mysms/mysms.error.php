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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.error.php $
*
* $Id: mysms.error.php 184 2009-12-11 17:06:40Z axel $
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

if( defined('MYSMS_ERROR_PHP') == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define('MYSMS_ERROR_PHP', 1);

/**
*  mySMS Error handling class
*
 * @package MySMS
 * @subpackage Util
**/
class mySMSError {

	/**
	 * Set this value to 1 if you want to get
	 * all error message as email.
	 *
	 * @var bool
	 */
	var $errorReporting;

	/**
	 * Send error reports to this email
	 *
	 * @var string
	 */
	var $email;

	/**
	 * Configuration object
	 *
	 * @var mySmsConfig
	 */
	var $config;

	/**
	 * Constructor
	 *
	 */
	function mySMSError()
	{
		$this->config = new mySmsConfig();
		$this->errorReporting = $this->config->Get( 'mailonerror');

		if( $this->errorReporting == 1 )
		{
			$this->email = $this->config->Get( 'email' );
		}
	}

	/**
	 * Alert the message as java script box and go -1 in histroy.
	 *
	 * @param string $msg
	 */
    function  Alert( $msg, $desc = '' )
    {
          echo "<script> alert('$msg'); window.history.go(-1); </script>\n";
          $this->Mail( $msg, $desc );
          exit();
    }

    /**
     * Send a error report email
     *
     * @param string $msg
     * @param string $desc
     */
    function Mail( $msg, $desc = '' )
    {
    	if( $this->errorReporting !== 1 )
    	{
    		return;
    	}

    	$user =& JFactory::getUser();

		$subject = 'com_mysms error alter';
		$msg  	 = "A error occured: \r\n";
		$msg	 .= "error: $msg \r\n";

		if( strlen( $desc ) > 0 )
		{
			$msg	 .= "description: $desc \r\n";
		}

		$msg	 .= "ip:    " . $_SERVER['REMOTE_ADDR'] .  "\r\n";
		$msg	 .= "user:  " . $user->get('name')   .  "\r\n";
		$msg	 .= "email: " . $user->get('email') . "\r\n";

		mail( $this->email, $subject, $msg );
    }

}//end class
?>