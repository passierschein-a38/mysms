<?php
/**
* $Id: spanish.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_LANG_SPANISH_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_LANG_SPANISH_PHP', 1 );


// no direct access
defined( '_VALID_MOS' ) or die( 'access denied !' );


DEFINE('_MYSMS_CHANGES_SAVED', 'Cambios salvados exitosamente');
DEFINE('_MYSMS_INVALID_PHONENUMBER', 'N&uacute;mero telefÃ³nico inv&aacute;lido');
DEFINE('_MYSMS_SQLQUERY_ERROR', 'Error en la consulta de la Base de Datos');
DEFINE('_MYSMS_INVALID_POSTRELOAD_TOKEN', 'Post Reload block');
DEFINE('_MYSMS_POST_RELOAD_BLOCK', 'Post Reload block' );
DEFINE('_MYSMS_INVALID_SENDER', 'N&uacute;mero del remitente inv&aacute;lido');
DEFINE('_MYSMS_INVALID_RECIPIENT', 'N&uacute;mero del receptor inv&aacute;lido');
DEFINE('_MYSMS_NO_ACTIVE_PROVIDER', 'Por favor verifique la configuraci&oacute;n de sms gateway');
DEFINE('_MYSMS_NOT_SUFFICIENT_FUNDS' , 'Cantidad de Cr&eacute;ditos muy baja');
DEFINE('_MYSMS_SEND_SMS_FAILED', 'SMS no se ha podido enviar a %s debido a: %s');
DEFINE('_MYSMS_SEND_SMS_SUCCESSFULLY', 'SMS enviado exitosamente a %s');
DEFINE('_MYSMS_CHANGE_BALANCE_FAILED', 'No se puede cambiar los cr&eacute;ditos');
DEFINE('_MYSMS_PHONEBOOKENTRY_ENTRY_SUCCESSFULLY_ADDED', 'Nuevo registro salvado');
DEFINE('_MYSMS_ADD_GROUP_FAILED', 'No se puede agregar un grupo');
DEFINE('_MYSMS_ADD_GROUP_NO_SELECTION' ,'No se puede agregar un grupo, ning&uacute;n contacto seleccionado');
DEFINE('_MYSMS_GROUPNAME_MISSING', 'Nombre del grupo inv&aacute;lido');
DEFINE('_MYSMS_GROUP_ADD_MEMBER_FAILED', 'No se pudo agregar el contacto al grupo');
DEFINE('_MYSMS_INTERNAL_ERROR', 'Error Interno');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_FAILED', 'No se ha pudo borrar el registro de la agenda');
DEFINE('_MYSMS_DEL_PHONEBOOK_ENTRY_SUCCESSFULLY', 'Nuevo registro salvado');
DEFINE('_MYSMS_ERROR', 'Error');
DEFINE('_MYSMS_MY_CONFIG', 'Mi Configuraci&oacute;n');
DEFINE('_MYSMS_GLOBAL_CONFIG', 'Configuraci&eacute;n Global');
DEFINE('_MYSMS_EDIT', 'Editar');
DEFINE('_MYSMS_SMSARCHIVE', 'SMS Archivados');
DEFINE('_MYSMS_SHOW', 'Mostrar');
DEFINE('_MYSMS_MY_PHONEBOOK', 'Mi Agenda');
DEFINE('_MYSMS_USER_GROUP', 'Grupos');
DEFINE('_MYSMS_DISPATCH', 'Dispatch');
DEFINE('_MYSMS_BALANCE', 'Balance');
DEFINE('_MYSMS_MESSAGE', 'Mensaje');
DEFINE('_MYSMS_SENDER', 'De');
DEFINE('_MYSMS_RECIPIENT', 'Para');
DEFINE('_MYSMS_SEND', 'Enviar');
DEFINE('_MYSMS_PHONENUMBER', 'N&uacute;mero');
DEFINE('_MYSMS_COMMENT', 'Comentario');
DEFINE('_MYSMS_SAVE', 'Salvar');
DEFINE('_MYSMS_CANCEL', 'Cancelar');
DEFINE('_MYSMS_DELETE', 'Borrar');
DEFINE('_MYSMS_DATE', 'Fecha');
DEFINE('_MYSMS_BACK', 'Atr&aacute;s');
DEFINE('_MYSMS_NAME', 'Nombre');
DEFINE('_MYSMS_NEW_ENTRY', 'Nuevo Contacto');
DEFINE('_MYSMS_MEMBERS', 'Miembros');
DEFINE('_MYSMS_ACTION', 'Acci&oacute;n');
DEFINE('_MYSMS_CREATE_NEW_GROUP', 'Crear un nuevo grupo');
DEFINE('_MYSMS_GROUPNAME', 'Nombre del grupo');
DEFINE('_MYSMS_PHONEBOOK_REMOVE_ENTRY_FAILED', 'Unable to delte phonebook entry');
DEFINE('_MYSMS_EDIT_USER_INVALID_DATA', 'Usuario Inv&aacute;lido');
DEFINE('_MYSMS_INVALID_BALANCE', 'Cr&eacute;ditos Inv&aacute;lidos');
DEFINE('_MYSMS_INVALUD_USER_DATA', 'Usuario Inv&aacute;lido');
DEFINE('_MYSMS_PROVIDER_NOT_FOUND','No se ha podido cargar el proveedor');
DEFINE('_MYSMS_PROVIDERFACTORY_NOT_FOUND','ProviderFactory no encontrado');
DEFINE('_MYSMS_USER_ADMIN','Admin. Usuario');
DEFINE('_MYSMS_PROVIDER_ADMIN','Admin. SMS Gateway');
DEFINE('_MYSMS_USERID','ID');
DEFINE('_MYSMS_USERNAME','Alias');
DEFINE('_MYSMS_SELECTED_USER', 'Usuario seleccionado');
DEFINE('_MYSMS_ALLOWED_SEND_SMS','Puede enviar SMS');
DEFINE('_MYSMS_CREDITS','Cr&eacute;ditos');
DEFINE('_MYSMS_NEW_CREDITS','Nuevo Cr&eacute;dito');
DEFINE('_MYSMS_PROVIDER','Proveedor SMS');
DEFINE('_MYSMS_ACTIV','activado');
DEFINE('_MYSMS_DETAILS', 'detalles');
DEFINE('_MYSMS_ADVERTISMENT','Anuncios');
DEFINE('_MYSMS_LOADLIST' , 'Charge Credits');
DEFINE('_MYSMS_GLOBAL_SETTINGS', 'Configuraci&oacute;n Global');
DEFINE('_MYSMS_GLOBAL_LIMIT_REACHED', 'La cantidad m&aacute;xima de SMS ha sido enviada, l&iacute;mite global alcanzado');
DEFINE('_MYSMS_CONTACTS', 'Contactos');
DEFINE('_MYSMS_GROUPS', 'Groupos');
DEFINE('_MYSMS_IMPORT_PHONEBOOK', 'Importar agenda');
DEFINE('_MYSMS_EXPORT_PHONEBOOK', 'Exportar agenda');
DEFINE('_MYSMS_EXPORT', 'Exportar');
DEFINE('_MYSMS_MISSING', 'desaparecido');
DEFINE('_MYSMS_SHOW_ABOUT', 'Acerca de MySms');
DEFINE('_MYSMS_PREREQ_CHECK', 'Comprobar Prerrequisitos');
DEFINE('_MYSMS_PLUGIN_ABORT', 'Plugin error');
?>