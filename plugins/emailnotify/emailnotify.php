<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 183 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/plugins/emailnotify/emailnotify.php $
*
* $Id: emailnotify.php 183 2009-12-11 17:02:29Z axel $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$mainframe->registerEvent( 'onSendSms', 'plgMySMSEmailNotify' );

/**
 * DynDns based authentication plugin
 */
class plgMySMSEmailNotify extends JPlugin
{

	/**
	 * Construct our plugin
	 */
	function plgMySMSEmailNotify( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}

	/**
	 * On send sms
	 *
	 */
	function onSendSms( $message, $from, $to )
	{
		$email  = "Sender: $from \r\n";
		$email .= "Receiver: $to \r\n";
		$email .= "Message: $message \r\n";

		$plugin = & JPluginHelper::getPlugin('mysms', 'emailnotify');

		//Load plugin params info
		$pluginParams = new JParameter($plugin->params);
		$e = $pluginParams->get( 'emailnotify' );

		$success = mail( $e, 'MySMS - EmailNotify', $email );

		return $success;
	}
}
?>