<?php

class Encryption {
    protected $Encryption_Key;

    public function __construct() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        // Ensure the encryption key is 32 bytes (256 bits)
        $this->Encryption_Key = hash('sha256', getenv('ENCRYPTION_KEY'), true);
    }

    public function encrypt($plaintext, $key) {
        $ivlen = $this->getCipherIvLength();
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $ciphertext);
    }

    public function decrypt($ciphertext_base64, $key) {
        $ivlen = $this->getCipherIvLength();
        $ciphertext_combined = base64_decode($ciphertext_base64);

        // Error handling for incorrect ciphertext length
        if (strlen($ciphertext_combined) < $ivlen) {
            throw new Exception("Invalid ciphertext length.");
        }

        $iv = substr($ciphertext_combined, 0, $ivlen);
        $ciphertext = substr($ciphertext_combined, $ivlen);

        // Decrypt the data
        $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, 0, $iv);
        if ($decrypted === false) {
            throw new Exception("Decryption failed.");
        }

        return $decrypted;
    }

    private function getCipherIvLength() {
        return openssl_cipher_iv_length('aes-256-cbc');
    }

    public function passEncrypt($original) {
        $encrypted = $this->encrypt($original, $this->Encryption_Key);
        
        return $encrypted;
    }

    public function passDecrypt($original) {
        $decrypted = $this->decrypt($original, $this->Encryption_Key);
        
        return $decrypted;
    }
}
?>