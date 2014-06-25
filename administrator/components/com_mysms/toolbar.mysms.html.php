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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/toolbar.mysms.html.php $
*
* $Id: toolbar.mysms.html.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_BACKEND_TOOLBAR_HTML_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_TOOLBAR_HTML_PHP', 1 );

/**
*  TOOLBAR_MySMS is for the toolbar in backend
*
 * @package MySMS
 * @subpackage Backend
**/

class mySmsToolBarHtml
{

         /**
          * Toolbar for editing a user
          *
          */
         function EditUser()
         {
         	JToolbarHelper::save();
			JToolbarHelper::spacer();
			JToolbarHelper::cancel();
         }

       /**
        * User Default toolbar
        *
        */
        function UserDefault()
        {
			JToolbarHelper::spacer();
			JToolbarHelper::custom( 'loadListPanel'	,  'forward.png'	, 'forward_f2.png'	, 'MYSMS_LOADLIST' 	  );
			JToolbarHelper::custom( 'publish'		,  'apply.png'		, 'apply_f2.png'	, 'publish'    );
			JToolbarHelper::custom( 'unpublish'		,  'trash.png'		, 'trash_f2,png'	, 'unpublish' );
            JToolbarHelper::editListX();
	        JToolbarHelper::cancel();
        }

        /**
         * Default Provider Toolbar
         *
         */
        function ProviderDefault()
        {
			JToolbarHelper::spacer();
            JToolbarHelper::editListX();
	        JToolbarHelper::cancel();
        }

        /**
         * Toolbar when editing a provider
         *
         */
        function EditProvider()
        {
			JToolbarHelper::save();
			JToolbarHelper::spacer();
	        JToolbarHelper::cancel();
        }

        /**
         * Default toolbar when editing the advertisment
         *
         */
        function AdDefault()
        {
			JToolbarHelper::save();
			JToolbarHelper::spacer();
	        JToolbarHelper::cancel();
        }

        /**
         * Default toolbar for global settings
         *
         */
        function GlobalDefault()
        {
        	JToolbarHelper::save();
	        JToolbarHelper::cancel();
        }

        /**
         * Show only a back button
         *
         */
        function AboutDefault()
        {
	        JToolbarHelper::cancel();
        }
} //end class
?>