<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 254 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/admin.mysms.php $
*
* $Id: admin.mysms.php 254 2010-07-06 20:29:06Z axel $
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

if( defined( 'MYSMS_BACKEND_ADMIN_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_ADMIN_PHP', 1 );

$backend_path  = dirname( __FILE__ ) . '/';
DEFINE( '_MYSMS_ADMIN_PATH' , 	$backend_path );

$obj  = &JFactory::getLanguage();	
$obj->load( 'com_mysms' );

require_once( _MYSMS_ADMIN_PATH . 'mysms.functions.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.crypt.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.config.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.error.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.backend.php' );
require_once( _MYSMS_ADMIN_PATH . 'admin.mysms.html.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.template.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.user.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.group.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.phonebook.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.usergroups.php' );
require_once( _MYSMS_ADMIN_PATH . 'mysms.prerequisite.php' );
require_once( _MYSMS_ADMIN_PATH . '/provider/providerfactory.php' );

$task = JRequest::getVar( 'task', 'Default');
$act  = JRequest::getVar( 'act', 'Default');
$cid  = JRequest::getVar( 'cid', array(0));

$backend = new mySmsBackend( $act, $task, $cid );

if( $backend->CanHandle() )
{
	return $backend->Execute();
}

echo 'Cannot handle task: ' . $task . $act;
?>