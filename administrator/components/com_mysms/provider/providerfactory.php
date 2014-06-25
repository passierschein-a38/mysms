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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/provider/providerfactory.php $
*
* $Id: providerfactory.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_PROVIDER_PROVIDERFACTORY_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_PROVIDER_PROVIDERFACTORY_PHP', 1 );

/**
*  ProviderFactory is for handling with provider objects
*
* @package MySMS
* @subpackage Provider
**/

class ProviderFactory
{

	/*
	 *  Array of all supported providers
	 */
     var $_providers;

     /*
      * Global db object
      */
	var $_db;

   /**
   *  The constructor creates an factory object and ini the provider array.
   * 
   */
      function  ProviderFactory()
      {      
      	$this->ReadAllProvider();      	
		$this->_db = &JFactory::getDBO();
		
      }

      function ReadAllProvider()
      {
      	$dirName = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;   
      	$handle = dir( $dirName );
      	
      	if( $handle === false )
      	{
      		return;
      	}
      	
      	while( false !== ($entry = $handle->read())) 
      	{
   			if( is_dir( $entry ) )
   			{
   				continue;
   			}
   			
   			if( !eregi( "mysms.provider.*.php", $entry ) )
   			{
   				continue;
   			}
   			
   			$providerName = substr( $entry, strlen( 'mysms.provider.'), strlen( $entry )  );
   			$providerName = substr( $providerName, 0, strlen( $providerName ) - strlen( '.php' ) );
   			
   			if( empty( $providerName ) )
   			{
   				continue;
   			}
   			
   			if( $providerName == 'provider' )
   			{
   				continue;
   			}
   			
   			require_once( $entry );
   			
   			$this->_providers[ $providerName ] = $entry;   			
		}
      }
      
   /**
   *  This function returns an provider instance identified by name, if no such provider exists, fals is returned
   *  @param string $name
   */
      function getInstance( $name )
      { 
      	 $name = strtolower( $name );     	
      	
        //check if provider exists
          if( isset($this->_providers[$name]) )
          {          	          
            //try to create an new object
             $provider = new $name();
                          
             //check object
             if( is_object( $provider ) )
             {
                   $provider->loadConfigFromDB(); //load config from db
                   return $provider;
             }
          }
          
          return false;
      }

   /**
   *  This function returns the active provider from database
   *  @param string $name
   */
      function getActiveInstance()
      {
        //get the current provider and its file name ( w2sms.php )
        $sql = "SELECT * FROM #__mysms_provider where active='1' LIMIT 1";

        $this->_db->setQuery($sql);

        if(  $this->_db->query() == false ) //check error
        {
            MySMSRedirect('com_mysms --> getActiveProvider: database query failed !!!');
            return;
        }

        //load databse object
        $row =  $this->_db->loadObject();
        return $this->getInstance( $row->name );
      }


   /**
   *  This function registers all providers at database, should called on installation
   *  @param string $name
   */
      function registerAllProvider()
      {
        foreach($this->_providers as $name => $file )
        {
          $obj = $this->getInstance($name);
          $obj->register();
        }
      }
}  //end class
?>