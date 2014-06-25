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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.config.php $
*
* $Id: mysms.config.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_BACKEND_CONFIG_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_CONFIG_PHP', 1 );

/**
 * Simple getter class for configuration values
 *
 * @package MySMS
 * @subpackage Util
 *
 */
class mySmsConfig
{
	/**
	 * Get a global configuration by name
	 *
	 * @param string $key
	 * @return string $value
	 */
	function Get( $key )
	{
		$database = &JFactory::getDBO();
		$sql = "SELECT * FROM #__mysms_config WHERE name='$key' LIMIT 1";
		$database->setQuery( $sql );
		$database->query();
		$obj = $database->loadObject();
		return is_null($obj)?false:$obj->value;
	}

}//end class
?>