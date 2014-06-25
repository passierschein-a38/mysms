<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 203 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.group.php $
*
* $Id: mysms.group.php 203 2010-02-04 18:59:38Z axel $
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

if( defined( 'MYSMS_BACKEND_GROUPS_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_GROUPS_PHP', 1 );

/**
*  mySMS Group class
*
 * @package MySMS
 * @subpackage Util
**/
class mySMSGroup {

      var $_id; //id of the group unique
      var $_ownerID; //owner id
      var $_name; //group name
      var $_members;//group members
	  var $_db; //refernce to the global db object

/**
* The constructor creates a new user group
*
**/

function mySMSGroup()
{
    $this->_id = -99;
    $this->_name = '';
    $this->_members = array();
    $this->_db = &JFactory::getDBO();
}

function init($idORName)
{
	//prevent null values
 	if( is_null($idORName ) )
 	{
	   	return;
 	}

 	if( is_numeric($idORName) )
 	{
   		$sql = "SELECT COUNT(*) AS COUNT from #__mysms_groups WHERE id='$idORName'";
 	}else{
   		$sql = "SELECT COUNT(*) AS COUNT from #__mysms_groups WHERE name='$idORName'";
 	}

 	$this->_db->setQuery($sql);

  	if( $this->_db->query() === false )
  	{
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
    }

  	$g = $this->_db->loadObject();
  //we can only create our group if we have a name, checkup

  	if( !is_numeric( $idORName ) )
  	{
      //group not exists, create it, and call us again
      if( $g->COUNT <= 0 )
      {

      	  $my = &JFactory::getUser();
      	  $id = $my->get('id');

          $sql = "INSERT INTO #__mysms_groups VALUES(0, '$idORName', $id )";
          $this->_db->setQuery($sql);

          if( $this->_db->query() === false )
          {
             mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
             die;
          }
       }
   }

   if( is_numeric( $idORName ) )
   {
       $sql = "SELECT * from #__mysms_groups WHERE id='$idORName'";
   }else{
       $sql = "SELECT * from #__mysms_groups WHERE name='$idORName'";
   }

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
      mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
      die;
  }

  unset($g);
  $g = $this->_db->loadObject();

  $this->_ownerID = $g->ownerid;
  $this->_name = $g->name;
  $this->_id = $g->id;

  //now collect all user in groups from #__mysms_usergroups
  $sql = "SELECT * FROM #__mysms_usergroups WHERE groupid=". $this->_id;

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
  }

  $lst = $this->_db->loadObjectList();

  //now we have member id, we need name, from our table
  //collect a user in our group
  foreach($lst as $l)
  {
    $sql ="SELECT id, name, number FROM #__mysms_phonebook WHERE id=$l->memberid";
    $this->_db->setQuery($sql);

    if( $this->_db->query() === false )
    {
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
    }

    $u = $this->_db->loadObject();
    $this->_members[$l->id] = $u;
    unset($u);
    //data structure
    //unique usergroups id --> object ( id, name, number ) phonebook
  }
}

function addMember($id)
{
  //given id, is the id from the phonebook entry !!!

  //check input
  if( !is_numeric( $id ) )
  {
       return false;
  }

  $my = &JFactory::getUser();
  //check if given id is a user phonebook entry
  $sql = "SELECT COUNT(*) AS COUNTER FROM #__mysms_phonebook WHERE id=$id AND ownerid=". $my->get('id');

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
      mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
      die;
  }

  $u = $this->_db->loadObject();

  //given id is invalid
  if( $u->COUNTER <= 0 )
  {
     return false;
  }

  //insert new entry
  $sql = "INSERT INTO #__mysms_usergroups VALUES(0, $id, $this->_id)";

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
	mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
    die;
  }

  return true;
}

function deleteMember( $id )
{

 //check input
  if( !is_numeric( $id ) )
  {
       return false;
  }

  $my = &JFactory::getUser();
  //check if given id is a user phonebook entry
  $sql = "SELECT COUNT(*) AS COUNTER FROM #__mysms_phonebook WHERE id=$id AND ownerid=". $my->get('id');

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
      mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
      die;
  }

  $u = $this->_db->loadObject();

  //given id is invalid
  if( $u->COUNTER <= 0 )
  {
     return false;
  }

//insert new entry
  $sql = sprintf( "DELETE FROM #__mysms_usergroups where memberid=%s and groupid=%s LIMIT 1", $id, $this->_id );
  
  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
	mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
    die;
  }

  return true;  
  
}


function delete()
{
   //first delete all entries from #__mysms_usergroups
   $sql ="DELETE FROM #__mysms_usergroups WHERE groupid=$this->_id";

   $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
  }

  //now delete group itself from #__mysms_groups
  $sql ="DELETE FROM #__mysms_groups WHERE id=$this->_id";

 	$this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
  }
}

}
?>