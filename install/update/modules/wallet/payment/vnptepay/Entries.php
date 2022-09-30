<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 29, 2010  10:42:00 PM
 */

class login
{
	public $m_UserName;
	public $m_Pass;
	public $m_PartnerID;
	public $soapClient;
	
	/**
	 * login::login_()
	 * 
	 * @return
	 */
	function login_()
	{
		$Obj = new loginResponse();
		$RSAClass = new ClsCryptor();
		
		//Ham thuc hien lay public key cua EPAy tu file pem
		$RSAClass->GetpublicKeyFrompemFile( NV_ROOTDIR . "/modules/wallet/payment/vnptepay/Key/Epay_Public_key.pem" );
		
		try
		{
			$EncrypedPass = $RSAClass->encrypt( $this->m_Pass );
		}
		catch ( exception $ex )
		{
		}
		
		$Pass = base64_encode( $EncrypedPass );
		//$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");

		try
		{
			$result = $this->soapClient->login( $this->m_UserName, $Pass, $this->m_PartnerID ); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
		}
		catch ( exception $ex )
		{
			trigger_error( "Xay ra loi khi thuc hien login: " . $ex, 256 );
		}

		$Obj->m_Sessage = $result->message;
		//$Obj->m_SessionID = $result->sessionid;
		$Obj->m_Status = $result->status;

		//Ham thuc hien lay private key cua doi tac tu file pem
		$RSAClass->GetPrivatekeyFrompemFile( NV_ROOTDIR . "/modules/wallet/payment/vnptepay/Key/kh0016_mykey.pem" );
		
		try
		{
			$Session_Decryped = $RSAClass->decrypt( base64_decode( $result->sessionid ) );
			$Obj->m_SessionID = $this->Hextobyte( $Session_Decryped );
		}
		catch ( exception $ex )
		{
			trigger_error( "Co loi khi thuc hien giai mai session: " . $ex, 256 );
		}

		$Obj->m_TransID = $result->transid;

		return $Obj;
	}
	
	/**
	 * login::Hextobyte()
	 * 
	 * @param mixed $strHex
	 * @return
	 */
	function Hextobyte( $strHex )
	{
		$string = '';
		for( $i = 0; $i < strlen( $strHex ) - 1; $i += 2 )
		{
			$string .= chr( hexdec( $strHex[$i] . $strHex[$i + 1] ) );
		}
		return $string;
	}

	/**
	 * login::ByteToHex()
	 * 
	 * @param mixed $strHex
	 * @return
	 */
	function ByteToHex( $strHex )
	{
		return bin2hex( $strHex );
	}
}

class loginResponse
{
	public $m_Status;
	public $m_Sessage;
	///
	// Session do VNPT EPAY cung cap cho doi t�c d�ng de ma hoa du lieu va xac thuc thong tin.
	//SessinID gui ve tu VNPT EPAY duoc ma hoa bang public key cua merchant theo thuat toan RSA
	///
	public $m_SessionID;
	public $m_TransID;
}

class logout
{
	public $m_UserName;
	public $m_PartnerID;
	public $m_SessionID;
	public $soapClient;
	
	/**
	 * logout::logout_()
	 * 
	 * @return
	 */
	function logout_()
	{
		$Ojb = new LogoutResponse();

		//$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
		
		try
		{
			$result = $this->soapClient->logout( $this->m_UserName, $this->m_PartnerID, md5( $this->m_SessionID ) ); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
		}
		catch ( exception $ex )
		{
		}
		
		$Obj->m_Status = $result->status;
		$Obj->m_Message = $result->message;
		
		return $Obj;
	}
}

class LogoutResponse
{
	public $m_Status;
	public $m_Message;

}

class ChangePassword
{
	public $m_TransID;
	public $m_UserName;
	public $m_PartnerID;
	public $m_OLD_PASSWORD;
	public $m_NEW_PASSWORD;
	public $m_SessionID;
	public $soapClient;
	
	/**
	 * ChangePassword::ChangePassword_()
	 * 
	 * @return
	 */
	function ChangePassword_()
	{
		$Ojb = new ChangeResponse();
		$ObjTriptDes = new TriptDes( $this->m_SessionID );
		
		try
		{
			$OldPass = $ObjTriptDes->EncrypTriptDes( $this->m_OLD_PASSWORD );
			$NewPass = $ObjTriptDes->EncrypTriptDes( $this->m_NEW_PASSWORD );
		}
		catch ( exception $ex )
		{
		}
		
		//$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
		
		try
		{
			$result = $this->soapClient->changePassword( $this->m_TransID, $this->m_UserName, $this->m_PartnerID, $OldPass, $NewPass, md5( $this->m_SessionID ) ); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
		}
		catch ( exception $ex )
		{
		}
		
		$Obj->m_Status = $result->status;
		$Obj->m_Message = $result->message;
		
		return $Obj;
	}
}

class ChangeResponse
{
	public $m_Status;
	public $m_Message;
}

class ChangMPin
{
	public $m_TransID;
	public $m_UserName;
	public $m_PartnerID;
	public $m_OLD_OLD_MPIN;
	public $m_NEW_MPIN;
	public $m_SessionID;
	public $soapClient;
	
	/**
	 * ChangMPin::ChangMPin_()
	 * 
	 * @return
	 */
	function ChangMPin_()
	{
		$Ojb = new ChangeResponse();
		$ObjTriptDes = new TriptDes( $this->m_SessionID );
		
		try
		{
			$OldMpin = $ObjTriptDes->EncrypTriptDes( $this->m_OLD_OLD_MPIN );
			$NewMpin = $ObjTriptDes->EncrypTriptDes( $this->m_NEW_MPIN );
		}
		catch ( exception $ex )
		{
		}

		//$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");

		try
		{
			$result = $this->soapClient->changeMPIN( $this->m_TransID, $this->m_UserName, $this->m_PartnerID, $OldMpin, $NewMpin, md5( $this->m_SessionID ) ); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
		}
		catch ( exception $ex )
		{
		}

		$Obj->m_Status = $result->status;
		$Obj->m_Message = $result->message;

		return $Obj;
	}
}

class CardCharging
{
	public $m_TransID;
	public $m_UserName;
	public $m_PartnerID;
	public $m_MPIN;
	public $m_Target;
	public $m_Card_DATA;
	public $m_Pass;
	var $SessionID;
	var $soapClient;
	
	/**
	 * CardCharging::CardCharging_()
	 * 
	 * @return
	 */
	function CardCharging_()
	{
		if( $this->SessionID == null || $this->SessionID == "" )
		{
			$login = new login();
			$login->m_UserName = $this->m_UserName;
			$login->m_Pass = $this->m_Pass;
			$login->m_PartnerID = $this->m_PartnerID;
			$login->soapClient = $this->soapClient;

			$loginresponse = new loginResponse();
			$loginresponse = $login->login_();

			if( $loginresponse->m_Status == "1" )
			{
				//Nen luu lai bien SessionID de thuc hien cac ham charging tiep theo
				//Tranh viec moi giao dich charging lai login 1 lan nhu vay giao dich se rat cham.
				$this->SessionID = bin2hex( $loginresponse->m_SessionID );
			}
			else
			{
				trigger_error( "Dang nhap khong thanh cong: " . $loginresponse->m_Sessage, 256 );
			}
		}

		///Bat dau thuc hien charging
		$Ojb = new CardChargingResponse();
		$key = $this->Hextobyte( $this->SessionID );
		//$keytesst = base64_encode($key);

		$ObjTriptDes = new TriptDes( $key );

		try
		{
			$strEncreped = $ObjTriptDes->encrypt( $this->m_MPIN );
			//$decode =  $ObjTriptDes->decrypt(  $strEncreped);
			$Mpin = $this->ByteToHex( $strEncreped );

			$Card_DATA = $this->ByteToHex( $ObjTriptDes->encrypt( $this->m_Card_DATA ) );
		}
		catch ( exception $ex )
		{
			trigger_error( "Co loi xay ra khi ma hoa mpin: " . $ex, 256 );
		}
		
		//$soapClient = new Soapclient("http://192.168.0.85:10001/CardChargingGW_0108/services/Services?wsdl");
		
		try
		{
			$result = $this->soapClient->cardCharging( $this->m_TransID, $this->m_UserName, $this->m_PartnerID, $Mpin, $this->m_Target, $Card_DATA, md5( $this->SessionID ) ); // goi ham login de lay session id du lieu tra ve la mot mang voi cac thong tin message, sessionid,status,transid
		}
		catch ( exception $ex )
		{
			trigger_error( "Co loi xay ra khi thuc hien charging: " . $ex, 256 );
		}

		$Obj->m_Status = $result->status;
		$Obj->m_Message = $result->message;
		$Obj->m_TRANSID = $result->transid;
		$Obj->m_AMOUNT = $result->amount;
		
		$resAmount = $ObjTriptDes->decrypt( $this->Hextobyte( $result->responseamount ) );
		
		$Obj->m_RESPONSEAMOUNT = $resAmount; //$result->responseamount;
		
		if( $Obj->m_Status == 3 || $Obj->m_Status == 7 ) $this->SessionID = null;
		
		return $Obj;
	}

	/**
	 * CardCharging::Hextobyte()
	 * 
	 * @param mixed $strHex
	 * @return
	 */
	function Hextobyte( $strHex )
	{
		$string = '';
		for( $i = 0; $i < strlen( $strHex ) - 1; $i += 2 )
		{
			$string .= chr( hexdec( $strHex[$i] . $strHex[$i + 1] ) );
		}
		return $string;
	}
	
	/**
	 * CardCharging::ByteToHex()
	 * 
	 * @param mixed $strHex
	 * @return
	 */
	function ByteToHex( $strHex )
	{
		return bin2hex( $strHex );
	}
}

class CardChargingResponse
{
	public $m_Status;
	public $m_Message;
	public $m_TRANSID;
	public $m_AMOUNT;
	public $m_RESPONSEAMOUNT;

}

class TriptDes
{
	private $DessKey;
	
	/**
	 * TriptDes::TriptDes()
	 * 
	 * @param mixed $key
	 * @return
	 */
	public function TriptDes( $key )
	{
		$this->DessKey = $key;
	}
	
	/**
	 * TriptDes::decrypt()
	 * 
	 * @param mixed $text
	 * @return
	 */
	public function decrypt( $text )
	{
		$key = $this->DessKey;
		$size = mcrypt_get_iv_size( MCRYPT_3DES, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv( $size, MCRYPT_RAND );
		$decrypted = mcrypt_decrypt( MCRYPT_3DES, $key, $text, MCRYPT_MODE_ECB, $iv );
		return rtrim( $this->pkcs5_unpad( $decrypted ) );
	}

	/**
	 * TriptDes::encrypt()
	 * 
	 * @param mixed $text
	 * @return
	 */
	public function encrypt( $text )
	{
		$key = $this->DessKey;
		$text = $this->pkcs5_pad( $text, 8 ); // AES?16????????
		$size = mcrypt_get_iv_size( MCRYPT_3DES, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv( $size, MCRYPT_RAND );
		$bin = pack( 'H*', bin2hex( $text ) );
		$encrypted = mcrypt_encrypt( MCRYPT_3DES, $key, $bin, MCRYPT_MODE_ECB, $iv );
		return $encrypted;
	}

	/**
	 * TriptDes::pkcs5_pad()
	 * 
	 * @param mixed $text
	 * @param mixed $blocksize
	 * @return
	 */
	function pkcs5_pad( $text, $blocksize )
	{
		$pad = $blocksize - ( strlen( $text ) % $blocksize );
		return $text . str_repeat( chr( $pad ), $pad );
	}

	/**
	 * TriptDes::pkcs5_unpad()
	 * 
	 * @param mixed $text
	 * @return
	 */
	function pkcs5_unpad( $text )
	{
		$pad = ord( $text{strlen( $text ) - 1} );
		if( $pad > strlen( $text ) ) return false;
		if( strspn( $text, chr( $pad ), strlen( $text ) - $pad ) != $pad ) return false;
		return substr( $text, 0, -1 * $pad );
	}
}

class ClsCryptor
{
	private $RsaPublicKey;
	private $RsaPrivateKey;
	private $TripDesKey;
	
	/**
	 * ClsCryptor::GetpublicKeyFromCertFile()
	 * 
	 * @param mixed $filePath
	 * @return
	 */
	public function GetpublicKeyFromCertFile( $filePath )
	{
		$fp = fopen( $filePath, "r" );
		$pub_key = fread( $fp, filesize( $filePath ) );
		fclose( $fp );
		openssl_get_publickey( $pub_key );

		$this->RsaPublicKey = $pub_key;
	}

	/**
	 * ClsCryptor::GetpublicKeyFrompemFile()
	 * 
	 * @param mixed $filePath
	 * @return
	 */
	public function GetpublicKeyFrompemFile( $filePath )
	{
		$fp = fopen( $filePath, "r" );
		$pub_key = fread( $fp, filesize( $filePath ) );
		fclose( $fp );
		openssl_get_publickey( $pub_key );
		//print_r($pub_key);
		$this->RsaPublicKey = $pub_key;
		//print_r($this-> RsaPublicKey);
	}

	/**
	 * ClsCryptor::GetPrivatekeyFrompemFile()
	 * 
	 * @param mixed $filePath
	 * @return
	 */
	public function GetPrivatekeyFrompemFile( $filePath )
	{
		$fp = fopen( $filePath, "r" );
		$pub_key = fread( $fp, filesize( $filePath ) );
		fclose( $fp );
		$this->RsaPrivateKey = $pub_key;


	}
	
	/**
	 * ClsCryptor::GetPrivate_Public_KeyFromPfxFile()
	 * 
	 * @param mixed $filePath
	 * @param mixed $Passphase
	 * @return
	 */
	public function GetPrivate_Public_KeyFromPfxFile( $filePath, $Passphase )
	{
		$p12cert = array();
		$fp = fopen( $filePath, "r" );
		$p12buf = fread( $fp, filesize( $filePath ) );
		fclose( $fp );
		openssl_pkcs12_read( $p12buf, $p12cert, $Passphase );
		$this->RsaPrivateKey = $p12cert['pkey'];
		$this->RsaPublicKey = $p12cert['cert'];

	}

	/**
	 * ClsCryptor::encrypt()
	 * 
	 * @param mixed $source
	 * @return
	 */
	function encrypt( $source )
	{
		//path holds the certificate path present in the system
		$pub_key = $this->RsaPublicKey;
		//$source="sumanth";
		$j = 0;
		$x = strlen( $source ) / 10;
		$y = floor( $x );
		$crt = '';
		//print_r($pub_key) ;
		for( $i = 0; $i < $y; $i++ )
		{
			$crypttext = '';

			openssl_public_encrypt( substr( $source, $j, 10 ), $crypttext, $pub_key );
			$j = $j + 10;
			$crt .= $crypttext;
			$crt .= ":::";
		}
		if( ( strlen( $source ) % 10 ) > 0 )
		{
			openssl_public_encrypt( substr( $source, $j ), $crypttext, $pub_key );
			$crt .= $crypttext;
		}
		return ( $crt );

	}
	
	//Decryption with private key
	/**
	 * ClsCryptor::decrypt()
	 * 
	 * @param mixed $crypttext
	 * @return
	 */
	function decrypt( $crypttext )
	{
		$priv_key = $this->RsaPrivateKey;
		$tt = explode( ":::", $crypttext );
		$cnt = count( $tt );
		$i = 0;
		$str = '';
		while( $i < $cnt )
		{
			openssl_private_decrypt( $tt[$i], $str1, $priv_key );
			$str .= $str1;
			$i++;
		}
		return $str;
	}
}