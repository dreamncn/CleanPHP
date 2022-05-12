<?php


namespace app\lib\Encryption;

/**
 * Class AESEncryptHelper
 * @package Security\DataSecurity
 */
class AESEncryptHelper
{

    const SHA256 = 'sha256';



    /**
     * @var string
     */
    private string $secretKey;
    
    private string $method = 'AES-256-CBC';


    /**
     * AESEncryptHelper constructor.
     */
    public function __construct($secret_key, $method = 'AES-256-CBC')
    {
        $this->secretKey = $secret_key;
        $this->method = $method;
    }


    /**
     * @param $data
     * @param int $options
     * @return string
     */
    public function encryptWithOpenssl($data, int $options = 0): string
    {
        $iv = substr($this->secretKey, 8, 16);
        return openssl_encrypt($data, $this->method, $this->secretKey, $options, $iv);
    }


    /**
     * @param $data
     * @param int $options
     * @return string
     */
    public function decryptWithOpenssl($data, int $options = 0): string
    {
        $iv = substr($this->secretKey, 8, 16);
        return openssl_decrypt($data, $this->method, $this->secretKey, $options, $iv);
    }


    /**
     * @param $uuid
     * @return string
     */
    public function createSecretKey($uuid): string
    {
        $this->secretKey  = md5($this->sha256WithOpenssl($uuid . '|' . $this->secretKey) . '|' . $this->secretKey);
        return  $this->secretKey;
    }


    /**
     * @param $data
     * @return string
     */
    private function sha256WithOpenssl($data): string
    {
        return openssl_digest($data, self::SHA256);
    }


}
