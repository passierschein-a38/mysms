<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author:$
* $Rev:$
* $HeadURL:$
*
* $Id:$
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

if( defined( 'MYSMS_BACKEND_TEMPALTE_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_TEMPALTE_PHP', 1 );

class mySMSTemplate
{
	 var $_ownerID; //owner id from #__mysms_template
	 var $_db; //refernce to the global database object
	 

/**
s* The constructor creates a new user phonebook
*
**/
function mySMSTemplate( $id )
{
	
	$this->_db = &JFactory::getDBO();

    if( is_numeric( $id ) )
    {
        $this->_ownerID = $id;
    }
}

function getEntries()
{
	
	$rows = array();
	
	if( !is_numeric( $this->_ownerID ) )
	{
		return rows;
	}
	
 //read joomla based user data
  $sql = "SELECT tid, uid, name, tmpl from #__mysms_templates WHERE uid=" . $this->_ownerID;
  
  $this->_db->setQuery($sql);

  if($this->_db->query() === false )
  {
     mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
     die;
  }

  return $this->_db->loadObjectList();	
	
}

function Delete( $tid )
{
	//read joomla based user data
  $sql = sprintf( "DELETE from #__mysms_templates WHERE tid=\"%s\" AND uid=" . $this->_ownerID . " LIMIT 1", $tid );
  
  $this->_db->setQuery($sql);
  
  if($this->_db->query() === false )
  {
     return false;
  }  
	
  return true;
}

function Create( $name, $tmpl )
{
	if( strlen($name) == 0 )
	{
		return false;
	}
   
	if( strlen($tmpl) == 0 )
	{
		return false;
	}
	
	$sql = sprintf( 'INSERT INTO #__mysms_templates values( 0, %d, "%s", "%s" )', $this->_ownerID, $name, $tmpl );
	
	$this->_db->setQuery($sql);
  
	if($this->_db->query() === false )
    {
       mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
       die;
    }
     
     return true;
}

function Get( $tid )
{
	$sql = sprintf( "SELECT tid, name, tmpl FROM #__mysms_templates WHERE tid=%d AND uid=%d LIMIT 1", $tid, $this->_ownerID );
		
	    $this->_db->setQuery( $sql );

		//check result and output a message
  		if( $this->_db->query( $sql ) == false )
  		{
    		return false;
  		}
  		
  		$row = $this->_db->loadObject();
  		
  		return $row;
	
}


function Update( $tid, $name, $tmpl )
{
	$sql = sprintf( "UPDATE #__mysms_templates SET name='%s' , tmpl='%s' WHERE tid=%d AND uid=%d LIMIT 1", $name, $tmpl, $tid, $this->_ownerID );
		
	$this->_db->setQuery( $sql );

		//check result and output a message
  		if( $this->_db->query( $sql ) == false )
  		{
    		return false;
  		}
  		
  	return true;
  		
}
	 
} //end class
?>