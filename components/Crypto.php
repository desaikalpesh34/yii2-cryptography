<?php
namespace cryptography\components;

use yii\base\Component;
use yii\base\InvalidConfigException;

class Crypto extends Component
{    
    private $_secrateKey;
    protected $method;
   
    public function init()
    {
        if (!$this->_secrateKey) {
            throw new InvalidConfigException('"' . get_class($this) . '::secrateKey" cannot be null.');
        }
        if($this->method=='')
        {
          $this->method = 'AES-128-ECB'; //default method you can change
        }
    }
    
    /**
     * Sets the secrate key for the encrypter
     */
    public function setsecrateKey($secrateKey)
    {
        $this->_secrateKey = $this->generateCipherText($secrateKey);
    }
    
    /**
     * set encryption/decryption method
     */
    private function setMethod($method)
    {
        $this->method = $method;
    }
   
    /**
     * get secrate key 
     */
    private function getBaseString()
    {
        return $this->_secrateKey;
    }
    
    
    /**
     * Encrypts a data/string
     */
    public function encrypt($data)
    {
        if(!self::is_base64($data))
        {
            $chiperIvLength = openssl_cipher_iv_length($this->method);
            $iv = openssl_random_pseudo_bytes($chiperIvLength);
            $padValue = 16 - (strlen($data) % 16);

            $ciperText =  openssl_encrypt(
                str_pad($data, intval(16 * (floor(strlen($data) / 16) + 1)), chr($padValue)),
                $this->method,
                $this->_secrateKey,
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                $iv
            );
            return base64_encode($ciperText);
        }else
        {
            return $data;
        }
    }
    
    /**
     * Decrypts a data/string. 
     * False is returned in case it was not possible to decrypt it.
     */
    public function decrypt($data)
    {
        if($this->is_base64($data))
        {
            $data = base64_decode($data);

            $data = openssl_decrypt(
                $data,
                $this->method,
                $this->_secrateKey,
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
            );

            return rtrim($data, "\x00..\x10");
        }else
        {
            return $data;
        }
    }
    
    
     /**
     * check encryption/decryption trigger or not.
     */
    public function is_base64($string)
    {
        if (base64_encode(base64_decode($string, true)) === $string){
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * generating cipher text
     */
    protected function generateCipherText($cipher)
    {
        $string = str_repeat(chr(0), 16);
        for ($i = 0, $len = strlen($cipher); $i < $len; $i++) {
            $string[$i % 16] = $string[$i % 16] ^ $cipher[$i];
        }

        return $string;
    }
}
