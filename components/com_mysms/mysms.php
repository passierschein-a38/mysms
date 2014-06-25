<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 252 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/components/com_mysms/mysms.php $
*
* $Id: mysms.php 252 2010-07-01 18:47:30Z axel $
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

if( defined( 'MYSMS_FRONTEND_MYSMS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_FRONTEND_MYSMS_PHP', 1 );

@session_start();

//setup correct language, and get all needed globals
global $option, $Itemid, $mainframe;

$frontend_path = dirname( __FILE__ ) . '/';
$backend_path  = dirname( __FILE__ ) .'/../../administrator/components/com_mysms/';

DEFINE( '_MYSMS_PATH' , 		$frontend_path );
DEFINE( '_MYSMS_ADMIN_PATH' , 	$backend_path );

$obj  = &JFactory::getLanguage();	
$obj->load( 'com_mysms' );
	
require_once( $mainframe->getPath('front_html') );
require_once(_MYSMS_ADMIN_PATH . 'provider/providerfactory.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.functions.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.user.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.group.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.error.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.phonebook.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.usergroups.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.config.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.crypt.php' );
require_once(_MYSMS_ADMIN_PATH . 'mysms.template.php' );
require_once(_MYSMS_PATH       . 'mysms.frontend.php' );

//check if user is registered
$user =& JFactory::getUser();

if( $user->get('id') < 1 )
{
	MySMSNoAuth();
	return;
}

//create our sms user object
$smsUser = new mySMSUser( $user->get('id') );

//check com_mysms user rights, is user allowed to send sms (backend)
if( $smsUser->isBlocked() == true )
{
  MySMSNoAuth();
  return;
}

$params = &JComponentHelper::getParams( 'com_component' );

//get task, setup default task to overview
$task = JRequest::getVar( 'task', 'default' );

$database =  &JFactory::getDBO();

jimport('joomla.html.pagination');
JHTML::_('behavior.mootools');

$params = array( 'mySMSUser'		=> $smsUser,
				 'mosParameters'	=> $params,
				 'mosMainframe'		=> $mainframe,
				 'mosDatabase'		=> $database,
				 'ItemId'		    => $Itemid,
				 'option'			=> $option,
				 'lang'				=> $lang
				 );

$frontend = new mySmsFrontend( $task, $params );

if( $frontend->CanHandle() )
{
	return $frontend->Execute();
}

echo 'Cannot handle task: ' . $task;
?>