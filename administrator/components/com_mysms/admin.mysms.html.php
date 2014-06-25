<?php
/**
* MySMS - Simple SMS Component for Joomla
*
* Axel Sauerhoefer < mysms[at]quelloffen.com >
*
* http://www.willcodejoomlaforfood.de
*
* $Author: axel $
* $Rev: 254 $
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/admin.mysms.html.php $
*
* $Id: admin.mysms.html.php 254 2010-07-06 20:29:06Z axel $
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

if( defined( 'MYSMS_BACKEND_ADMIN_HTML_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_ADMIN_HTML_PHP', 1 );


/**
*  HTML_MySMS is the html backend class from com_mysms
*
* @package MySMS
* @subpackage Backend
**/
class mySmsBackendHtml
{


/**
* This function shows the user overview panel
*
*  @param array $rows
*  @param array $mySmsRows
*  @param array $pageNav
**/

function showUser($rows, $mySmsRows, $pageNav){
    global $option;
    ?>
    <form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="edit" rowspan="2" nowrap><?php echo JText::_( 'MYSMS_USER_ADMIN' );?></th>
	  </tr>
	  </table>

	  <table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="5">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title"><?php echo JText::_( 'MYSMS_USERID' );?></th>
			<th class="title"><?php echo JText::_( 'MYSMS_NAME' );?></th>
			<th class="title"><?php echo JText::_( 'MYSMS_USERNAME' );?></th>
			<th class="title"><?php echo JText::_( 'MYSMS_ALLOWED_SEND_SMS' );?></th>
			<th class="title"><?php echo JText::_( 'MYSMS_CREDITS' );?></th>
	  </tr>
		<?php
		$k = 0;
		 $allow = false;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			?>
			<tr>
			  <td><?php echo $pageNav->getRowOffset( $i ); ?></td>
			  <td><?php echo JHTML::_('grid.id', $i, $row->id) ?></td>
			  <td><?php echo $row->id;?></td>
			  <td><?php echo $row->name ?></td>
			  <td><?php echo $row->username; ?></td>


			  <?php
                            $credits = 0;
                            $allow = false;
			    foreach($mySmsRows as $s ){
                              if( $s->userid == $row->id && $s->state==1){
                                $credits = $s->credits;
                                $allow = true;
                                break;
                              }
                            }
			  ?>



			  <td >
  			     <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php if($allow==true){echo 'unpublish';}else{echo'publish';}?>')">
				<img src="images/<?php echo ($allow ? 'tick.png' : 'publish_x.png');?>" width="12" height="12" border="0" alt="allow <?php echo $row->name; ?>" />
			      </a>
			      <td><?php echo $credits; ?></td>
			</td>
			</tr>
			<?php
		}
		?>
	  </table>

	  <?php echo $pageNav->getListFooter(); ?>

	  <input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="user" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
<?php
}

 function showCredits()
 { 	
    global $option;
?>
    <table class="adminheading">
           <tr>
	       <th class="cpanel" rowspan="2" nowrap>MySMS</th>
	  </tr>
   </table>

    <table cellpadding="4" cellspacing="0" border="0"  class="adminform">
	<tr valign="top">
		<td>
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index2.php?option=com_mysms&amp;act=provider" >
								<img alt="<?php echo JText::_( 'MYSMS_PROVIDER_ADMIN' );?>" src="images/browser.png" alt="Provider" align="middle" name="image" border="0"/><br/>
							<?php echo JText::_( 'MYSMS_PROVIDER_ADMIN' );?>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index2.php?option=com_mysms&amp;act=user">
								<img alt="<?php echo JText::_( 'MYSMS_USER_ADMIN' ) ;?>" src="images/addusers.png" alt="Manage User" align="middle" name="image" border="0" /><br/>
							<?php echo JText::_( 'MYSMS_USER_ADMIN' );?> </a>
						</div>
					</div>
				</div>
			</div>
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index2.php?option=com_mysms&amp;act=ad">
								<img alt="<?php echo JText::_('MYSMS_ADVERTISMENT' ); ?>" src="images/addedit.png" align="middle" name="image" border="0" /><br/>
							<?php echo JText::_('MYSMS_ADVERTISMENT' ); ?> </a>
						</div>
					</div>
				</div>
			</div>
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index2.php?option=com_mysms&amp;act=global">
							<div class="iconimage">
								<img  alt="<?php echo JText::_( 'MYSMS_GLOBAL_SETTINGS' ); ?>" src="images/impressions.png" align="middle" name="image" border="0" />
							</div>
							<?php echo JText::_( 'MYSMS_GLOBAL_SETTINGS' ); ?> </a>
					</div>
				</div>
			</div>
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index2.php?option=com_mysms&amp;act=about">
							<div class="iconimage">
								<img alt="<?php echo JText::_( 'MYSMS_SHOW_ABOUT' ); ?>" src="images/install.png" align="middle" name="image" border="0" />
							</div>
							<?php echo JText::_( 'MYSMS_SHOW_ABOUT' ); ?> </a>
					</div>
				</div>
			</div>
			<div id="cpanel">
				<div style="float:left;">
					<div class="icon">
						<a href="index2.php?option=com_mysms&amp;act=prereq">
							<div class="iconimage">
								<img alt="<?php echo JText::_( 'MYSMS_PREREQ_CHECK' ); ?>" src="images/systeminfo.png" align="middle" name="image" border="0" />
							</div>
							<?php echo JText::_( 'MYSMS_PREREQ_CHECK' ); ?> </a>
					</div>
				</div>
			</div>
			</div>
		</td>
	</tr>
</table>
<?php
}

/**
* This function shows the edit user panel
*
*  @param array $rows
*  @param array $mySmsRows
**/
function editUser(&$row, &$mySMSRow)
  {
    global $option;

  ?>
              <form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="edit" rowspan="2" nowrap><?php echo $row->name .' '. JText::_( 'MYSMS_EDIT' ); ?></th>
	  </tr>
	  </table>

	  <table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td valign="top">
    	  <table class="adminlist">
    		<tr>
    			<th class="title" colspan="2">Details</th>
    	  </tr>
    	  <tr>
    	    <td width="50"><?php echo JText::_( 'MYSMS_PHONENUMBER' );?></td>
    	    <td><input type="text" name="number" value="<?php echo $mySMSRow->number; ?>" size="50" /></td>
    	  </tr>
    	  <tr>
    	    <td><?php echo JText::_( 'MYSMS_COMMENT' ); ?></td>
    	    <td><input type="text" name="comment" value="<?php echo $mySMSRow->comment ?>" size="50" /></td>
    	  </tr>
    	  <tr>
    	    <td><?php echo JText::_( 'MYSMS_CREDITS' );?></td>
    	    <td><input type="text" name="credits" value="<?php echo $mySMSRow->credits ?>" size="50" /></td>
    	  </tr>
    	  </table>
    	</td>
    </tr>
    </table>
	  <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	  <input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="user" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
<?php
}




  /**
* This function shows the edit user panel
*
*  @param array $rows
*  @param array $mySmsRows
**/
function showAdvertisment(&$ad)
  {
    global $option;
  ?>
   <form action="index2.php" method="post" name="adminForm">

	 <table class="adminheading">
	<tr>
			<th class="edit" rowspan="2" nowrap><?php echo JText::_('MYSMS_ADVERTISMENT' ); ?></th>
	  </tr>
	  </table>

	  <table cellspacing="0" cellpadding="0" width="100%" >
		<tr valign="top">
			<td valign="top">
    	  		<table class="adminlist">
    	  			<tr>
              			<textarea rows="3" cols="40" name="ad" id="ad"><?php echo $ad; ?></textarea>
    	  			</tr>
    			</table>
    		</td>
    		</tr>
    		</table>

	    <input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="ad" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
<?php
}


/**
* This function shows a provider selection panel before editing a provider
*
*  @param array $rows
**/
function showProviderSelectPanel(&$rows)
{
      global $option;
?>
        <form action="index2.php" method="post" name="adminForm">
          <table class="adminheading">
		<tr>
	 	    <th class="edit" rowspan="2" nowrap="nowrap">Manage SMS Provider</th>
	       </tr>
	  </table>

	  <table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="5">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title"><?php echo JText::_( 'MYSMS_PROVIDER' );?></th>
			<th class="title"><?php echo JText::_('MYSMS_ACTIV' );?></th>
	    </tr>
		<?php
		$k = 0;
		 $allow = false;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];
			?>
			<tr>
			  <td></td>
			  <td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
			  <td><?php echo $row->name;?></td>
			  <td><?php echo $row->active ?></td>
			</tr>
			<?php
		}
		?>
	  </table>

	  <input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="provider" />
		<input type="hidden" name="task" value="configure" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
        <?php
  }

/**
* This function shows a provider edit panel
*
*  @param array $row
*  @param object $provider
**/

function editProvider(&$row, &$provider)
  {

    global $option;
  ?>
     <form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="edit" rowspan="2" nowrap="nowrap"><?php echo $row->name .' '.JText::_( 'MYSMS_EDIT' );?></th>
	  </tr>
	  </table>

	  <table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td valign="top">
    	  		<table class="adminlist">
    				<tr>
    					<th class="title" colspan="2">
    						<?php echo JText::_( 'MYSMS_DETAILS' ) ;?>
    					</th>
          			</tr>
<?php

          //create dynamic html from params array

          $tmp = '';
          foreach($provider->_params as $key=>$val )
          {
             $tmp .= "$key,";
?>
          <tr>
          <td align="left">
          <b>
          <?php echo $key; ?>:
          </b>
          </td>
          <td>
          <input type="text" name="<?php echo $key;?>" value="<?php echo $val;?>">
          </td>

          </tr>

<?php

           }


?>


    	  </table>
    	</td>
    </tr>
    </table>
	  <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	  <input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="provider" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="providerparams" value="<?php echo $tmp;?>" />

		</form>
	  <?php
  }



function showLoadPanel(&$cid)
 {
    global $option;
?>
        <form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="edit" rowspan="2" nowrap="nowrap"><?php echo JText::_( 'MYSMS_LOADLIST' ); ?></th>
	  </tr>
	  </table>

	  <table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td valign="top">
    	  <table class="adminlist">
    		<tr>
    			<th class="title" colspan="2"></th>
          </tr>
          
          <tr>
          	<td>
          		<b>
          			<?php echo JText::_( 'MYSMS_NEW_CREDITS' );?>:
          		</b>
          		<input type="text" name="credits" value="0" size="4">
          		<input type="submit" name="button" value="<?php echo JText::_( 'MYSMS_SAVE' );?>">
          	</td>
          </tr>
			<tr>
			<td><b><?php echo JText::_( 'MYSMS_SELECTED_USER' );?></b><br/>
			<ul>
<?php

			foreach( $cid as $user ){
				$u = new mySMSUser( $user );
				echo "<li>" . $u->userName() . "</li>";
				unset($u);
			}
?>
		  </ul>
			</td>
			</tr>

    	  </table>
    	</td>
    </tr>
    </table>

<?php
		foreach( $cid as $id )
		{
			echo '<input type="hidden" name="cid[]" value="'. $id.'" />';
		}
?>

	  <input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="act" value="user" />
		<input type="hidden" name="task" value="loadList" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
	  <?php
  }

function showGlobal( $config )
{
	global $option;
?>
        <form action="index2.php" method="post" name="adminForm">
			<table class="adminheading">
				<tr>
					<th class="edit" rowspan="2" nowrap="nowrap"><?php echo JText::_( 'MYSMS_GLOBAL_CONFIG' ); ?></th>
	  			</tr>
	  		</table>
	  		<table cellspacing="0" cellpadding="0" width="100%">
				<tr valign="top">
					<td valign="top">
    	  				<table class="adminlist">
    						<tr>
    							<th class="title" colspan="2"></th>
          					</tr>
<?php
		foreach( $config as $key => $val )
		{
			
			$type  = $val['type'];
			$value = $val['value'];
			
				echo '<tr>
						<td width="200" >';
							echo ucfirst( strtolower($key) );
					echo '</td>
							<td>';
					
							 if( $type == 'text' )
							 {				
							 	echo "<input type=\"text\" name=\"config[$key]\" value=\"$value\" />";
							 }
							 
							 
							 if( $type == 'textarea' )
							 {
							 	echo "<textarea cols=\"60\" rows=\"5\" name=\"config[$key]\">$value</textarea>";
							 }
							 
				echo '</td>
						</tr>';
		}
?>
    	  				</table>
    				</td>
    			</tr>
    		</table>

	  		<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="act" value="global" />
			<input type="hidden" name="task" value="save" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="hidemainmenu" value="0" />
		</form>
	  <?php
}

	/**
	 * Show some links and statistics about com_mysms
	 */
	function showAbout()
	{
	?>
			<table class="adminheading">
				<tr>
					<th class="edit" rowspan="2" nowrap="nowrap"><?php echo JText::_( 'MYSMS_SHOW_ABOUT' ); ?></th>
	  			</tr>
	  		</table>

	  		<!-- show install stuff -->
			<table border="0" width="75%" align="center">
				<tr>
					<td align="left" valign="top">
						<a href="http://mysms.joomlacoder.de" alt="mysms.joomlacoder">
							<img src="http://www.willcodejoomlaforfood.de/mysms-logo.png" alt="mysms">
						</a>
					</td>
															<td align="left" valign="top">
					<script type="text/javascript" src="http://www.ohloh.net/p/9567/widgets/project_factoids"></script>
</td>
				</tr>
				<tr>
					<td align="left" valign="top">
						<h3>Thank you for using com_mysms version 1.5.11</h3><br/>
						Here maybe some useful links for you:
						<ul>
							<li>
								Official contact email address: <a href="mailto:mysms@quelloffen.com"/> mysms[at]quelloffen.com</a>
							</li>
							<li>
								MySMS Wiki and Bugtracking System:  <a href="http://mysms.joomlacoder.de" alt="mysms.joomlacoder">http://mysms.joomlacoder.de</a>
							</li>
							<li>
								Official MySMS Homepage: <a href="http://www.willcodejoomlaforfood.de" alt="willcodejoomlaforfood">http://www.willcodejoomlaforfood.de</a>
							</li>
							<li>
								Ohloh Profile: <a href="http://www.ohloh.net/p/MySMS" alt="ohloh profile">http://www.ohloh.net/p/MySMS</a>
							</li>
							<li>
								Download Area: <a href="http://willcodejoomlaforfood.de/downloads/" alt="Downloads">WillCodeJoomlaForFood Downloads</a>
							</li>
						</ul>
						
						<p> MySMS is using some GPL licensed images from <a href="http://www.everaldo.com/crystal/" target="_blank"> Everaldo - Crystal </p> 
						
					</td>
						<td align="left" valign="top">
					<script type="text/javascript" src="http://www.ohloh.net/p/9567/widgets/project_languages"></script>
					</td>
				</tr>
			</table>
	<?php
	}

	/**
	 * Show some links and statistics about com_mysms
	 */
	function showPrerequisite( &$data )
	{
	?>
			<table class="adminheading">
				<tr>
					<th class="edit" rowspan="2" nowrap="nowrap"><?php echo JText::_( 'MYSMS_PREREQ_CHECK' ); ?></th>
	  			</tr>
	  		</table>
	<?php
			while( ($entry = array_pop($data) ) !== null  ){
				list( $key, $val, $desc ) = $entry;

				$style = 'background-color: #AAFFAA; padding: 5px;';

				if( $val == false ){
					$style= 'background-color: #FFA98C; padding: 5px;';
				}

				echo "<table class=\"adminlist\" style=\"$style\">
						<tr>
							<td width=\"25%\">$key</td>
							<td width=\"25%\">$val</td>
							<td width=\"50%\">$desc</td>
						</tr>
					</table>";
			}
	}


}     //end class
?>