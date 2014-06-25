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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.prerequisite.php $
*
* $Id: mysms.prerequisite.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_BACKEND_PREREQUISITE_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_PREREQUISITE_PHP', 1 );


class MySmsPrerequisite
{
	/**
	 * Execute all checks and return the result as array
	 */
	function Check()
	{
		$methods = get_class_methods( 'MySmsPrerequisite' );
		$result  = array();

		foreach( $methods as $m ){

			if( $m == 'Check' ){
				continue;
			}

			array_push($result, $this->$m() );
		}

		return $result;
	}

	/**
	 * Check if the fopen wrapper are available
	 * many provider need them
	 */
	function CheckFopenWrapper()
	{
		$key  = 'FopenWrapper';
		$val  = (bool)ini_get( 'allow_url_fopen' );
		$desc = 'php.ini - allow_url_fopen - clickatell, innosend, mobilant, nonoh, sms4credits, sms77, smsbox';

		return array( $key, $val, $desc );
	}

	/**
	 * Check php version > 4
	 */
	function CheckPhp5()
	{
		$key  = 'Php5';
		$val  = ((int)phpversion())<5?0:1;
		$desc = 'Php5 - phonebook import/export, nohnoh, mexado';

		return array( $key, $val, $desc );
	}

	/**
	 * Check if ssl stream wrapper
	 */
	function CheckSSLStreamWrapper()
	{
		$key  = 'SSLStreamWrapper';
		$val = 0;

		if( function_exists( 'stream_get_wrappers' ) ){
			$wrapper = 	stream_get_wrappers();

			if( array_search( 'https', $wrapper ) !== false ){
				$val = 1;
			}
		}

		$desc = 'nohnoh';

		return array( $key, $val, $desc );
	}

}//end class
?>