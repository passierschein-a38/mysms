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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.usergroups.php $
*
* $Id: mysms.usergroups.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_BACKEND_USERGROUPS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_USERGROUPS_PHP', 1 );

/**
*  mySMS User Group class, collection of single group classes
*
 * @package MySMS
 * @subpackage Util
**/
class mySMSUserGroups
{
     var $_groups;
     var $_ownerID;
     var $_db;

   /**
	* The constructor creates a new user group
	*
	**/
	function mySMSUserGroups($owernid)
	{
		$this->_db = &JFactory::getDBO();

       if( is_numeric($owernid) )
       {
            $this->_ownerID = $owernid;
            $this->init();
       }
	}

/**
* This function load's all user groups
*
**/
function init()
{

  $sql = "SELECT * FROM #__mysms_groups WHERE ownerid=".$this->_ownerID;

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert(   JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
        exit();
  }

  $lst = $this->_db->loadObjectList();

  foreach($lst as $l)
  {
    $g = new mySMSGroup();
    $g->init( $l->name );
    $this->_groups[] = $g;
  }

}

function reload()
{
  unset($this->_groups);
  $this->init();
}

function getEntries()
{
  return $this->_groups;
}

}  //end class
?>