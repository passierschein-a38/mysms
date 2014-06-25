<?php
/**
* $Id: french.php 184 2009-12-11 17:06:40Z axel $
*
* @author Axel Sauerhöfer 
* @copyright Copyright &copy; 2008, Axel Sauerhöfer
* @version 1.5.6
* @email mysms[at]quelloffen.com
* @package MySMS
*
* All rights reserved.  MySMS Component for Joomla!
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

if( defined( 'MYSMS_LANG_FRENCH_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_LANG_FRENCH_PHP', 1 );


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

DEFINE('_MYSMS_CHANGES_SAVED', 'Changements sauvés');
DEFINE('_MYSMS_INVALID_PHONENUMBER', 'No telephone invalide');
DEFINE('_MYSMS_SQLQUERY_ERROR', 'Requete base annulé');
DEFINE('_MYSMS_INVALID_POSTRELOAD_TOKEN', 'Post Reload block');
DEFINE('_MYSMS_POST_RELOAD_BLOCK', 'Post Reload block' );
DEFINE('_MYSMS_INVALID_SENDER', 'Numero envoyeur invalide');
DEFINE('_MYSMS_INVALID_RECIPIENT', 'Numero destinataire invalide');
DEFINE('_MYSMS_NO_ACTIVE_PROVIDER', 'Verifier votre configuration sms gateway');
DEFINE('_MYSMS_NOT_SUFFICIENT_FUNDS' , 'Credits trop bas');
DEFINE('_MYSMS_SEND_SMS_FAILED', 'SMS non envoye à %s cause: %s');
DEFINE('_MYSMS_SEND_SMS_SUCCESSFULLY', 'SMS envoyé %s');
DEFINE('_MYSMS_CHANGE_BALANCE_FAILED', 'Impossible de changer credit');
DEFINE('_MYSMS_PHONEBOOKENTRY_ENTRY_SUCCESSFULLY_ADDED', 'Entree repertoire sauvée');
DEFINE('_MYSMS_ADD_GROUP_FAILED', 'Impossible ajouter un groupe');
DEFINE('_MYSMS_ADD_GROUP_NO_SELECTION' ,'Impossible ajouter le group, aucuncontact selectionné');
DEFINE('_MYSMS_GROUPNAME_MISSING', 'Nom de groupe invalide');
DEFINE('_MYSMS_GROUP_ADD_MEMBER_FAILED', 'Impossible ajouter le contact augroupe');
DEFINE('_MYSMS_INTERNAL_ERROR', 'Erreur interne');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_FAILED', 'Impossible de detruire cecontact de repertoire');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_SUCCESSFULLY', 'Entree dans le repertoiresauvée');
DEFINE('_MYSMS_ERROR', 'Erreur');
DEFINE('_MYSMS_MY_CONFIG', 'Ma Configuration');
DEFINE('_MYSMS_GLOBAL_CONFIG', 'Configuration Globale');
DEFINE('_MYSMS_EDIT', 'edit');
DEFINE('_MYSMS_SMSARCHIVE', 'SMS Archive');
DEFINE('_MYSMS_SHOW', 'montrer');
DEFINE('_MYSMS_MY_PHONEBOOK', 'Mon Repertoire');
DEFINE('_MYSMS_USER_GROUP', 'Usergroups');
DEFINE('_MYSMS_DISPATCH', 'Dispatch');
DEFINE('_MYSMS_BALANCE', 'Balance');
DEFINE('_MYSMS_MESSAGE', 'Message');
DEFINE('_MYSMS_SENDER', 'Emetteur');
DEFINE('_MYSMS_RECIPIENT', 'Destinataire');
DEFINE('_MYSMS_SEND', 'Envoye');
DEFINE('_MYSMS_PHONENUMBER', 'No telephone');
DEFINE('_MYSMS_COMMENT', 'commentaire');
DEFINE('_MYSMS_SAVE', 'sauve');
DEFINE('_MYSMS_CANCEL', 'annule');
DEFINE('_MYSMS_DELETE', 'detruit');
DEFINE('_MYSMS_DATE', 'date');
DEFINE('_MYSMS_BACK', 'retour');
DEFINE('_MYSMS_NAME', 'Nom');
DEFINE('_MYSMS_NEW_ENTRY', 'nlle Entree');
DEFINE('_MYSMS_MEMBERS', 'Membres');
DEFINE('_MYSMS_ACTION', 'Action');
DEFINE('_MYSMS_CREATE_NEW_GROUP', 'Ajouter nouveau groupe');
DEFINE('_MYSMS_GROUPNAME', 'Nom Groupe');
DEFINE('_MYSMS_PHONEBOOK_REMOVE_ENTRY_FAILED', 'Impossible de detruire cecontact de repertoire');
DEFINE('_MYSMS_EDIT_USER_INVALID_DATA', 'Utilisateur invalide');
DEFINE('_MYSMS_INVALID_BALANCE', 'Invalide credits');
DEFINE('_MYSMS_INVALUD_USER_DATA', 'Utilisateur Invalide');
DEFINE('_MYSMS_PROVIDER_NOT_FOUND','Impossible de charger le provideur');
DEFINE('_MYSMS_PROVIDERFACTORY_NOT_FOUND','ProviderFactory not found');
DEFINE('_MYSMS_USER_ADMIN','User Admin');
DEFINE('_MYSMS_PROVIDER_ADMIN','SMS Gateway Admin');
DEFINE('_MYSMS_USERID','ID');
DEFINE('_MYSMS_USERNAME','Username');
DEFINE('_MYSMS_SELECTED_USER', 'Selected User');
DEFINE('_MYSMS_ALLOWED_SEND_SMS','Able to send SMS');
DEFINE('_MYSMS_CREDITS','Credits');
DEFINE('_MYSMS_NEW_CREDITS','New Credits');
DEFINE('_MYSMS_PROVIDER','SMS Provider');
DEFINE('_MYSMS_ACTIV','activated');
DEFINE('_MYSMS_DETAILS', 'details');
DEFINE('_MYSMS_ADVERTISMENT','Publicite');
DEFINE('_MYSMS_LOADLIST' , 'Charge Credits');
DEFINE('_MYSMS_GLOBAL_SETTINGS', 'Globale Settings');
DEFINE('_MYSMS_GLOBAL_LIMIT_REACHED', 'Max SMS where sended, global Limit reached');
DEFINE('_MYSMS_CONTACTS', 'Contacts');
DEFINE('_MYSMS_GROUPS', 'Groupes');
DEFINE('_MYSMS_IMPORT_PHONEBOOK', 'Import repertoire');
DEFINE('_MYSMS_EXPORT_PHONEBOOK', 'Export repertoire');
DEFINE('_MYSMS_EXPORT', 'export');
DEFINE('_MYSMS_MISSING', 'missing');
DEFINE('_MYSMS_SHOW_ABOUT', 'about MySms');
DEFINE('_MYSMS_PREREQ_CHECK', 'Prerequisites Check');
DEFINE('_MYSMS_PLUGIN_ABORT', 'Plugin error');
?>