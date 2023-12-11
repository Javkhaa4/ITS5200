<?php
class AES {
    const M_CBC = 'cbc';
    const M_CFB = 'cfb';
    const M_ECB = 'ecb';
    const M_NOFB = 'nofb';
    const M_OFB = 'ofb';
    const M_STREAM = 'stream';

    protected $key;
    protected $cipher;
    protected $data;
    protected $mode;
    protected $IV;

    public function __construct($data = null, $key = null, $blockSize = null, $mode = null) {
        $this->setData($data);
        $this->setKey($key);
        $this->setBlockSize($blockSize);
        $this->setMode($mode);
        // Removed setIV method and initialization
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function setBlockSize($blockSize) {
        switch ($blockSize) {
            case 128:
                $this->cipher = 'AES-256-CBC'; // Updated to use AES-256-CBC
                break;
            // Add other cases as needed
        }
    }

    public function setMode($mode) {
        // Implement setting the encryption mode based on the provided $mode
        // Example: $this->mode = $mode;
    }

    public function encrypt() {
        $this->IV = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $encryptedData = openssl_encrypt($this->data, $this->cipher, $this->key, 0, $this->IV);
        return base64_encode($this->IV . $encryptedData);
    }

    public function decrypt() {
        $data = base64_decode($this->data);
        $ivSize = openssl_cipher_iv_length($this->cipher);
        $this->IV = substr($data, 0, $ivSize);
        $encryptedData = substr($data, $ivSize);
        return openssl_decrypt($encryptedData, $this->cipher, $this->key, 0, $this->IV);
    }
}
?>
