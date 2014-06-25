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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.phonebook.php $
*
* $Id: mysms.phonebook.php 203 2010-02-04 18:59:38Z axel $
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

if( defined( 'MYSMS_BACKEND_PHONEBOOK_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_PHONEBOOK_PHP', 1 );

/**
*  mySMS Phonebook class
*
* @package MySMS
* @subpackage Util
*/
class mySMSPhonebook
{
      var $_ownerID; //owner id from #__mysms_phonebook
	  var $_db; //refernce to the global database object

/**
s* The constructor creates a new user phonebook
*
**/
function mySMSPhonebook($id)
{
	$this->_db = &JFactory::getDBO();

    if( is_numeric( $id ) )
    {
        $this->_ownerID = $id;
    }
}
 
/**
* This function returns the user phonebook
*
**/
function getEntries( $offset = 0, $limit = 100, $search = null )
{
  //read joomla based user data
  $sql = "SELECT id, ownerid, number, name from #__mysms_phonebook WHERE ownerid=" . $this->_ownerID;

  if( !empty( $search ) )
  {
  	$sql .= " and number like '%" . $search . "%' or name like '%". $search . "%' ";  
  }
  
  $sql .= " limit $offset, $limit";
  
          
  $this->_db->setQuery($sql);

  if($this->_db->query() === false )
  {
     mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
     die;
  }

  return $this->_db->loadObjectList();
}

function getTotalEntryCount( $search = null )
{
	 //read joomla based user data
  $sql = "SELECT count(*) from #__mysms_phonebook WHERE ownerid=" . $this->_ownerID;
  
  if( !empty( $search ) )
  {
  	$sql .= " and number like '%" . $search . "%' or name like '%". $search . "%' ";
  }
  
  $this->_db->setQuery( $sql );

  if($this->_db->query() === false )
  {
     mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
     die;
  }

  return $this->_db->loadRow();
}

/**
* This function add's a new entry to the phonebook, return true if success otherwise false
*
* @param string name
* @param string number
**/
function addEntry( &$name, &$number )
{
  //check input
  if( strlen( $name ) <= 0 )
  {  	
    mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
    die;
  }

  $sql = "INSERT INTO #__mysms_phonebook VALUES(0, $this->_ownerID, '$number', '$name' )";
  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {  	
      mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
      die;
  }

  return true;
}


/**
* This function remove's a new entry from the phonebook, return true if success otherwise false
*
* @param int entryID
**/
function removeEntry($entryID)
{
  //check input
  if( !is_numeric($entryID) )
  {
       mySMSError::Alert( JText::_( 'MYSMS_PHONEBOOK_REMOVE_ENTRY_FAILED' ) );
       die;
  }

  //create sql
  $sql = "DELETE FROM #__mysms_phonebook WHERE id=$entryID AND ownerid=$this->_ownerID LIMIT 1";
  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
    mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
    die;
  }
  
  //remove from groups
  
  return true;
}

/**
* This function returns the complete user sms archive
*
**/
function getArchive( $offset, $limit )
{
 
	  //create sql to get all sended sms
    $sql = "SELECT * FROM #__mysms_sendsms WHERE userid=".$this->_ownerID . ' LIMIT ' . $offset .','.$limit;

    //setup query and check error
    $this->_db->setQuery($sql);

    if( $this->_db->query( ) == false )
    {
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
    }
    //load the sms and show it in html class
    $rows =  $this->_db->loadObjectList();

    //now try to replace number with names
     $sql ="SELECT name, number FROM #__mysms_phonebook WHERE ownerid=".$this->_ownerID;

    //setup query and check error
    $this->_db->setQuery($sql);

    if( $this->_db->query( ) == false )
    {
        mySMSError::Alert( JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
        die;
    }

    //load result object list
    $pb = $this->_db->loadObjectList();

    //replace numbers with names
    for($i=0; $i<= count($rows); $i++)
    {
      foreach($pb as $p )
      {
      	if( !isset( $row[$i] ) ){
      		continue;
      	}

        if( $rows[$i]->to == $p->number ){
          $rows[$i]->to = $p->name;
        }
      }
    }

    reset($rows);
    return $rows;  
}

/**
* This function returns the complete user sms archive
*
**/
function getArchiveTotalCount()
{
	$sql = "SELECT COUNT(*)  FROM #__mysms_sendsms WHERE userid=".$this->_ownerID;
	
	$this->_db->setQuery( $sql );

  	if($this->_db->query() === false )
  	{
    	 mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
     	die;
  	}

   return $this->_db->loadRow();	
}



}//end class
?>