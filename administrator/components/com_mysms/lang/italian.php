<?php
/**
* $Id: italian.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_LANG_ITALIAN_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_LANG_ITALIAN_PHP', 1 );


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

DEFINE('_MYSMS_CHANGES_SAVED', 'Cambiamenti salvati');
DEFINE('_MYSMS_INVALID_PHONENUMBER', 'Numero di telefono non valido');
DEFINE('_MYSMS_SQLQUERY_ERROR', 'Query Database fallita');
DEFINE('_MYSMS_INVALID_POSTRELOAD_TOKEN', 'Post Reload block');
DEFINE('_MYSMS_POST_RELOAD_BLOCK', 'Post Reload block' );
DEFINE('_MYSMS_INVALID_SENDER', 'Numero telefono a cui inviare non valido');
DEFINE('_MYSMS_INVALID_RECIPIENT', 'Numero mittente non valido');
DEFINE('_MYSMS_NO_ACTIVE_PROVIDER', 'Settaggi Sms gateway non validi');
DEFINE('_MYSMS_NOT_SUFFICIENT_FUNDS' , 'Credito insufficente');
DEFINE('_MYSMS_SEND_SMS_FAILED', 'SMS non inviato a %s causa : %s');
DEFINE('_MYSMS_SEND_SMS_SUCCESSFULLY', 'SMS inviato a %s');
DEFINE('_MYSMS_CHANGE_BALANCE_FAILED', 'Aggiornamento del credito fallito');
DEFINE('_MYSMS_PHONEBOOKENTRY_ENTRY_SUCCESSFULLY_ADDED', 'Numero in Rubrica aggiunto');
DEFINE('_MYSMS_ADD_GROUP_FAILED', 'Aggiunta gruppo fallito');
DEFINE('_MYSMS_ADD_GROUP_NO_SELECTION' ,'Aggiunta gruppo fallito,nessun contatto selezionato');
DEFINE('_MYSMS_GROUPNAME_MISSING', 'Nome gruppo non valido');
DEFINE('_MYSMS_GROUP_ADD_MEMBER_FAILED', 'Aggiunta al gruppo fallito');
DEFINE('_MYSMS_INTERNAL_ERROR', 'Errore interno');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_FAILED', 'Cancellazione Rubrica fallita');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_SUCCESSFULLY', 'Cancellato dalla Rubrica');
DEFINE('_MYSMS_ERROR', 'Errore');
DEFINE('_MYSMS_MY_CONFIG', 'Mia Configurazione');
DEFINE('_MYSMS_GLOBAL_CONFIG', 'Configurazione Globale');
DEFINE('_MYSMS_EDIT', 'modifica');
DEFINE('_MYSMS_SMSARCHIVE', 'Archivio SMS');
DEFINE('_MYSMS_SHOW', 'mostra');
DEFINE('_MYSMS_MY_PHONEBOOK', 'Mia Rubrica');
DEFINE('_MYSMS_USER_GROUP', 'Gruppi');
DEFINE('_MYSMS_DISPATCH', 'Dispatch');
DEFINE('_MYSMS_BALANCE', 'Balance');
DEFINE('_MYSMS_MESSAGE', 'Messaggio');
DEFINE('_MYSMS_SENDER', 'Sender');
DEFINE('_MYSMS_RECIPIENT', 'Destinatario');
DEFINE('_MYSMS_SEND', 'invia');
DEFINE('_MYSMS_PHONENUMBER', 'Numero tel');
DEFINE('_MYSMS_COMMENT', 'comment');
DEFINE('_MYSMS_SAVE', 'salva');
DEFINE('_MYSMS_CANCEL', 'cancella');
DEFINE('_MYSMS_DELETE', 'elimina');
DEFINE('_MYSMS_DATE', 'data');
DEFINE('_MYSMS_BACK', 'indietro');
DEFINE('_MYSMS_NAME', 'Nome');
DEFINE('_MYSMS_NEW_ENTRY', 'Nuovo');
DEFINE('_MYSMS_MEMBERS', 'Membri');
DEFINE('_MYSMS_ACTION', 'Azione');
DEFINE('_MYSMS_CREATE_NEW_GROUP', 'Agggiungi gruppo');
DEFINE('_MYSMS_GROUPNAME', 'Nome Gruppo');
DEFINE('_MYSMS_PHONEBOOK_REMOVE_ENTRY_FAILED', 'Cancellazione Rubrica fallita');
DEFINE('_MYSMS_EDIT_USER_INVALID_DATA', 'Utente invalido');
DEFINE('_MYSMS_INVALID_BALANCE', 'Credito invalido');
DEFINE('_MYSMS_INVALUD_USER_DATA', 'Utente invalido');
DEFINE('_MYSMS_PROVIDER_NOT_FOUND','Caricamento provider fallito');
DEFINE('_MYSMS_PROVIDERFACTORY_NOT_FOUND','ProviderFactory non trovata');
DEFINE('_MYSMS_USER_ADMIN','Amministartore');
DEFINE('_MYSMS_PROVIDER_ADMIN','Amministartore SMS Gateway');
DEFINE('_MYSMS_USERID','ID');
DEFINE('_MYSMS_USERNAME','Nome');
DEFINE('_MYSMS_SELECTED_USER', 'Seleziona Utente');
DEFINE('_MYSMS_ALLOWED_SEND_SMS','Può inviare SMS');
DEFINE('_MYSMS_CREDITS','Crediti');
DEFINE('_MYSMS_NEW_CREDITS','Nuovi Crediti');
DEFINE('_MYSMS_PROVIDER','SMS Provider');
DEFINE('_MYSMS_ACTIV','attivato');
DEFINE('_MYSMS_DETAILS', 'dettagli');
DEFINE('_MYSMS_ADVERTISMENT','Pubblicità');
DEFINE('_MYSMS_LOADLIST' , 'Compra Crediti');
DEFINE('_MYSMS_GLOBAL_SETTINGS', 'Settaggi generali');
DEFINE('_MYSMS_GLOBAL_LIMIT_REACHED', 'Massimo numero SMS raggiunto');
DEFINE('_MYSMS_CONTACTS', 'Contatti');
DEFINE('_MYSMS_GROUPS', 'Gruppi');
DEFINE('_MYSMS_IMPORT_PHONEBOOK', 'Importa rubrica');
DEFINE('_MYSMS_EXPORT_PHONEBOOK', 'Esporta rubrica');
DEFINE('_MYSMS_EXPORT', 'esporta');
DEFINE('_MYSMS_PREREQ_CHECK', 'Prerequisites Check');
DEFINE('_MYSMS_PLUGIN_ABORT', 'Plugin error');
?>