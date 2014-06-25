<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 253 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/components/com_mysms/mysms.frontend.php $
*
* $Id: mysms.frontend.php 253 2010-07-06 20:18:34Z axel $
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

if( defined( 'MYSMS_FRONTEND_MYSMS_CONTROLLER_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_FRONTEND_MYSMS_CONTROLLER_PHP', 1 );

/**
 *
 * Frontend Dispatcher for com_mysms
 *
 *
* @package MySMS
* @subpackage Frontend
 */
class mySmsFrontend
{
	/*
	 * task to proccess
	 * @var string
	 */
	var $task;

	/**
	 * parameter array
	 * @var array
	 */
	var $params;

	/**
	 * sms user object
	 * @var object smsUser
	 */
	var $user;

	/**
	 * html layer
	 * @var object mySmsFrontendHtml
	 */
	var $html;

	/**
	 * global database object
	 * @var object mosDatabase
	 */
	var $db;

	/**
	 * global mainframe object
	 * @var object mosMainframe
	 */
	var $mainframe;

	/**
	 * Error handler
	 * @var object mySMSError
	 */
	var $errorHandler;

	/**
	 * Constructor
	 *
	 * @param string $task task to execute
	 * @param array  $params array filled wiht parameter and globals like $database, $ItemId, $option
	 */
	function mySmsFrontend( $task = 'default', $params = array( ) )
	{
		$this->task 		= $task;
		$this->params 		= $params;
		$this->html			= new mySmsFrontendHtml();
		$this->errorHandler = new mySMSError();

		if( isset( $params['mySMSUser'] ) )
		{
			$this->user = $params['mySMSUser'];
		}

		if( isset( $params['mosDatabase'] ) )
		{
			$this->db = $params['mosDatabase'];
		}

		if( isset( $params['mosMainframe'] ) )
		{
			$this->mainframe = $params['mosMainframe'];
		}

		$tok = -99;

		if( isset( $_REQUEST['prtoken'] ) )
		{
			$tok = (string) $_REQUEST['prtoken'];
		}

		if( $tok != -99 )
		{
			if( $this->IsPostReload( $tok ) )
			{
				$this->errorHandler->Alert( JText::_( 'MYSMS_INVALID_POSTRELOAD_TOKEN' ) );
			}
		}

		$this->params['token'] = $this->CreateToken();
	}

	/**
	 * Execute
	 *
	 * The Execute method is the only method to call, it is the entry point.
	 * It check's if the given task exists, and is callable, if not the default
	 * task will be called by call_user_method
	 */
	function Execute( )
	{
		$method = 'Do' . ucfirst( strtolower( $this->task ) );

		if( !method_exists( $this, $method ) )
		{
			return $this->DoDefault();
		}

		if( !is_callable( array( $this, $method ) ) )
		{
			return $this->DoDefault();
		}

		call_user_func( array( $this, $method ) );
	}

	/**
	 * Check if we can handle the request
	 *
	 * This method checks if we can handle the given task.
	 *
	 * @return bool return true if the task is handable, otherwise false
	 */
	function CanHandle()
	{
		$method = 'Do' . ucfirst( strtolower( $this->task ) );

		if( !method_exists( $this, $method ) )
		{
			return false;
		}

		if( !is_callable( array( $this, $method ) ) )
		{
			return false;
		}

		return true;
	}

	/**
	 * Default
	 *
	 * The Default method, is called when no taks is given oder some unknwon.
	 * This method show the send panel. It also reload's the user data.
	 */
	function DoDefault()
	{
		$this->user->reload();
		$this->html->showConfigPanel( $this->params );
  		$this->html->showSendPanel( $this->user,  $this->params  );  		
	}

	/**
	 * Show configuration panel
	 *
	 * Show the user configuartion panel, here the user can setup his phonenumber and a comment.
	 * The comment field is optional
	 */
	function DoConfiguration()
	{
		//we are comming from the default task, redirect with get, strip params
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
		{							
			MySMSRedirect( $this->CreateRedirectUrl( 'configuration' ) );			
		}	
		
		$this->html->showConfigPanel( $this->params );
		$this->html->showUserConfigPanel( $this->user, $this->params );
	}

	/**
	 * Save new configuration
	 *
	 * Do save the new user configuration if submit button is pressed, otherwise cancel the action and redirect
	 * to the default view.
	 *
	 * @todo make the phonenumber length configable
	 */
	function DoSaveConfiguration()
	{
		//check if user press the cancel button
  		if( isset( $_POST['cancel_button'] ) )
  		{
  			MySMSRedirect( $this->CreateRedirectUrl() );
  		}

  		$number 	=  JRequest::getVar( 'myphonenumber' );
  		$comment 	=  JRequest::getVar( 'mycomment' );

  		//filter user input
  		$this->Filter($number);
  		$this->Filter($comment);

  		if( !is_string( $number) )
  		{
  			$this->errorHandler->Alert( JText::_( 'MYSMS_INVALID_PHONENUMBER' ) );
  		}

  		// a normal german mobile phone number has the length 11
  		if( strlen( $number ) < 9  )
  		{
  			$this->errorHandler->Alert( JText::_( 'MYSMS_INVALID_PHONENUMBER' ) );
  		}

		//create sql and query
  		$sql = "UPDATE /*com_mysms->dosaveconfiguration*/ #__mysms_joomlauser SET number='$number', comment='$comment' WHERE userid=". $this->user->joomlaID() ." AND id=". $this->user->mySmsID();
  		$this->db->setQuery( $sql );

	  	if( $this->db->query( $sql ) === false )
	  	{
	  		$this->errorHandler->Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' ) , $this->db->getErrorMsg() );
  	  	}

  	  	MySMSRedirect( $this->CreateRedirectUrl() , JText::_( 'MYSMS_CHANGES_SAVED' ) );
	}

	/**
	 * Show users smsarchiv
	 *
	 * Show all sended sms.
	 */
	function DoSmsarchiv()
	{
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
		{							
			MySMSRedirect( $this->CreateRedirectUrl( $this->task ) );			
		}	
		
		$mainframe = $this->params['mosMainframe'];				
		
		$limit    	= $mainframe->getUserStateFromRequest( "mysms.frontend.smsarchiv.limit", "limit", 25, "int" );
		$offset   	= JRequest::getVar( 'limitstart', 0, '', 'int' );
		
		$total = $this->user->_phoneBook->getArchiveTotalCount();		 		
		$pageNav = new JPagination( $total[0], $offset, $limit );
		
		$params = $this->params;
		$params['pageNav'] = $pageNav;
		
		$rows = $this->user->_phoneBook->getArchive( $offset, $limit  );
		$this->html->showConfigPanel( $this->params );		
 		$this->html->showSendSMS( $rows, $params, $this->user );
	}

	/**
	 * Show users phonebook
	 */
	function DoPhonebook()
	{		
		//we are comming from the default task, redirect with get, strip params
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
		{							
			MySMSRedirect( $this->CreateRedirectUrl( $this->task ) );			
		}	
		
		$pharse = JRequest::getVar( 'phrase', '' );	
		$this->Filter( $pharse );
		
		$mainframe = $this->params['mosMainframe'];
				
		$limit    	= $mainframe->getUserStateFromRequest( "mysms.frontend.phonebook.limit", "limit", 25, "int" );
		
		if( $limit == 0 )
		{
			$limit = $this->user->_phoneBook->getTotalEntryCount();
			$limit = $limit[0];
		}
		
		$offset   	= JRequest::getVar( 'limitstart', 0, '', 'int' );
		
		$params = array();
		$params = $this->params;
		$params['phrase'] = $pharse;
				
		$rows 		= $this->user->_phoneBook->getEntries( $offset, $limit, $pharse ); 	
		$total 		= $this->user->_phoneBook->getTotalEntryCount( $pharse );

		$pageNav = new JPagination( $total[0], $offset, $limit );			
		$params['pageNav'] = $pageNav;
		 			
		$this->html->showConfigPanel( $this->params );	
 		$this->html->showPhonebook( $rows, $params, $this->user );
	}
	
	function DoTemplate()
	{
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
		{							
			MySMSRedirect( $this->CreateRedirectUrl( $this->task ) );			
		}	
		
		$params = array();
		$params = $this->params;
				
		$rows = $this->user->_templates->getEntries();
		
		$this->html->showConfigPanel( $this->params );	
		$this->html->showTemplates( $rows, $params );		
	}
	
	function DoExportTemplates()
	{
		$entries = $this->user->_templates->getEntries();
		$handle = fopen( 'php://temp', 'r+' );

		foreach( $entries as $entry )
		{
			fputcsv( $handle , array( $entry->name, $entry->tmpl ), ';' );
		}

		rewind( $handle );
		$content = stream_get_contents($handle);
		fclose( $handle );

		header('Content-type: text/comma-separated-values');
		header('Content-Disposition: attachment; filename="templates.csv"');
		echo $content;
		exit();		
	}
	
	function DoImportTemplates()
	{
			
		if( !isset( $_FILES['templateFile'] ) )
		{
			MySMSRedirect( $this->CreateRedirectUrl( 'template' ) , JText::_( 'MYSMS_SMSAT_TEMPLATE_IMPORT_FAILED' ) );
		}

		$data = $_FILES['templateFile'];

		//try to open tmp file
		$handle = fopen( $data['tmp_name'], 'r' );
			
		if( !$handle )
		{
			MySMSRedirect( $this->CreateRedirectUrl( 'template' ) , JText::_( 'MYSMS_SMSAT_TEMPLATE_IMPORT_FAILED' ) );
		}
		
		while( ( $data = fgetcsv ( $handle, 1000, ";") ) !== false )
		{
			    list( $name, $tmpl ) = $data;
				
			    if( $this->user->_templates->Create( $name, $tmpl ) == false )
				{
					$this->errorHandler->Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' ) , $this->db->getErrorMsg() );
  				}
		}

		MySMSRedirect( $this->CreateRedirectUrl( 'template' ) , JText::_( 'MYSMS_SMSAT_TEMPLATE_IMPORT_SUCCESSFULLY' ) );		
	}
	
	function DoDeleteTemplate()
	{
		$tid =  JRequest::getVar( 'tid' );
  		$this->Filter($tid);
		$success = $this->user->_templates->Delete( (int)$tid  );
		
		$msg = 'MYSMS_SMSAT_TEMPLATE_DELETED_SUCCESSFULLY';
		
		if( false == $success )
		{
			$msg = 'MYSMS_SMSAT_TEMPLATE_DELETED_FAILED';
		}
		
		MySMSRedirect( $this->CreateRedirectUrl( 'template' ) , JText::_( $msg ) );	
	}
	
	function DoCreateTemplate()
	{
		$name =  JRequest::getVar( 't_name', '' );
		$this->Filter( $name );
		
		$val  =  JRequest::getVar( 't_tmpl', '' );
		$this->Filter( $val );
  				
		$msg = JText::_( 'MYSMS_SMSAT_TEMPLATE_CREATED_SUCCESSFULLY' );
		
		if( false == $this->user->_templates->Create( $name, $val ) )
		{
			$msg = JText::_( 'MYSMS_SMSAT_TEMPLATE_CREATED_FAILED' );
		}
		
		MySMSRedirect( $this->CreateRedirectUrl( 'template' ) , $msg );	
	}
	
	function DoEditTemplate()
	{
		
		$tid = JRequest::getVar( 'tid' );
		$this->Filter( $tid );
		
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
		{							
			MySMSRedirect( $this->CreateRedirectUrl( $this->task ) . '&tid=' . $tid );			
		}	
				
		$row = $this->user->_templates->Get( $tid );
		
  		$params = array();
		$params = $this->params;
  		  	
		$this->html->editTemplate( $row, $params );
	}
	
	function DoUpdateTemplate()
	{
		
		$tid = JRequest::getVar( 'tid' );
		$this->Filter( $tid );
		
		$name =  JRequest::getVar( 't_name', '' );
		$this->Filter( $name );
		
		$val  =  JRequest::getVar( 't_tmpl', '' );
		$this->Filter( $val );

		$success = $this->user->_templates->Update( $tid, $name, $val );
		
		$msg = 'MYSMS_SMSAT_TEMPLATE_UPDATE_SUCCESSFULLY';
		
		if( false == $success )
		{
			$msg = 'MYSMS_SMSAT_TEMPLATE_UPDATE_FAILED';
		}
		
	
  		MySMSRedirect( $this->CreateRedirectUrl( 'template' ) ,  JText::_( $msg ) );	
	}	
	
	/**
	 * Add a new phonebook entry
	 */
	function DoAddphonebookentry()
	{
		$url = 'index.php?';
		$url .= 'option=' . $this->params['option'];
		$url .= '&'; 
		$url .= 'ItemId=' . $this->params['ItemId'];
		$url .= '&'; 
		$url .= 'task=phoneBook';
						
 		//get name and number from form
 		$name 	= JRequest::getVar( 'contactname', '' );
 		$number = JRequest::getVar( 'contactnumber', '' );

  		$this->Filter( $name );
  		$this->Filter( $number );

  		if( $this->user->_phoneBook->addEntry( $name, $number ) == false )
  		{
  			$this->errorHandler->Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' ) , $this->db->getErrorMsg() );
  		}

  		MySMSRedirect( $this->CreateRedirectUrl( 'phoneBook' ), JText::_( 'MYSMS_PHONEBOOKENTRY_ENTRY_SUCCESSFULLY_ADDED' ) );
	}

	/**
	 * Delete a phonebook entry
	 *
	 * Delete a entry from users phonebook
	 */
	function DoDeletephonebookentries( )
	{
		$option = JRequest::getVar( 'option', 'mysms' );
		$ItemId = JRequest::getVar( 'ItemId', 0 );
				
		 //get a complete comma seperated list form id's ( database )
  		$ids = JRequest::getVar( 'phoneBookEntryList', array() );  	

  		if( empty( $ids ) )
  		{
  			MySMSRedirect( $this->CreateRedirectUrl( 'phoneBook' ) );
  		}
  		
  		 //something wrong
  		if( $id == -1 )
  		{
    		$this->errorHandler->Alert( JText::_( 'MYSMS_INTERNAL_ERROR' )  );
    		die;
  		}

  		foreach( $ids as $k => $id )
  		{
  			
  			foreach( $this->user->_groups->GetEntries() as $k => $v )
  			{
  				$v->deleteMember( $id );  				
  			}  			
  			
  			if( $this->user->_phoneBook->removeEntry( $id ) == false )
  			{
      			$this->errorHandler->Alert( JText::_( 'MYSMS_DEL_PHONEBOOK_ENTRY_FAILED' ) );
  			}	
  		
  		}
  		  	
  		MySMSRedirect( $this->CreateRedirectUrl( 'phoneBook' ) , JText::_( 'MYSMS_DEL_PHONEBOOK_ENTRY_SUCCESSFULLY' ) );
	}

	/**
	 * Show user groups
	 *
	 * Show the configured uers groups
	 */
	function DoUserGroup()
	{
			//we are comming from the default task, redirect with get, strip params
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
		{							
			MySMSRedirect( $this->CreateRedirectUrl( $this->task ) );			
		}
		
  		$pbRows = $this->user->_phoneBook->getEntries();
    	$grRows = $this->user->_groups->getEntries();
    	
    	$this->html->showConfigPanel( $this->params );	
    	$this->html->showUserGroups($pbRows, $grRows, $this->params, $this->user );
	}

	/**
	 * Delte a user group
	 *
	 */
	function DoDeleteusergroup()
	{
		  //get a complete comma seperated list form id's ( database )
  		$ids = JRequest::getVar( 'ids', '');
  		$this->Filter( $ids );
  		$ids_a = explode( ';', $ids ); //create a array of ids

	  $id = -1;
	  //check what delete button is clicked
  		foreach($ids_a as $entryid)
  		{
    		$button = "delete_button_$entryid";

    		if( isset( $_REQUEST[$button] ) ){  //we found the correct group
       			$id = $entryid;
       			break;
    		}
  		}

  		if( $id == -1 ){     //something wrong
    		$this->errorHandler->Alert( JText::_( 'MYSMS_INTERNAL_ERROR' ) );
  		}

		  //load group by id and than delete it
  		$g = new mySMSGroup();
  		$g->init($id);
  		$g->delete();

		  //reload sms user  groups
  		$this->user->_groups->reload();

  		MySMSRedirect( $this->CreateRedirectUrl( 'usergroup' ) );
	}

	/**
	 * Add a new user group
	 */
	function DoAddusergroup()
	{
		 //check if user canceld action, if true return
 		if( isset($_REQUEST['back_button'] ) )
 		{
 			MySMSRedirect( $this->CreateRedirectUrl() );
 		}

		  //now get min and max id set defaults to -99
  		$minID = JRequest::getVar( 'minID', -99);
  		$maxID = JRequest::getVar( 'maxID', -99);

  		//something strange, abrot here
  		if( $minID == -99 || $maxID == -99 )
  		{
    		$this->errorHandler->Alert( JText::_( 'MYSMS_ADD_GROUP_FAILED' ) );
  		}

	   //in request are checkboxes, the values are the userids ( userid=id from phonebook entry )
	   //now collect userids from request
   		$userIDS = array();

   		for($i=$minID; $i<=$maxID; $i++)
   		{
     		$id = 'userid_'.$i;

     		if( isset( $_POST[$id] ) )
     		{
       			$t = JRequest::getVar( $id, -99 );

       			if( $id == -99 )
       			{
          			$this->errorHandler->Alert( JText::_( 'MYSMS_ADD_GROUP_FAILED' ) );
		       	}else{
        			$userIDS[]=$t;
       			}
     		}
		}

	   //nothing selected
   		if( count($userIDS) == 0 )
   		{
   			$this->errorHandler->Alert( JText::_( 'MYSMS_ADD_GROUP_NO_SELECTION' ) );
   		}

   		$groupName =  JRequest::getVar( 'groupname', '');

		if( strlen($groupName) <= 1 )
		{
      		$this->errorHandler->Alert( JText::_( 'MYSMS_GROUPNAME_MISSING' ) );
   		}

   		$this->Filter( $groupName );

	  //create a new user group, if group exists it will be loaded otherwise it will be created
	  //if init is called with a non numeric value
  	  $group = new mySMSGroup();
  	  $group->init($groupName);

	  //now add every entry
  		foreach($userIDS as $userid)
  		{
    		if( $group->addMember( $userid ) === false )
    		{
      			$this->errorHandler->Alert( JText::_( 'MYSMS_GROUP_ADD_MEMBER_FAILED' ) );
    		}
  		}


    //reload sms user  groups
  	$this->user->_groups->reload();
  	MySMSRedirect( $this->CreateRedirectUrl( 'usergroup' ) );
  	
	}

	/**
	 * Import to users phonebook by a csv file
	 */
	function DoImportphonebook ()
	{					
		
		if( !isset( $_FILES['phonebook'] ) )
		{
			MySMSRedirect( $this->CreateRedirectUrl( 'phoneBook' ) , JText::_( 'MYSMS_PHONEBOOKIMPORT_FAILED' ) );
		}

		$data = $_FILES['phonebook'];

		//try to open tmp file
		$handle = fopen( $data['tmp_name'], 'r' );
		
		

		if( !$handle )
		{
			MySMSRedirect( $this->CreateRedirectUrl( 'phoneBook' ) , JText::_( 'MYSMS_PHONEBOOKIMPORT_FAILED' ) );
		}
		
		while( ( $data = fgetcsv ( $handle, 1000, ";") ) !== false ){
			list( $name, $number ) = $data;
				if( $this->user->_phoneBook->addEntry( $name, $number ) == false ){
					$this->errorHandler->Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' ) , $this->db->getErrorMsg() );
  				}
		}

		MySMSRedirect( $this->CreateRedirectUrl( 'phoneBook' ) , JText::_( 'MYSMS_PHONEBOOKIMPORT_SUCCESSFULLY' ) );
	}

	/**
	 * Export users phonebook in csv format
	 *
	 * @todo check php if > 5.1, it not do some error message stuff
	 */
	function DoExportphonebook()
	{
		//check here php version > 5.1
		$entries = $this->user->_phoneBook->getEntries();
		$handle = fopen( 'php://temp', 'r+' );

		foreach( $entries as $entry ){
			fputcsv( $handle , array( $entry->name, $entry->number ), ';' );
		}

		rewind($handle);
		$content = stream_get_contents($handle);
		fclose($handle);

		header('Content-type: text/comma-separated-values');
		header('Content-Disposition: attachment; filename="phonebook.csv"');
		echo $content;
		exit();
	}

	/**
	 * Send a sms
	 *
	 * The most important method form the component. Send a sms.
	 *
	 */
	function DoSendSms( )
	{
	    //check global maxsms parameter before sending
		$sql = " /*com_mysms:dosendsms select global sms litmit */
				SELECT value FROM #__mysms_config WHERE name='maxsms' LIMIT 1";

		$this->db->setQuery( $sql );

		//check result and output a message
  		if( $this->db->query( $sql ) == false )
  		{
    		$this->errorHandler->Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' ) , $this->db->getErrorMsg() );
  		}

  		$maxsms = $this->db->loadResult();

  		if( !is_null( $maxsms ) && $maxsms > 0 )
  		{
  			$limit = $maxsms;

		  	$sql = "SELECT COUNT(*) AS COUNTER FROM #__mysms_sendsms";
  			$this->db->setQuery($sql);

  			//check result and output a message
  			if( $this->db->query($sql) == false )
  			{
    			$this->errorHandler->Alert(  JText::_( 'MYSMS_SQLQUERY_ERROR' ) , $this->db->getErrorMsg() );
  			}

  	 		$row = $this->db->loadObject();

  	 		if( $row->COUNTER >= $limit  )
  	 		{
  	 			$this->errorHandler->Alert( JText::_( 'MYSMS_GLOBAL_LIMIT_REACHED' ) );
  	 		}
  		}

  		$msg = '';

		  //get input parameters
  		$sms_body = JRequest::getVar( 'sms_body', ''); //dont check body, if we want to send a empty sms it is ok
  		$sms_send = JRequest::getVar( 'sms_send', '');
  		$ad       = JRequest::getVar( 'ad', '');
  		$cod      = JRequest::getVar( 'cod', 0 ); 
  		
  		if( 0 != $cod )
  		{
  			$cod = 1;
  		}
  		
  		if( strlen( $sms_body ) == 0 )
  		{ 
  			$this->errorHandler->Alert( JText::_( 'MYSMS_MESSAGE' ) );
  		}
  		
		/*
		 *  check sms sender
		 	
  		if( strlen( $sms_send ) < 8 )
  		{
    		$this->errorHandler->Alert( JText::_( 'MYSMS_INVALID_SENDER' ) );
  		}
  		
  		*/

  		$sms_recv = JRequest::getVar( 'sms_recv', '' );
  		$this->Filter( $sms_recv );

	   //check sms recv
  		if( strlen( $sms_recv ) < 8  )
  		{
      		$this->errorHandler->Alert( JText::_( 'MYSMS_INVALID_RECIPIENT') );
      		die;
  		}

	  //append advertisment to sms
  		if( strlen( $ad ) > 0 )
  		{

	  		$bodyLen = strlen( $sms_body);
	  		$adLen   = strlen( $ad );
	  		$len = $bodyLen + $adLen;

	  		if( $len > 160 )
	  		{
				$sms_body = substr( $sms_body, 0, 159 - strlen( $ad ) );
				$sms_body .= "\n" . $ad;
	  		}else{
      			$sms_body .= "\n" . $ad;
	  		}
  		}

		  //now check if the sms body is not longer that 160
 		if( strlen( $sms_body ) > 160 )
 		{
  			$sms_body = substr( $sms_body, 0, 160 );
 		}

		  //get the current sms provider
		  //create provider factory
  			$factory = new ProviderFactory();
  			$provider = $factory->getActiveInstance();

		  //check provider here
  			if( $provider === false )
  			{
       			$this->errorHandler->Alert( JText::_( 'MYSMS_NO_ACTIVE_PROVIDER' ) );
       			die;
  			}

  			$errMsg = '';
  			$arr = explode( ";", $sms_recv );

  		foreach( $arr as $recv )
  		{
       		$recv = trim($recv);  //trim whitespaces

	       if( strlen( $recv ) < 8 )
	       {
    	        continue;
       	   }

		       //check user balance
       		if( $this->user->balance() <= 0  )
       		{
				$this->errorHandler->Alert( JText::_( 'MYSMS_NOT_SUFFICIENT_FUNDS' ) );
       		}


       		{ //trigger plugins if available
       			$dispatcher = JDispatcher::getInstance();
       			JPluginHelper::importPlugin( 'mysms' );

       			$data = array( 'message'  => $sms_body ,
       						   'sender'	  => $sms_send,
       						   'receiver' => $recv );

       			$result = $dispatcher->trigger( 'onSendSms', $data );


       			$abort = false;

       			foreach( $result as $code )
       			{
       				if( $code == false )
       				{
       					$abort = true;
       					break;
       				}
       			}

       			//a plugin has returned false, so we cancel the send process
       			if( $abort == true )
       			{
       				MySMSRedirect( $this->CreateRedirectUrl() ,  JText::_( 'MYSMS_PLUGIN_ABORT' ) );
       			}
       		}
       		
       		$ret = $provider->sendSMS( $sms_body, $sms_send, $recv, $msg );
       		
	       //sms sending failed
    	   if( $ret == false )
    	   {
    	   		$errMsg .= JText::sprintf( 'MYSMS_SEND_SMS_FAILED', $recv, $msg );
       		}else{

       		   //dont forget to update the user balance
            	$bal = (int) $this->user->balance();
            	$bal--;

            	if( $this->user->setBalance( $bal ) == false )
            	{
                	$this->errorHandler->Alert( JText::_( 'MYSMS_CHANGE_BALANCE_FAILED' ) );
            	}
       			
	           //sendind was ok, so archive it
    	       $provider->archiveSMS($sms_body, $sms_send, $recv);   	          	               	    

            	$errMsg .= JText::sprintf( 'MYSMS_SEND_SMS_SUCCESSFULLY',  $recv );
       		}
  		}

  		MySMSRedirect( $this->CreateRedirectUrl(),  $errMsg );
	}

	/**
	 * Filter a given string by referance
	 *
	 * @param string &$param to filter
	 */
	function Filter( &$param )
	{
		 $param = htmlentities( strip_tags( $param ) , ENT_QUOTES );
	}

	/**
	 * Check if we alreay know the given token
	 *
	 * @param string $token
	 * @return bool true if it is a post reload otherwise false
	 */
	function IsPostReload( $token )
	{		
		if( $_SERVER['REQUEST_METHOD'] != 'POST' )
		{
			return false;
		}
		
		//check if our session array exists, if not create it
		if( !isset( $_SESSION['prArray'] ) || !is_array( $_SESSION['prArray'] ) ){
    		$_SESSION['prArray'] = array();
		}

		//check if given token is already known, if true this is a post reload, otherwise post is ok
		if( isset( $_SESSION['prArray'][$token] ) ){
  			return true;
		}else if( isset( $_SESSION['prArrayCreated'][$token] ) ){
  			$_SESSION['prArray'][$token] = $token;
  			return false;
		}else{
			  //the token is not from com_mysms !!! maybe a attack
			$this->errorHandler->Alert( JText::_( 'MYSMS_POST_RELOAD_BLOCK' ) );
		}

		return false;
	}
	
	
	function CreateRedirectUrl( $task = '' )
	{
		$url = 'index.php?';
		$url .= 'option=' . $this->params['option'];
		$url .= '&'; 
		$url .= 'Itemid=' . $this->params['ItemId'];
		
		if( strlen( $task ) > 0 )
		{
			$url .= '&'; 
			$url .= 'task=' . $task;			
		}
		
		return $url;
	}

	/**
	 * Create a new token
	 */
	function CreateToken()
	{
  		if( !isset($_SESSION['prArrayCreated'] ) || !is_array($_SESSION['prArrayCreated']) ){
    		$_SESSION['prArrayCreated'] = array();
  		}

  		$tok = md5( uniqid( rand() ) );

		//save all created post token, a incomming post token must set in this array (prArrayCreated) and not set in prArray
  		$_SESSION['prArrayCreated'][$tok]=$tok;

  		return $tok;
	}
}//end class
?>