<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 257 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/components/com_mysms/mysms.html.php $
*
* $Id: mysms.html.php 257 2010-07-26 16:34:24Z axel $
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

if( defined( 'MYSMS_FRONTEND_MYSMS_HTML_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_FRONTEND_MYSMS_HTML_PHP', 1 );


/**
*  mySmsFrontendHtml is the html frontend class for com_mysms
*
* @package MySMS
* @subpackage Frontend
**/
class mySmsFrontendHtml
{

/**
*  This function returns the java script code for the frontend
*  adlen +1 because \n
*
**/
  function JS()
  {
?>

<script type="text/javascript">
window.addEvent('domready', function(){
	var mySlide = new Fx.Slide('mysmsPanel');
	
	$('toggle').addEvent( 'click', function(e){
		e = new Event(e);
		mySlide.toggle();
		e.stop();

		if( $('toggle').src.match( 'menu-close' ) )
		{
			$('toggle').src = $('toggle').src.replace( 'menu-close', 'menu-open' );
		}else{
			$('toggle').src = $('toggle').src.replace( 'menu-open', 'menu-close' );
		}
	});
});
 
</script>

  <script type="text/javascript">
  <!--
  function SMSCharCount(adlen)
  {	  
	  var maxlen = 160;

	  if( adlen > 0 )
	  {
		maxlen = maxlen - ( adlen+1 );  
	  }
	  	  
	  sub = $('sms_body').value;
 	  
	  if( sub.length > 160 ) 
	  {
		  sub = sub.substring( 0, maxlen );
		  $('sms_body').value = sub;		  
	  }

	  $('sms_charcounter').value = maxlen - $('sms_body').value.length;

	  $('sms_body').focus();
  }

  function SMSCharCountEx()
  {	  
	  var maxlen = 160;

      adLen = 0;

      if( null != $('ad') ) 
      {
	        adLen = $('ad').value.length;
      }	  

	  if( adLen > 0 )
	  {
		maxlen = maxlen - ( adLen+1 );  
	  }
	  	  
	  sub = $('sms_body').value;
 	  
	  if( sub.length > 160 ) 
	  {
		  sub = sub.substring( 0, maxlen );
		  $('sms_body').value = sub;		  
	  }

	  $('sms_charcounter').value = maxlen - $('sms_body').value.length;
  }
  

  function checkInput(adlen){

	  var maxlen = 160;

	  if( adlen > 0 )
	  {
		maxlen = maxlen - ( adlen+1 );  
	  }
	  	  
	     
     var body = document.getElementById('sms_body');

     if( body.value.length > maxlen ){
       alert('Maximal' + maxlen + 'Zeichen');
       return false;
     }

     var sender = document.getElementById('sms_send');

     if( sender.value.length <= 0 ){
       alert('Bitte Absender eintragen');
       return false;
     }

     var recv = document.getElementById('sms_recv');

     if( recv.value.length <= 0 ){
       alert('Bitte Empfänger eintragen');
       return false;
     }


     return true;
  }

  function resetPhoneBook()
  {
    var phoneBook =  $('phonebookentry');
    
    if( phoneBook )
     {
        for( var i=0; i<phoneBook.length; i++)
        {
             var element = phoneBook[i];
             
             if( element )
             {
                 element.selected = 0;
             }
        }
    }
  }

  function addReceiver( number )
  {
    if( number )
    {
        var recv = $('sms_recv');
        recv.value += number;
        recv.value += ';';
    }
  }

  function resetReceiver(){
    var recv =  $('sms_recv');
    recv.value = '';
  }

  function updateRecv()
  {
 

   //reset group box and recv field
   resetReceiver();

   //get phonebook and recv field
   var phoneBook = $('phonebookentry');

   //check all items in phonebook if selected, if true add number to recv field
   for( var i=0; i<phoneBook.length; i++){
       var element = phoneBook[i];
       if( element ){
           if( element.selected ){
               addReceiver( element.value );
            }
       }
   }
  }
 
  function SelectGroup( groupId )
  {
    //first reset our phonebook
    
    $('filter').value = '';
    
    resetPhoneBook();
    resetReceiver();

    //Find the selected group object by groupid
    var group = null;
    for( var idx = 0; idx < MySMSPhoneBookGroups.length; idx++ )
    {
        var object = MySMSPhoneBookGroups[idx];

        if( object.groupId == groupId )
        {
			group = object;
			break;
        }
    }
  
    //something went wrong
    if( group == null )
    {
        return false;
    }

    //now create a array of option element with fitting group member data
    var len = 0;
    var optionArray = new Array();
    
    //for all group members
    for( var idx = 0; idx < group.members.length; idx++ )
    {
		var memberId = group.members[idx];

		//find a member in the whole phonebook
		for( var idx2 = 0; idx2 < MySMSPhoneBook.length; idx2++ )
		{

			var entry = MySMSPhoneBook[idx2];
			
			if( entry == null )
			{
				continue;
			}
					
			//match found, create html element and save it
			if( entry.id == memberId )
			{
				var option = document.createElement( 'option' );
				option.text  = entry.name + ' (' + entry.number + ')'  ;
				option.value = entry.number;	
				option.selected = 1;
		
				optionArray[len] = new Object();
				optionArray[len] = option;
			
				len++;					
			}
		}				       
    }

      
    //Remove all entries
	$('phonebookentry').length = 0;

	//rebuild option select
	for( var idx = 0; idx < optionArray.length; idx++ )
	{
		var el = optionArray[idx];
		$('phonebookentry').add( el, null );
	}

	updateRecv();

	return false;
	
  }//function


  function TemplateDelete( id )
  {
	  $('postform').task.value = 'deleteTemplate';
	  $('postform').tid.value = id;
	  $('postform').submit();

	  return false;
  }

  function TemplateEdit( id )
  {
	  $('postform').task.value = 'editTemplate';
	  $('postform').tid.value = id;
	  $('postform').submit();

	  return false;
  }

  function OnTemplateCreateCheck()
  {
	  
	  name = $('createTemplateForm').t_name.value;
	  val  = $('createTemplateForm').t_tmpl.value;

	  if( name.length > 0 && val.length > 0 ) 
	  {
		  $('createTemplateForm').createTemplateButton.disabled = 0;  
	  }else{
		  $('createTemplateForm').createTemplateButton.disabled = 1;
	  }	  	  
  }

  function OnTemplateEditCheck()
  {
	  name = $('editTemplateForm').t_name.value;
	  val  = $('editTemplateForm').t_tmpl.value;

	  if( name.length > 0 && val.length > 0 ) 
	  {
		  $('editTemplateForm').editTemplateButton.disabled = 0;  
	  }else{
		  $('editTemplateForm').editTemplateButton.disabled = 1;
	  }	  	  
  }
  
  function TemplateCreate()
  {	  
	  $('postform').task.value = 'createTemplate';
	  $('postform').t_name.value = $('createTemplateForm').t_name.value;
	  $('postform').t_tmpl.value = $('createTemplateForm').t_tmpl.value;
	  $('postform').submit();

	  return false; 
  }

  function TemplateUpdate()
  {	  
	  $('editTemplateForm').submit();	  
  }


  function TemplateSelection()
  {
	  $('sms_body').value = '';
	  
	  tid =  $('tmplselect').value;
	  
	   for( var idx = 0; idx < MySMSTemplates.length; idx++ )
	   {
	        var object = MySMSTemplates[idx];

	        if( object.tid == tid )
	        {
		        $('sms_body').value = object.tmpl;		        
		        SMSCharCountEx();
	        }
	    }	  	  
  }

  function CheckTemplateImport()
  {
	  $('importTemplateButton').disabled = ( $('templateFile').value.length < 1 );
  }
  
  function ExportTemplates()
  {
	  $('postform').task.value = 'exportTemplates';	  
	  $('postform').submit();

	  return false;
  }

  function ImportTemplates()
  {
	  $('importForm').task.value = 'importTemplates';	  
	  $('importForm').submit();

	  return false;
	  
  }
  
//-->
</script>

<?php
}  //end function JS

/**
* This function shows an error panel with variable messages
*
*  @param string msg
**/
 function showSMSSendErrorMessage( &$msg )
 {
  ?>
    <div class="componentheading">
            <?php echo JText::_( 'MYSMS_ERROR' ); ?>:
    </div>

    <table border="0" width="100%">
           <tr>
               <td align="center" valign="top">
                   <p>
                      <b>
                            <?php echo $msg; ?>
                      </b>
                   </p>
               </td>
           </tr>
    </table>
  <?php
}  //end function    showSMSSendErrorMessage


function MakeTopPanelEntry( $img, 
							$task, 
							$text,
							$option,
							$itemID )
{
	
$link = 'option=' . $option . '&Itemid='.$itemID;

if( strlen( $task ) )
{
	$link .= '&task='.$task;
}
	
?>
<a href="?<?php echo $link;?>">
	<img align="middle" src="./media/com_mysms/images/<?php echo $img ?>" border="1" alt="<?php echo $text; ?>" />	
</a>
<a href="?<?php echo $link;?>" style="padding-right: 5px;">
	<?php echo $text; ?>
</a>	
<?php
 
}

function showConfigPanel( &$params )
{
    $mosParameters  = $params['mosParameters'];
	$option			= $params['option'];
	$Itemid			= $params['ItemId'];
?>

<div id="toggler" style="float: right; width: 30px;" />  
<img src="./media/com_mysms/images/menu-close.png" id="toggle"/>
</div>
<div id="mysmsPanel"  style="text-align: center; padding-bottom: 15px; clear: both;">
<?php 
$this->MakeTopPanelEntry( 'home.png'      , ''              , JText::_( 'MYSMS_HOME' )         ,$option, $Itemid );
$this->MakeTopPanelEntry( 'configure.png' , 'configuration' , JText::_( 'MYSMS_MY_CONFIG' )    ,$option, $Itemid );
$this->MakeTopPanelEntry( 'archive.png'   , 'smsarchiv'     , JText::_( 'MYSMS_SMSARCHIVE' )   ,$option, $Itemid );
$this->MakeTopPanelEntry( 'template.png'  , 'template'      , JText::_( 'MYSMS_SMSAT_TEMPLATE' )   ,$option, $Itemid );
$this->MakeTopPanelEntry( 'phonebook.png' , 'phonebook'     , JText::_( 'MYSMS_MY_PHONEBOOK' ) ,$option, $Itemid );
$this->MakeTopPanelEntry( 'usergroup.png' , 'usergroup'     , JText::_( 'MYSMS_USER_GROUP' ) ,$option, $Itemid );
?>
</div>


<?php 
}
   

/**
* This function shows the send panel
*
*  @param mosParameters params
*  @param class user
*  @param string msg
**/
function showSendPanel( $smsUser , $params  )
{
	$mosParameters  = $params['mosParameters'];
	$option			= $params['option'];
	$Itemid			= $params['ItemId'];
	$msg			= isset( $params['msg'] )?$params['msg']:'';
	$tok 			= $params['token'];

	$c = $smsUser->_phoneBook->getTotalEntryCount();
    $phonebook 	= $smsUser->_phoneBook->getEntries( 0, $c[0]  );
    $templates  = $smsUser->_templates->getEntries();
          
    //build javascript object array    
?>

<script type="text/javascript">
<!--

var MySMSTemplates = Array();

<?php 

   $count = 0;
   
   if( is_array( $templates ) ) 
   {
   
		foreach( $templates as $key => $val )
		{		
			echo "MySMSTemplates[$count] = new Object();";
			echo "MySMSTemplates[$count][\"tid\"] = \"" . $val->tid . "\";";
			echo "MySMSTemplates[$count][\"name\"] = \"" . $val->name . "\";";
			echo "MySMSTemplates[$count][\"tmpl\"] = \"" . $val->tmpl . "\";";		
			$count++;		
		}
	
		reset( $templates );
   } //if is array
?>

var MySMSPhoneBook = new Array();

<?php

	$count = 0;
	
	if( is_array( $phonebook ) )
	{ 
		
		foreach( $phonebook as $key => $val )
		{		
			echo "MySMSPhoneBook[$count] = new Object();";
			echo "MySMSPhoneBook[$count][\"id\"] = \"" . $val->id . "\";";
			echo "MySMSPhoneBook[$count][\"number\"] = \"" . $val->number . "\";";
			echo "MySMSPhoneBook[$count][\"name\"] = \"" . $val->name . "\";";
			echo "MySMSPhoneBook[$count][\"dispaly\"] = \"" . $val->name . " ( " . $val->number . " ) \";";
		
			$count++;		
		}
	
		reset( $phonebook );
	} //if is array
?>

function FilterPhoneBook()
{
	
	//Remove all entries
	$('phonebookentry').length = 0; 

	//get len and trim
	var val = $('filter').value.trim();
	var len = val.length;

	//add all entries
	if( val.length == 0 )
	{
		for( var idx = 0; idx < MySMSPhoneBook.length; ++idx )
		{
			var object = MySMSPhoneBook[idx];
			var option = document.createElement( 'option' );
			option.text  = object.name + ' (' + object.number + ')'  ;
			option.value = object.number;
			$('phonebookentry').add( option, null );
		}

		return;
	}

	var bEmpty = true;
	
	//apply the filter
	for( var idx = 0; idx < MySMSPhoneBook.length; ++idx )
	{
		var object = MySMSPhoneBook[idx];

		if( object.name.test( val, "i" ) )
		{
			var option = document.createElement( 'option' );
			
			option.text  = object.name + ' (' + object.number + ')'  ;
			option.value = object.number;

			$('phonebookentry').add( option, null );
			bEmpty = false;
			continue;
		}

		if( object.number.test( val, "i" ) )
		{
			var option = document.createElement( 'option' );
			
			option.text  = object.name + ' (' + object.number + ')'  ;
			option.value = object.number;

			$('phonebookentry').add( option, null );
			bEmpty = false;
			continue;
		}				
	}    

	if( bEmpty == true )
	{
	      var el = document.createElement( 'option' );
	      el.text  = 'No Matches';
	      el.value = '';
	      $('phonebookentry').add( el, null );
	}	
}

-->
</script>

<?php 
$groups = $smsUser->_groups->getEntries();
?>

<script type="text/javascript">
<!--
var MySMSPhoneBookGroups = new Array();

<?php

	$count = 0;
	
	if( is_array( $groups ) ) 
	{
	
		foreach( $groups as $key => $val )
		{		
			echo "MySMSPhoneBookGroups[$count] = new Array();";
			echo "MySMSPhoneBookGroups[$count][\"groupId\"] = \"" . $val->_id . "\";" . "\r\n";
			echo "MySMSPhoneBookGroups[$count][\"groupName\"] = \"" . $val->_name . "\";" . "\r\n";		
			echo "MySMSPhoneBookGroups[$count][\"members\"] = new Array();";
		
			$idx = 0;
		
			foreach( $val->_members as $member )
			{
				echo "MySMSPhoneBookGroups[$count][\"members\"][$idx] = " . $member->id  . ";" . "\r\n";
				$idx++;
			}
				
			$count++;	
		}
	
		reset( $groups );	
	} //if is array	
	

	reset( $groups );
?>

-->
</script>


<?php     
    $config		= new mySmsConfig();
    $ad			= $config->Get( 'advertisment' );
    $policy     = $config->Get('Policy');   
    $adLen 		= strlen( $ad );
?>
    <form action="index.php" method="post" onsubmit="return checkInput();">
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
    <input type="hidden" name="task" value="sendSMS" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    <input type="hidden" name="prtoken" value="<?php echo $tok; ?>" />
<?php
     //check if a msg is given, if show it
     if( strlen( $msg ) > 1 ){
       echo $msg;
     }
?>
       <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            SMS <?php echo JText::_( 'MYSMS_DISPATCH' );?>: <?php echo $smsUser->_name; ?> ( <?php echo JText::_( 'MYSMS_BALANCE') ;?>: <?php echo $smsUser->balance(); ?> )
       </div>

      <table border="0" class="adminform" cellpadding="0" cellspacing="0">
	  	<tr>
	    	<td width="15%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
	    		&nbsp;
	    	</td>
	    	<td width="45%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
	    		&nbsp;
	    	</td>
	    	<td width="10%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
	    		&nbsp;
	    	</td>
        	<td  width="30%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
        		&nbsp;
        	</td>
	  	</tr>
      	<tr>
        	<td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            	<i>SMS <?php echo JText::_( 'MYSMS_MESSAGE' );?></i>
            </td>
            <td align="center" valign="top"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            	<textarea onfocus="SMSCharCount( <?php echo $adLen; ?> );" onchange="SMSCharCount( <?php echo $adLen; ?> );" onkeyup="SMSCharCount( <?php echo $adLen; ?> );"  onkeypress="SMSCharCount( <?php echo $adLen; ?> );" onkeydown="SMSCharCount( <?php echo $adLen; ?> );"  id="sms_body" name="sms_body" rows="10" cols="40" style="width: auto; height: auto;" ></textarea>
            </td>
            <td align="center" valign="top"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
               <input onclick="SMSCharCount( <?php echo $adLen; ?> );"  value="<?php echo (160 - $adLen); ?> " size="3" type="text" name="sms_charcounter" id="sms_charcounter" readonly="readonly" />
            </td>
            <td rowspan="6" valign="top" align="center"  >
            	<b><?php echo JText::_( 'MYSMS_CONTACTS' ); ?> ( <?php echo count( $phonebook ); ?> )</b><br/>
            	<input style="width: 150px;" type="text" id="filter" onmouseup="FilterPhoneBook();" onkeyup="FilterPhoneBook();"  />
            	<br/>
            	<select multiple="multiple" onchange="updateRecv();"  id="phonebookentry" name="phonebookentry" size="15" style="width: 150px;">
<?php
			     //create phonebook
     			foreach($phonebook as $entry)
     			{
?>
         			<option value="<?php echo $entry->number; ?>">
            			<?php echo $entry->name . ' (' . $entry->number . ')'; ?>
         			</option>
<?php
			     } //end loop
?>
      			</select>
<?php 
				$groups = $smsUser->_groups->getEntries();
				
				if( false == empty( $groups) )
				{
					echo '<div align="left">';
					echo '<ul style="list-style-image:url(./images/mysms/group.png); margin-top: 10px;">';

					foreach( $groups as $group )
					{
						echo '<li style="margin-top: 5px;">
								<a href="#" onclick="return SelectGroup(' . $group->_id .');"> '  . $group->_name . '</a>';
						echo '</li>'; 
					}
					
					echo '</ul>';
					echo '</div>';
				}
?>      			
            </td>
          </tr>
          
<!-- Policy -->           
          
             <?php if( strlen( $policy ) > 0){?>
                    <tr>
               <td colspan="3"><br/>
               </td>
          </tr>

          <tr>

          <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
          	<?php echo JText::_( 'MYSMS_POLICY' );?>:
          </td>

          <td colspan="3">
          	<textarea style="height: auto; width: 300px;" name="ad" id="ad" readonly="readonly" rows="1" cols="35"><?php echo  $policy; ?></textarea>
          </td>
          </tr>
		  <tr>
               <td colspan="3">
               	<br/>
               </td>
          </tr>
          <?php } ?>
          
<!-- Policy End -->           
          
<!-- Advertisment --> 
 
          <?php if($adLen > 0){?>
                    <tr>
               <td colspan="3"><br/>
               </td>
          </tr>

          <tr>

          <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
          	<?php echo JText::_( 'MYSMS_ADVERTISMENT' );?>:
          </td>

          <td colspan="3">
          	<textarea style="height: auto; width: 300px;" name="ad" id="ad" readonly="readonly" rows="1" cols="35"><?php echo  $ad; ?></textarea>
          </td>
          </tr>
		  <tr>
               <td colspan="3">
               	<br/>
               </td>
          </tr>
          <?php } ?>
          
<!-- Advertisment End -->          
          
          <?php 
          
          
          
          if( count($templates) > 0 )
          {
          ?>
		<tr>
            <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                
            </td>
            <td align="right"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            <select onchange="TemplateSelection();" id="tmplselect" name="tmplselect" size="1">
            <option  value="0">&nbsp;&nbsp;&nbsp;&nbsp;</option>
            
            <?php 
            
            foreach($templates as $key => $val )
            {
            	echo '<option value="' . $val->tid . '">' . $val->name .'</option>';
            }
            
            ?>
            
            
            </select>
            
            
            </td>
            <td   class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            </td>
          </tr>          
          
          <?php } ?>
          
          <tr>
            <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <i><?php echo JText::_( 'MYSMS_SENDER' );?></i>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            <div style="text-align: center;">
                <input readonly="readonly" type="text" id="sms_send" name="sms_send" size="47" value="<?php echo $smsUser->number(); ?>" />
               </div>
            </td>
            <td   class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            </td>
          </tr>
          <tr>
               <td colspan="3"><br/>
               </td>
          </tr>
          <tr>
            <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <i><?php echo JText::_( 'MYSMS_RECIPIENT' ); ?></i>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            <div style="text-align: center;">
                <input type="text" id="sms_recv" name="sms_recv" size="47" />
             </div>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            </td>
          </tr>
                  <tr>
               <td colspan="3"><br/>
               </td>
          </tr>

          <tr>

              <td>          </td>
              <td>
              <input type="submit" name="send_button" value="<?php echo JText::_( 'MYSMS_SEND'); ?>" />
              </td>
              <td>

              </td>
          </tr>

	  </table>
       </form>
                   
<?php

	$this->JS();

}      //end function       showSendPanel


/**
*  This function is for showing the user config panel.
*
*  @param mosParameters params
*  @param array row
**/
function showUserConfigPanel($smsUser, $params)
{
	$mosParameters  = $params['mosParameters'];
	$option			= $params['option'];
	$Itemid			= $params['ItemId'];

    $this->JS();
?>
       <form action="index.php" method="post">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="saveConfiguration" />
       <input type="hidden" name="boxchecked" value="0" />
       <input type="hidden" name="hidemainmenu" value="0" />


 <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_MY_CONFIG' ); ?>: <?php echo $smsUser->userName(); ?>
       </div>
                 <br/>
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">

          <tr>
            <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <i><?php echo JText::_( 'MYSMS_PHONENUMBER' ); ?>:</i>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <input type="text" name="myphonenumber" id="myphonenumber" size="20" value="<?php echo $smsUser->number();?>" />
            </td>

            <td>

            </td>
          </tr>

          <tr>
          <td colspan="3">
          <br/>
          </td>
          </tr>
           <tr>
            <td align="center" valign="top" class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <i><?php echo JText::_( 'MYSMS_COMMENT' );?>:</i>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <input type="text" name="mycomment" id="mycomment" size="20" value="<?php echo $smsUser->comment();?>" />
            </td>

            <td>

            </td>
          </tr>

          <tr>
          <td>

          </td>
            <td colspan="2"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
                <input type="submit" name="change_button" value="<?php echo JText::_( 'MYSMS_SAVE' ); ?>" />
                <input type="submit" name="cancel_button" value="<?php echo JText::_( 'MYSMS_CANCEL' ); ?>" />
            </td>
          </tr>

          </table>

          </form>

<?php

}  //end fucntion showUserConfigPanel

/**
*  This function is for showing the sms archive
*
*
*  @param mosParameters params
*  @param array rows
**/
function showSendSMS($rows, $params, $user)
{
	$this->JS();
	
	$mosParameters  = $params['mosParameters'];
	$option			= $params['option'];
	$Itemid			= $params['ItemId'];
	$pageNav		= $params['pageNav'];
?>
     <form action="index.php" method="get">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="smsarchiv" />

      <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_SMSARCHIVE' );?>: <?php echo $user->userName() ?>
       </div>
                 <br/>
         <table border="0" class="adminform" width="100%" cellpadding="1" cellspacing="0">
	  <tr>
	    <td width="25%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_DATE' ) ;?></b></td>
	    <td width="15%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_RECIPIENT' );?></b></td>
	    <td width="60%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_MESSAGE' );?></b></td>
	  </tr>

<?php
        foreach($rows as $sms )
        {
?>
        <tr>
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo $sms->senddate; ?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo $sms->to; ?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo wordwrap( $sms->text, 55, '<br/>', true );  ?><br/><br/></td>

        </tr>
        <tr>

        <td colspan="3" style="border-bottom: 1px solid #cccccc;" >

        </td>
        </tr>

<?php
        }
?>
          <tr>        
            <td valign="top" align="center" colspan="3"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            <?php echo $pageNav->getListFooter(); ?>
            </td>
          </tr>

          </table>

          </form>
<?php
}

/**
*  This function is for the phonebook config panel
*
*  @param mosParameters params
*  @param array rows
**/
function showPhonebook($rows, $params, $user)
{
	$this->JS();
	
	$mosParameters  = $params['mosParameters'];
	$option			= $params['option'];
	$Itemid			= $params['ItemId'];
	$tok			= $params['token'];
	$pageNav		= $params['pageNav'];
	$pharse			= $params['phrase'];
?>
                
	<div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_MY_PHONEBOOK' ) ;?>: <?php echo $user->userName() ?>
    </div>                
                
	<!-- Phonebook Entries -->                               
   <form id="phoneBookForm" action="index.php" method="get">
     
   <input type="text" name="phrase" size="20" value="<?php echo $pharse;?>"/>
   <input type="submit" value="<?php echo JText::_( 'MYSMS_SEARCH' ); ?>"/>	   
   <input type="hidden" name="option" value="<?php echo $option; ?>" />
   <input type="hidden" name="ItemId" value="<?php echo $Itemid; ?>" />
   <input type="hidden" name="task" value="phonebook" />

       <br/>
       
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">
	     <tr>
	     	<td width="15%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><input style="margin-left: 0px;" type="checkbox" id="phoneBookListToogleButton" name="phoneBookListToogleButton" onClick="ToogleCheckBox()"/></td>
	     	<td width="25%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_NAME' ); ?></b></td>
	    	<td width="50%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_PHONENUMBER' ); ?></b></td>
	  	</tr>

<?php
        foreach( $rows as $entry )
        {
?>
        <tr>
			<td class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
				<input class="check-me" type="checkbox" name="phoneBookEntryList[]" value="<?php echo $entry->id; ?>"/>           
            </td>
            
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           		<?php echo $entry->name; ?>
           </td>
           
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           		<?php echo $entry->number; ?>
           </td>           
        </tr>
        
        <tr>
        	<td colspan="3" style="border-bottom: 1px solid #cccccc;" ></td>
        </tr>

<?php
        } //for
?>

		
    	<tr>
    		<!-- Actions -->
      		<td colspan="1" align="left">
      			<input type="button" name="dPBB" id="dPBB" onClick="return DeletePhoneBookEntries();" value="<?php echo JText::_( 'MYSMS_DELETE' );?>"/>
			</td>
			
			<!-- Pager -->			
      		<td colspan="2" align="right" width="100%">
      			<?php echo $pageNav->getListFooter(); ?>
			</td>			
    	</tr>	
  		
       </table>
       </form>
       
         <script type="text/javascript">

         function ToogleCheckBox()
         {
        	 $$( '.check-me' ).each( function( el ) { el.checked = !el.checked; } ); 
         }

         function DeletePhoneBookEntries()
         {
        	 var el = $('phoneBookForm');
        	 el.method='post';
        	 el.elements['task'].value = 'DeletePhonebookEntries';        	 
        	 el.submit();
        	 return true;
         }
   		</script>
   	   	
       <br/><br/>

       <form action="index.php" method="post">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="AddPhoneBookEntry" />       
       <input type="hidden" name="prtoken" value="<?php echo $tok; ?>" />

 	   <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
       		<?php echo JText::_( 'MYSMS_NEW_ENTRY' ); ?>:
       </div>
                
      <br/>
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">

        <tr>
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_NAME' );?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <input type="text" name="contactname" />
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">

            </td>

        </tr>

                <tr>
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_PHONENUMBER' ); ?>:
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <input type="text" name="contactnumber" />
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">

            </td>

        </tr>


          <tr>
          <td>

          </td>
            <td colspan="2"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
               <br/> <input type="submit" value="<?php echo JText::_('MYSMS_SAVE'); ?>"  />
            </td>
          </tr>
          </table>
        </form>

  	   <form action="index.php" method="post" enctype="multipart/form-data">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="importPhonebook" />
       <input type="hidden" name="boxchecked" value="0" />
       <input type="hidden" name="hidemainmenu" value="0" />
       <input type="hidden" name="prtoken" value="<?php echo $tok; ?>" />

 <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
       <?php echo JText::_( 'MYSMS_IMPORT_PHONEBOOK' ); ?>:
       </div>
                 <br/>
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">

        <tr>
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_NAME' );?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           		<input name="phonebook" type="file"  />
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
            </td>
        </tr>
        <tr>
        	<td>
          	</td>
            <td colspan="2"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
               <br/> <input type="submit" name="save_button" value="<?php echo JText::_( 'MYSMS_IMPORT_PHONEBOOK' )?>" /><br/>&nbsp;
            </td>
          </tr>

          </table>

          </form>

	   <form action="index2.php" method="post" enctype="multipart/form-data">
	   <input type="hidden" name="no_html" value="1" />
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="exportPhonebook" />
       <input type="hidden" name="boxchecked" value="0" />
       <input type="hidden" name="hidemainmenu" value="0" />
       <input type="hidden" name="prtoken" value="<?php echo $tok; ?>" />

 		<div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
       <?php echo JText::_( 'MYSMS_EXPORT_PHONEBOOK' ); ?>:
       </div>
                 <br/>
                 
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">

        <tr>
        	<td>
          	</td>
            <td colspan="2"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
               <br/> <input type="submit" name="save_button" value="<?php echo JText::_( 'MYSMS_EXPORT' );?>" />
            </td>
          </tr>

          </table>

          </form>
<?php
} //end function phonebook


/**
*  This function is for user groups
*
*  @param mosParameters params
*  @param array rows
**/
function showUserGroups($phoneBookRows, $groupRows, $params, $user)
{
	 $this->JS();
	
	  $mosParameters    = $params['mosParameters'];
	  $option			= $params['option'];
	  $Itemid			= $params['ItemId'];
	  $tok				= $params['token'];
?>
       <form action="index.php" method="post">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="deleteUserGroup" />
       <input type="hidden" name="boxchecked" value="0" />
       <input type="hidden" name="hidemainmenu" value="0" />
       <input type="hidden" name="prtoken" value="<?php echo $tok; ?>" />

 <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_USER_GROUP' ) ;?>: <?php echo $user->userName(); ?>
       </div>
                 <br/>
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">
	  <tr>
	    <td width="25%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_NAME' ); ?></b></td>
   	    <td width="60%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_MEMBERS' );?></b></td>
	    <td width="15%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>"><b><?php echo JText::_( 'MYSMS_ACTION' );?></b></td>
	  </tr>

<?php
       $ids = '';
       if( count($groupRows) > 0 ){
           foreach($groupRows as $entry )
           {


         $ids .= $entry->_id .';';
?>
        <tr>
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo $entry->_name; ?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
<?php
            foreach($entry->_members as $key=>$val){
               echo $val->name . ",";
            }
?>
            </td>

              <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <input type="submit" name="delete_button_<?php echo $entry->_id; ?>" value="<?php echo JText::_( 'MYSMS_DELETE' ); ?>" />
            </td>

        </tr>
        <tr>

        <td colspan="3" style="border-bottom: 1px solid #cccccc;" >

        </td>
        </tr>

<?php
        } //foreach
        }//if count > 0
?>

          </table>
          <input type="hidden" name="ids" value="<?php echo $ids; ?>" />
          </form>

          <br/><br/>

       <form action="index.php" method="post">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="addUserGroup" />
       <input type="hidden" name="boxchecked" value="0" />
       <input type="hidden" name="hidemainmenu" value="0" />
       <input type="hidden" name="prtoken" value="<?php echo $tok; ?>" />

 <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_( 'MYSMS_CREATE_NEW_GROUP' );?>:
      
       </div>
                 <br/>
         <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">

        <tr>
           <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_GROUPNAME' ); ?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <input type="text" name="groupname" />
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">

            </td>

        </tr>

           <tr>
           <td valign="top"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_MEMBERS' );?>
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
<?php
            //get min and max id for faster searching in dispatcher
            $maxID = 0;
            $minID = 0;


            foreach($phoneBookRows as $pbEntry){
             if( $maxID < $pbEntry->id ){
               $maxID = $pbEntry->id;
             }

             if( $minID > $pbEntry->id ){
               $minID = $pbEntry->id;
             }

?>
            <input type="checkbox" name="userid_<?php echo $pbEntry->id;?>" value="<?php echo$pbEntry->id;?>" />
            	<?php echo $pbEntry->name . '&nbsp;&nbsp;(' . $pbEntry->number . ' )'; ?>
            	<br/>
<?php
            }

?>
            <input type="hidden" name="maxID" value="<?php echo $maxID;?>" />
            <input type="hidden" name="minID" value="<?php echo $minID;?>" />
            </td>
            <td  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">

            </td>

        </tr>


          <tr>
          <td>

          </td>
            <td colspan="2"  class="sectiontableentry<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">

               <br/> <input type="submit" name="save_button" value="<?php echo JText::_( 'MYSMS_SAVE' ); ?>" />&nbsp;&nbsp;<input type="submit" name="back_button" value="<?php echo JText::_( 'MYSMS_BACK' );?>" />
            </td>
          </tr>

          </table>

          </form>
<?php
} //end function phonebook

function editTemplate( $row, $params )
{
	$this->JS();
	
	 $mosParameters    = $params['mosParameters'];
	 $option			= $params['option'];
	 $Itemid			= $params['ItemId'];
?>	
	<form name="editTemplateForm" action="index.php" method="post" id="editTemplateForm">
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
    <input type="hidden" name="task" value="updateTemplate" />
    <input type="hidden" name="tid" value="<?php echo $row->tid;?>" />       
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    
     <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
      <?php echo JText::_( 'MYSMS_SMSAT_TEMPLATE' );?>      
     </div>
     
<br/>
     
	  <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">
        <tr>
        <td>Name</td>
        <td><input onmouseout="return OnTemplateEditCheck();" 
        		   onfocus="return OnTemplateEditCheck" 
        		   onclick="return OnTemplateEditCheck();" 
        		   onkeyup="return OnTemplateEditCheck();" 
        		   onkeydown="return OnTemplateEditCheck();"
            	   onchange="return OnTemplateEditCheck"
              	   type="text" name="t_name" value="<?php echo $row->name; ?>"/></td>
        </tr>
        <tr>
        <td>Text</td>
        <td><textarea  onmouseout="return OnTemplateEditCheck();" 
        		   onfocus="return OnTemplateEditCheck" 
        		   onclick="return OnTemplateEditCheck();" 
        		   onkeyup="return OnTemplateEditCheck();" 
        		   onkeydown="return OnTemplateEditCheck();"
            	   onchange="return OnTemplateEditCheck"
            	   name="t_tmpl" style="width: 250px; height: 50px;"><?php echo $row->tmpl; ?></textarea> <input onclick="return TemplateUpdate();" type="button" name="editTemplateButton" value="<?php echo JText::_( 'MYSMS_SMSAT_TEMPLATE_UPDATE' ); ?>"  /></td>
        </tr>        
       </table>      

</form>     
    
    
<?php   

	$this->JS();

	
}

function showTemplates( $rows, $params )
{
	$this->JS();
	
	  $mosParameters    = $params['mosParameters'];
	  $option			= $params['option'];
	  $Itemid			= $params['ItemId'];
	  $tok				= $params['token'];
?>
       <form action="index.php" method="post" id="postform" name="postform">
       <input type="hidden" name="option" value="<?php echo $option; ?>" />
       <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
       <input type="hidden" name="task" value="unknown" />
       <input type="hidden" name="tid" value="unknown" />
       <input type="hidden" name="t_name" value="" />
       <input type="hidden" name="t_tmpl" value="" />
       <input type="hidden" name="boxchecked" value="0" />
       <input type="hidden" name="hidemainmenu" value="0" />       
       </form>

<!--  Header  -->
 	   <div class="componentheading<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           <?php echo JText::_( 'MYSMS_SMSAT_TEMPLATE' ) ;?>
       </div>
       
<!-- List all existing Templates -->

<br/>
<form name="createTemplateForm" action="post" id="createTemplateForm" name="createTemplateForm">
	  <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">
        <tr>
        <td>Name</td>
        <td><input onmouseout="return OnTemplateCreateCheck();" 
        		   onfocus="return OnTemplateCreateCheck();" 
        		   onclick="return OnTemplateCreateCheck();" 
        		   onkeyup="return OnTemplateCreateCheck();" 
        		   onkeydown="return OnTemplateCreateCheck();"
            	   onchange="return OnTemplateCreateCheck();"
              	   type="text" name="t_name" value=""/></td>
        </tr>
        <tr>
        <td>Text</td>
        <td><textarea  onmouseout="return OnTemplateCreateCheck();" 
        		   onfocus="return OnTemplateCreateCheck();" 
        		   onclick="return OnTemplateCreateCheck();" 
        		   onkeyup="return OnTemplateCreateCheck();" 
        		   onkeydown="return OnTemplateCreateCheck();"
            	   onchange="return OnTemplateCreateCheck();"
            	   name="t_tmpl" style="width: 250px; height: 50px;"></textarea> <input onclick="return TemplateCreate();" type="button" name="createTemplateButton" value="<?php echo JText::_( 'MYSMS_SMSAT_TEMPLATE_CREATE' );  ?>" disabled="disabled" /></td>
        </tr>        
       </table> 
</form>

<div id="existingTemplates" style="margin-top: 30px">
    <?php 

if( count( $rows ) > 0 )
{

?>
      <table border="0" class="adminform" width="100%" cellpadding="0" cellspacing="0">
        <tr>
        
           <td  width="25%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           	<strong>
           		<?php echo JText::_( 'MYSMS_SMSAT_TEMPLATE_NAME' ); ?>
           </strong>
           </td>
           
           <td width="50%"  class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
        		<strong>
           			<?php echo JText::_( 'MYSMS_SMSAT_TEMPLATE_VALUE' ); ?>
           		</strong>
           </td>
           
           <td width="25%" class="sectiontableheader<?php echo $mosParameters->get( 'pageclass_sfx' ); ?>">
           		<strong> 
           			<?php echo JText::_( 'MYSMS_ACTION' ); ?>
            	</strong>
           </td>
            
        </tr>
        
<?php 
    
    $css = 'sectiontableentry';
   
	foreach( $rows as $tmpl )
	{
		
		
		echo '<tr>';
			echo '<td style="padding: 0px;" align="left" width="25%" class="'.$css.$mosParameters->get( 'pageclass_sfx' ) .'" ><div style="font-weight: normal;  margin: 7px;">' . wordwrap( $tmpl->name, 50, '<br/>', true )  . '</div></td>';
			echo '<td style="padding: 0px;" align="left" width="50%" class="'.$css.$mosParameters->get( 'pageclass_sfx' ) .'" ><div style="font-weight: normal;  margin: 7px;">' . wordwrap( $tmpl->tmpl, 50, '<br/>', true )  . '</div></td>';
				
			$del_url = '<a href="#" onClick="return TemplateDelete( '. $tmpl->tid .' );" />' . JText::_( 'MYSMS_DELETE' ) . ';</a>';
			$edt_url = '<a href="#" onClick="return TemplateEdit( '. $tmpl->tid .' );" />' . JText::_( 'MYSMS_EDIT' ) .'</a>';
			
			echo '<td style="padding: 0px;" align="left" width="25%" class="'.$css.$mosParameters->get( 'pageclass_sfx' ) .'" ><div style="font-weight: normal;   margin: 7px;">' . $del_url . '&nbsp;-&nbsp;' . $edt_url. '</div></td>';
			
		echo '</tr>';	

		if( $css == 'sectiontableentry' )
		{
			$css = 'sectiontableheader';
		}else{
			$css = 'sectiontableentry';
		}
		
	}
?>                        
    </table>
    

    
    
<?php 
} //end count rows > 0
?>
    
    
    <hr noshade width="95%" size="1" align="left" />
        
    </div>
    
    <form id="importForm" name="importForm" action="index.php" method="post"  enctype="multipart/form-data">
      <input type="hidden" name="option" value="<?php echo $option; ?>" />
      <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
      <input type="hidden" name="task" value="unknown" />
    
    <div style="float: right; width: 185px; ">
    <input value="<?php echo JText::_('MYSMS_SMSAT_EXPORT_TEMPLATES');  ?>" type="button" name="exportTemplateButton" id="exportTemplateButton" onclick="return ExportTemplates();"/>
    </div>
    
    <div style="float: left; width: 485px; ">
    <input type="file" name="templateFile" id="templateFile" onchange="return CheckTemplateImport();" />
    <input value="<?php echo JText::_('MYSMS_SMSAT_IMPORT_TEMPLATES');  ?>" type="button" name="importTemplateButton" id="importTemplateButton" disabled="disabled" onclick="return ImportTemplates();"/> 
    </div>
    
    </form>    
    
       
       
       
       
     
       
          
<?php
$this->JS();
} //end function phonebook

}//end class
?>