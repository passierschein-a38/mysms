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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/provider.php $
*
* $Id: provider.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_PROVIDER_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_PROVIDER_PHP', 1 );


/**
*  Provider is the base class of all sms gateway provider
*
* @package MySMS
* @subpackage Provider
**/

class Provider
{

   /**
   *  The logical provider name
   *  @var string
   */
      var $_name;

   /**
   *  All parameters needed by the provder like loginname, hostname ....
   *  @var array
   */
      var $_params;
   /**
   *  The php filename in filesystem like w2sms.php
   *  @var string
   */
      var $_file;

	/*
	 *  crypto object
	 */
      var $_crypto;

      /*
       * db
       */
     var $_db;

   /**
   *  Constructor, setting up name, file and parameters (empty array)
   */
      function Provider()
      {
        $this->_name = 'Base SMS Provider';
        $this->_file = basename( __FILE__ );
        $this->_params = array();
        $this->_crypto = new mySmsCrypt();
        $this->_db = &JFactory::getDBO();
      }
/**
*  This function is to register a provider in database
*
**/
      function register()
      {

       //check input
       if( $this->_name == 'Base SMS Provider' )
       {
           return;
       }

        //first check if our provider is already registerd
        $sql = "SELECT id from #__mysms_provider WHERE name='" . $this->_name ."'";

        $this->_db->setQuery($sql);

        if( $this->_db->query() == false )
        {
            echo "<script> alert('com_mysms --> registerProvider: database query failed !!!'); window.history.go(-1); </script>\n";
            return;
        }

        //get number of datasets
        $count = $this->_db->getAffectedRows();

        //if provider doesn't exists, register it
        if( $count == 0 )
        {
			$params = $this->_crypto->Encode( $this->_params );
            $sql ="Insert Into #__mysms_provider Values( 0, '$this->_name', '$this->_file', '$params', '0')";

            $this->_db->setQuery($sql);

            if( $this->_db->query() == false )
            {
                echo "<script> alert('com_mysms --> registerProvider: database query failed !!!'); window.history.go(-1); </script>\n";
              exit();
            }
         }
      }

   /**
   *  The sendSMS is for sending a sms, this function must be reimplemented in all dirved classes
   *  @param string $text
   *  @param string $from
   *  @param string $to
   /* @param string $errMsg
   */

      function sendSms( $text, $from, $to, &$errMsg )
      {
      	return false; // return false, we are a dummy
      }

   /**
   *  The archiveSMS is for storing sms in database.
   *  @param string $text
   *  @param string $from
   *  @param string $to
   */
      function archiveSMS( $txt, $from, $to )
      {
          $sql = "SELECT id FROM #__mysms_provider WHERE active='1' LIMIT 1";

          $this->_db->setQuery($sql);

          if( $this->_db->query() === false )
          {
          	MySMSRedirect( 'index.php?option=com_mysms', JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
          }

          $row = $this->_db->loadObject();

          $user =& JFactory::getUser();
          $userId = $user->get('id');
          $sql = "INSERT INTO #__mysms_sendsms VALUES(0,   $userId ,  NOW(),  '$txt', '$from', '$to', $row->id )";

          //setup query and query
          $this->_db->setQuery($sql);

          if( $this->_db->query() === false )
          {
            MySMSRedirect( 'index.php?option=com_mysms', JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
          }

      }
   /**
   *  The loadConfigFromDB loads the config parameters form database.
   *  The config data is a serialzed string
   */

      function loadConfigFromDB()
      {
          //load config from db
           $sql = "SELECT params FROM #__mysms_provider WHERE name='$this->_name' LIMIT 1";

           $this->_db->setQuery($sql);

           if( $this->_db->query() === false )
           {
            MySMSRedirect( 'index.php?option=com_mysms', JText::_( 'MYSMS_SQLQUERY_ERROR' ) );
            die();
           }

           $obj = $this->_db->loadObject();

           //provider does not exists in database
           if( is_null( $obj ) )
           {
           		return;
           }

           $p = $this->_crypto->Decode( $obj->params );

           if( is_array($p) ){ //if not something wrong
               $this->_params = $p;
           }
      }

      /**
       * buildQuery
       *
       * http_build_query emulation for old php 4 installations
       *
       * @param array $params
       * @return string $query url encoded query string
       */
      function buildQuery( $params )
      {

        if( !is_array( $params ) )
        {
        	return $params;
        }

        /*if( function_exists( 'http_build_query') )
        {
        	return http_build_query( $params );
        }*/ 

        foreach( $params as $key => $val )
        {
			$query .= urlencode( $key ) . '=' . urlencode( $val ) . '&';
        }

        //remove last &
        $query = substr( $query, 0, strlen($query)-1);

        return $query;
      }

} //end Provider class
?>