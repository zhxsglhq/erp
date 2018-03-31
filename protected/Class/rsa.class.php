<?php
class mycrypt {
  public $pubkey;
  public $privkey;
    function __construct() {
      $this->privkey= file_get_contents('/APM/WWW/works/protected/Class/rsa/rsa_private_key.pem');
      $this->pubkey = file_get_contents('/APM/WWW/works/protected/Class/rsa/rsa_public_key.pem');
    }
    public function encrypt($data) {
        if (openssl_public_encrypt($data, $encrypted, $this->pubkey))
            $data = base64_encode($encrypted);
        else
            $data = '';
        return $data;
    }
    public function decrypt($data) {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey))
            $data = $decrypted;
        else
            $data = '';
        return $data;
    }
}
