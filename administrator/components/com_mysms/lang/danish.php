<?php
/**
* $Id: danish.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_LANG_DANISH_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_LANG_DANISH_PHP', 1 );

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


DEFINE('_MYSMS_CHANGES_SAVED', 'Ændringerne er gemt');
DEFINE('_MYSMS_INVALID_PHONENUMBER', 'Telefonnummeret er ugyldigt');
DEFINE('_MYSMS_SQLQUERY_ERROR', 'Database forspørgsel mislykkes');
DEFINE('_MYSMS_INVALID_POSTRELOAD_TOKEN', 'Ugyldig indlæsning af blok');
DEFINE('_MYSMS_POST_RELOAD_BLOCK', 'Indlæsning af blok' );
DEFINE('_MYSMS_INVALID_SENDER', 'Afsenderens telefonnummer er ugyldigt');
DEFINE('_MYSMS_INVALID_RECIPIENT', 'Modtagerens telefonnummer er ugyldigt');
DEFINE('_MYSMS_NO_ACTIVE_PROVIDER', 'Venligst kontroller din SMS gateway opsætning');
DEFINE('_MYSMS_NOT_SUFFICIENT_FUNDS' , 'Du er ikke flere SMS´er tilrådig');
DEFINE('_MYSMS_SEND_SMS_FAILED', 'SMS´en kunne ikke afsendes til %s fordi: %s');
DEFINE('_MYSMS_SEND_SMS_SUCCESSFULLY', 'SMS´en er sendt til %s');
DEFINE('_MYSMS_CHANGE_BALANCE_FAILED', 'Kunne ikke ændre kreditten');
DEFINE('_MYSMS_PHONEBOOKENTRY_ENTRY_SUCCESSFULLY_ADDED', 'Telefonbogen er gemt');
DEFINE('_MYSMS_ADD_GROUP_FAILED', 'Kunne ikke tilføjes til gruppen');
DEFINE('_MYSMS_ADD_GROUP_NO_SELECTION' ,'Kunne ikke tilføjes til gruppen, ingen kontakter valgt');
DEFINE('_MYSMS_GROUPNAME_MISSING', 'Ugyldigt gruppenavn');
DEFINE('_MYSMS_GROUP_ADD_MEMBER_FAILED', 'Kunne ikke tilføje kontaktpersonen til gruppen');
DEFINE('_MYSMS_INTERNAL_ERROR', 'Intern fejl');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_FAILED', 'Kunne ikke slette telefonbogen');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_SUCCESSFULLY', 'Telefonbogen er slettet');
DEFINE('_MYSMS_ERROR', 'Fejl');
DEFINE('_MYSMS_MY_CONFIG', 'Min konfiguration');
DEFINE('_MYSMS_GLOBAL_CONFIG', 'Global konfiguration');
DEFINE('_MYSMS_EDIT', 'Rediger');
DEFINE('_MYSMS_SMSARCHIVE', 'SMS Arkiv');
DEFINE('_MYSMS_SHOW', 'Vis');
DEFINE('_MYSMS_MY_PHONEBOOK', 'Min telefonbog');
DEFINE('_MYSMS_USER_GROUP', 'Brugeregruppe');
DEFINE('_MYSMS_DISPATCH', 'Ekspeder');
DEFINE('_MYSMS_BALANCE', 'Balance');
DEFINE('_MYSMS_MESSAGE', 'Besked');
DEFINE('_MYSMS_SENDER', 'Afsender');
DEFINE('_MYSMS_RECIPIENT', 'Modtager');
DEFINE('_MYSMS_SEND', 'Send');
DEFINE('_MYSMS_PHONENUMBER', 'Telefonnummer');
DEFINE('_MYSMS_COMMENT', 'Kommentar');
DEFINE('_MYSMS_SAVE', 'Gem');
DEFINE('_MYSMS_CANCEL', 'Annuller');
DEFINE('_MYSMS_DELETE', 'Slet');
DEFINE('_MYSMS_DATE', 'Dato');
DEFINE('_MYSMS_BACK', 'Tilbage');
DEFINE('_MYSMS_NAME', 'Navn');
DEFINE('_MYSMS_NEW_ENTRY', 'Ny postering');
DEFINE('_MYSMS_MEMBERS', 'Medlem');
DEFINE('_MYSMS_ACTION', 'Handling');
DEFINE('_MYSMS_CREATE_NEW_GROUP', 'Tilføj ny gruppe');
DEFINE('_MYSMS_GROUPNAME', 'Gruppenavn');
DEFINE('_MYSMS_PHONEBOOK_REMOVE_ENTRY_FAILED', 'Kunne ikke slette telefonbogen');
DEFINE('_MYSMS_EDIT_USER_INVALID_DATA', 'Ugyldig bruger');
DEFINE('_MYSMS_INVALID_BALANCE', 'Ugyldig balance');
DEFINE('_MYSMS_INVALUD_USER_DATA', 'Ugyldig bruger');
DEFINE('_MYSMS_PROVIDER_NOT_FOUND','Kunne ikke indlæse udbyderen');
DEFINE('_MYSMS_PROVIDERFACTORY_NOT_FOUND','Udbyderlisten kunne ikke findes');
DEFINE('_MYSMS_USER_ADMIN','Brugere administration');
DEFINE('_MYSMS_PROVIDER_ADMIN','SMS Gateway Admin');
DEFINE('_MYSMS_USERID','ID');
DEFINE('_MYSMS_USERNAME','Brugerenavn');
DEFINE('_MYSMS_SELECTED_USER', 'Valgte brugere');
DEFINE('_MYSMS_ALLOWED_SEND_SMS','Tillad at sende SMS');
DEFINE('_MYSMS_CREDITS','Kredit');
DEFINE('_MYSMS_NEW_CREDITS','Ny Kredit');
DEFINE('_MYSMS_PROVIDER','SMS udbyder');
DEFINE('_MYSMS_ACTIV','Aktiv');
DEFINE('_MYSMS_DETAILS', 'Detaljer');
DEFINE('_MYSMS_ADVERTISMENT','Annonce');
DEFINE('_MYSMS_LOADLIST' , 'Ændre kredit');
DEFINE('_MYSMS_GLOBAL_SETTINGS', 'Globale indstillinger');
DEFINE('_MYSMS_GLOBAL_LIMIT_REACHED', 'Max. antal SMS sendt, den globale grænse er nåede');
DEFINE('_MYSMS_CONTACTS', 'Kontakter');
DEFINE('_MYSMS_GROUPS', 'Grupper');
DEFINE('_MYSMS_IMPORT_PHONEBOOK', 'Importere telefonbog');
DEFINE('_MYSMS_EXPORT_PHONEBOOK', 'Exportere telefonbog');
DEFINE('_MYSMS_EXPORT', 'Exportere');
DEFINE('_MYSMS_MISSING', 'Tabt');
DEFINE('_MYSMS_SHOW_ABOUT', 'Om MySms');
DEFINE('_MYSMS_PREREQ_CHECK', 'Prerequisites Check');
DEFINE('_MYSMS_ADD', 'Tilføj');
DEFINE('_MYSMS_IMPORT', 'Importer');
DEFINE('_MYSMS_PLUGIN_ABORT', 'Plugin error');
?>