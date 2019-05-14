<?php
class DesUtils{
	//加密  
	public function encrypt($str, $key){  
		$block = mcrypt_get_block_size('des', 'ecb');  
		$pad = $block - (strlen($str) % $block);  
		$str .= str_repeat(chr($pad), $pad);  
		return base64_encode(mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB) );  
	}
	//解密  
	public function decrypt($sStr, $sKey) {    
			$decrypted= mcrypt_decrypt(    
			MCRYPT_DES,    
			$sKey,    
			base64_decode($sStr),    
			MCRYPT_MODE_ECB    
		);
		$dec_s = strlen($decrypted);    
		$padding = ord($decrypted[$dec_s-1]);    
		$decrypted = substr($decrypted, 0, -$padding);    
		return $decrypted;    
	}
}
?>