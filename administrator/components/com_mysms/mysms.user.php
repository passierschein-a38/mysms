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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.user.php $
*
* $Id: mysms.user.php 252 2010-07-01 18:47:30Z axel $
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

if( defined( 'MYSMS_USER_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_USER_PHP', 1 );

/**
*  mySMS User class
*
 * @package MySMS
 * @subpackage Util
**/
class mySMSUser {

      var $_mysmsId; //user id from mysms_joomlauser table
      var $_joomBlocked; //bool if user is blocked
      var $_mysmsBlocked; //bool if user is blocked
      var $_credits; //user credit
      var $_joomId;  //joomla userid
      var $_valid; //true if user exists
      var $_name; //joomla name
      var $_userName; //joomla username
      var $_number; //phone number
      var $_comment; //mysms comment
      var $_phoneBook; //users phoenbook
      var $_templates; //sms templates
      var $_groups; //all users groups
	  var $_db; //reference the global db object

/**
* The constructor creates a new mysms user object function loads all entries from joomla user table, and all entries from mysms_joomlauser table and call html method
*
**/
function mySMSUser($id)
{

	$this->_db = &JFactory::getDBO();

	//	check input
    if( is_numeric( $id ) )
	{
     //setup default values
     $this->_joomId = $id;
     $this->_mysmsId = -99;
     $this->_joomBlocked = true;
     $this->_mysmsBlocked = true;
     $this->_valid = false;
     $this->_credits = -99;
     $this->_name = '';
     $this->_userName = '';
     $this->_comment = '';
     $this->_number = 0;
     $this->init();
     $this->_phoneBook = new mySMSPhonebook( $this->joomlaID() );
     $this->_groups = new mySMSUserGroups( $this->joomlaID() );
     $this->_templates = new mySMSTemplate( $this->joomlaID() );
    }
}

/**
* This function reads the use values from database
*
**/
function init()
{
  //read joomla based user data
  $sql = "SELECT * from #__users WHERE id=" . $this->_joomId;

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
        die;
  }

  $user = $this->_db->loadObject();

  //check if user is blocked
  if( $user->block == 0 )
  { //user not blocked
      $this->_joomBlocked = false;
  }

  //setup name
  $this->_name 		= $user->name;
  $this->_userName 	= $user->username;

  //read mysms based user data
  $sql = "SELECT id, number, comment, state, credits from #__mysms_joomlauser WHERE userid=" . $this->_joomId;

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
        die;
  }

  $myUser = $this->_db->loadObject();

  //no entry found in table
  if( is_null( $myUser ) )
  {
		return;
  }

  if( $myUser->state == 1 )
  { //user is allowed to send sms
     $this->_mysmsBlocked = false;
  }

  $this->_number = $myUser->number;
  $this->_mysmsId = $myUser->id;
  $this->_credits = $myUser->credits;
  $this->_comment = $myUser->comment;
  $this->_valid = true;
}

/**
*  This function is a dummy, it calls int. It is only for better code reading
*
**/
function reload()
{
  $this->init();
}

/**
* This function returns true if user is blocked in joomla user administration or
* is not allowed to send sms ( MySMS user administration )
*
**/

function isBlocked()
{
  if( $this->_valid == false ){
    return true;
  }

  if( $this->_joomBlocked == true ){
    return true;
  }

  if( $this->_mysmsBlocked == true ){
    return true;
  }

  return false;
}

/**
* This function returns the user credits
*
**/

function balance()
{
  return $this->_credits;
}

/**
* This function returns true if the new balance can be set otherwise false
*
**/

function setBalance($credits)
{
  //check input
  if( !is_numeric( $credits ) ){
    return false;
  }

 //check if user is blocked
  if( $this->isBlocked() == true ){
    return false;
  }

  $sql = "UPDATE #__mysms_joomlauser SET credits=".$credits." WHERE id=".$this->_mysmsId."  AND userid=".$this->_joomId;

  $this->_db->setQuery($sql);

  if( $this->_db->query() === false )
  {
        mySMSError::Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' )  );
        die;
  }

  $this->_credits = $credits;

  return true;
}

/**
* This function returns the user phone number
*
**/

function number()
{
  return $this->_number;
}

/**
* This function returns the joomla userid
*
**/

function joomlaID()
{
  return $this->_joomId;
}

/**
* This function returns the mysms userid
*
**/

function mySmsID()
{
  return $this->_mysmsId;
}

/**
* This function returns the username
*
**/

function userName()
{
  return $this->_userName;
}

/**
* This function returns the user comment
*
**/

function comment()
{
  return $this->_comment;
}

}//end class
?>