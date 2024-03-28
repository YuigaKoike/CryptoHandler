<?php

namespace Tokenizer\CryptoHandler;

class CryptoHandler {
    private $requestPublicKey;
    private $responsePrivateKey;

    public function __construct($requestPublicKeyPath, $responsePrivateKeyPath) {
        // 公開鍵ファイルの内容を取得
        $this->requestPublicKey = file_get_contents($requestPublicKeyPath);
        if ($this->requestPublicKey === false) {
            throw new \Exception("Failed to read request public key file.");
        }

        // 秘密鍵ファイルの内容を取得
        $this->responsePrivateKey = file_get_contents($responsePrivateKeyPath);
        if ($this->responsePrivateKey === false) {
            throw new \Exception("Failed to read response private key file.");
        }
    }

    public function encrypt($data) {
        $encryptedData = '';
        // 128バイトごとに区切って暗号化する
        while ($data) {
            $chunk = substr($data, 0, 128);
            $data = substr($data, 128);
            if (!openssl_public_encrypt($chunk, $encryptedChunk, $this->requestPublicKey)) {
                throw new \Exception("Failed to encrypt data.");
            }
            $encryptedData .= base64_encode($encryptedChunk) . '\n';
        }
        // 最後の余分なハイフンを取り除く
        $encryptedData = rtrim($encryptedData, '\n');
        return $encryptedData;
    }

    public function decrypt($encryptedData) {
        // '\n'で区切って各チャンクを配列に分割する
        $chunks = explode('\n', $encryptedData);
        // 復号化されたデータを格納する変数
        $decryptedData = '';
        // 各チャンクを復号化して結合する
        foreach ($chunks as $chunk) {
            if (!openssl_private_decrypt(base64_decode($chunk), $decryptedChunk, $this->responsePrivateKey)) {
                throw new \Exception("Failed to decrypt data.");
            }
            $decryptedData .= $decryptedChunk;
        }
        return $decryptedData;
    }
}
