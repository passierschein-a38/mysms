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
* $HeadURL: svn://willcodejoomlaforfood.de/mysms/trunk/administrator/components/com_mysms/mysms.crypt.php $
*
* $Id: mysms.crypt.php 184 2009-12-11 17:06:40Z axel $
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

if( defined( 'MYSMS_BACKEND_CRYPTO_PHP' ) == true )
{
  return;
}

/**
 * Define our class constant to precent multipe definition
 */
define( 'MYSMS_BACKEND_CRYPTO_PHP', 1 );

/**
 * Crypto class for encode/decode provider settings.
 * Nobody want's his provider account data in cleartext in a database.
 *
 * @package MySMS
 * @subpackage Util
 */
class mySmsCrypt
{
	/**
	 * encoder array
	 */
	var $encoder;

	/**
	 * decoders array
	 */
	var $decoder;

	/**
	 * chipher key
	 */
	var $key;

	function mySmsCrypt()
	{
		$this->encoder = array();
		$this->decoder = array();

		//first of all is base64
		$this->encoder[] = 'EncodeBase64';
		$this->decoder[] = 'DecodeBase64';

		//the mcrypt pecl is available
		if( function_exists('mcrypt_encrypt') )
		{
			$this->encoder[] = 'EncodeMcrypt';
			$this->decoder[] = 'DecodeMcrypt';
		}

		$this->key 	= md5( substr_replace(JURI::root(), '', -1, 1) );
	}

	/**
	 * Encode
	 */
	function Encode( $obj )
	{
		//first serialze to get a string
		$string = serialize( $obj );

		for( $i = (count($this->encoder) - 1); $i>=0; --$i ){
			$encoder = $this->encoder[$i];
			$string = $this->$encoder( $string );
		}

		return $string;
	}

	/**
	 * Decode
	 */
	function Decode( $string )
	{
		foreach( $this->decoder as $decoder ){
			$string = $this->$decoder( $string );
		}

		return unserialize( $string );
	}

	/*
	 * Encode a give string with mcrypt
	 */
	function EncodeMcrypt( $string )
	{
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$result = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $string, MCRYPT_MODE_ECB, $iv);
		return $result;
	}

	/*
	 * Decode a give string with mcrypt
	 */
	function DecodeMcrypt( $string )
	{
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$result = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $string, MCRYPT_MODE_ECB, $iv);
		$result = rtrim($result, "\0");
		return $result;
	}

	/*
	 * Encode a give string with base64
	 */
	function EncodeBase64( $string )
	{
		return base64_encode( $string );
	}

	/*
	 * Decode a give string with base64
	 */
	function DecodeBase64( $string )
	{
		return base64_decode( $string );
	}
}
?>