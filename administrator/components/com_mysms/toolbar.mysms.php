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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/toolbar.mysms.php $
*
* $Id: toolbar.mysms.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_BACKEND_TOOLBAR_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_TOOLBAR_PHP', 1 );

$html = dirname( __FILE__ ) . '/toolbar.mysms.html.php' ;
require_once( $html );

/**
 * ToolBar Dispatcher class
 *
 * Only a simple wrapper for toolbar html class
* @package MySMS
* @subpackage Backend
*/
class mySmsToolBar
{
	/**
	 * Current task to execute
	 *
	 * @var string
	 */
	var $task;

	/**
	 * Currenct action
	 *
	 * @var action
	 */
	var $action;

	/**
	 * Html layer
	 */
	var $html;

	/**
	 * Constructor
	 *
	 * Get current task and action
	 */
	function mySmsToolBar()
	{
		$this->action	= JRequest::getVar( 'act',  '' );
		$this->task 	= JRequest::getVar( 'task', 'Default' );
		$this->html		= new mySmsToolBarHtml();
	}

	/**
	 * Execute
	 *
	 * Execute the toolbar
	 */
	function Execute()
	{
		if( is_null( $this->action ) ){
			return;
		}

		$method = 'Do' . ucfirst( strtolower( $this->action ) ) . ucfirst( strtolower( $this->task ) );

		if( !method_exists( $this, $method ) ){
			return;
		}

		if( !is_callable( array( $this, $method ) ) ){
			return;
		}

		call_user_method( $method, $this );
	}

	/**
	 * Show the default toolbar on provider panel
	 *
	 */
	function DoProviderDefault()
	{
		$this->html->ProviderDefault();
	}

	/**
	 * Show the edit toolbar on provider panel
	 *
	 */
	function DoProviderEdit()
	{
		$this->html->EditProvider();
	}

	/**
	 * Toolbar after save provider settings
	 */
	function DoProviderSave()
	{
		$this->html->ProviderDefault();
	}

	/**
	 * Show the user default toolbar
	 */
	function DoUserDefault()
	{
		$this->html->UserDefault();
	}

	/**
	 * Show the user default toolbar
	 */
	function DoUser()
	{
		$this->html->UserDefault();
	}

	/**
	 * Toolbar for editing a user
	 */
	function DoUserEdit()
	{
		$this->html->EditUser();
	}

	/**
	 * Advertisment default toolbar
	 */
	function DoAdDefault()
	{
		$this->html->AdDefault();
	}

	/**
	 * Default toolbar for global settings
	 */
	function DoGlobalDefault()
	{
		$this->html->GlobalDefault();
	}

	/**
	 * Default about
	 */
	function DoAboutDefault()
	{
		$this->html->AboutDefault();
	}

	/**
	 * Default about
	 */
	function DoPrereqDefault()
	{
		$this->html->AboutDefault();
	}
}

$toolbar = new mySmsToolBar();
$toolbar->Execute();
?>