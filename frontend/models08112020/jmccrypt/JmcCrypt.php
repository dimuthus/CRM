<?php

namespace frontend\models\jmccrypt;

use Yii;
use yii\BaseYii;

/**
 * Encryption and Decryption class
 */
class JmcCrypt
{

  private static $iv = 'vbjD@fsdGsjkl4W4';
  private static $key = 'j&)7*S6FM#*^%07$&c';
  private static $td;

  	function encrypt($str) {
  		self::$td = mcrypt_module_open ( 'rijndael-128', '', 'cbc', self::$iv );
  		mcrypt_generic_init ( self::$td, self::$key, self::$iv );

  		$encrypted = mcrypt_generic ( self::$td, utf8_encode ( $this->pkcs5_pad ( $str ) ) );

  		mcrypt_generic_deinit ( self::$td );
  		mcrypt_module_close ( self::$td );

  		return bin2hex ( $encrypted );
  		// return base64_encode(bin2hex($encrypted));
  	}
  	function decrypt($encryptstr) {

  		// $code = $this->hex2bin(base64_decode($code));
  		$encryptstr = $this->hex2bin ( $encryptstr );
  		self::$td = mcrypt_module_open ( 'rijndael-128', '', 'cbc', self::$iv );
  		mcrypt_generic_init ( self::$td,self::$key, self::$iv );

  		$decrypted = mdecrypt_generic ( self::$td, $encryptstr );

  		mcrypt_generic_deinit ( self::$td );
  		mcrypt_module_close ( self::$td );

  		return trim ( $this->pkcs5_unpad ( utf8_decode ( $decrypted ) ) );
  	}
  	protected function hex2bin($hexdata) {
  		$bindata = '';

  		for($i = 0; $i < strlen ( $hexdata ); $i += 2) {
  			$bindata .= chr ( hexdec ( substr ( $hexdata, $i, 2 ) ) );
  		}

  		return $bindata;
  	}
  	protected function pkcs5_pad($text) {
  		// return $text;
  		$blocksize = 16;
  		$pad = $blocksize - (strlen ( $text ) % $blocksize);
  		return $text . str_repeat ( chr ( $pad ), $pad );
  	}
  	protected function pkcs5_unpad($text) {
  		$pad = ord ( $text {strlen ( $text ) - 1} );
  		if ($pad > strlen ( $text ))
  			return false;

  		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
  			return false;

  		return substr ( $text, 0, - 1 * $pad );
  	}

    function HashMe($str){

      $key = self::$key;
      return hash('ripemd160', $str.$key.Yii::$app->user->identity->id.Yii::$app->session->getId());
    }

}
