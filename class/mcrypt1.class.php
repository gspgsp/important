<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 4/5/17
 * Time: 7:09 AM
 */
class mcrypt1{
    private  $iv = '1234123412341324'; # converted JAVA byte code in to HEX and placed it here
    private  $key = 'ABCDEFGHIGKLMNOP'; #Same as in JAVA 16 32 64 LENGTH

    public function encrypt($data)
    {
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $data, MCRYPT_MODE_CBC, $this->iv);
        return base64_encode($encrypted);
    }

    public function decrypt($data)
    {
        $encryptedData = base64_decode($data);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $encryptedData, MCRYPT_MODE_CBC, $this->iv);
        return $decrypted;
    }
}