<?php

require_once 'CryptoHandler.php';

use App\CryptoHandler;

try {
    $cryptoHandler = new CryptoHandler(__DIR__ . "/../keystore/public_key_AP.pem", __DIR__ . "/../keystore/private_key_AP.pem");

    // クレジットカード番号を含む入力値
    $creditCardNumber = "1234-5678-9012-3456";
    $encryptedCreditCard = $cryptoHandler->encrypt($creditCardNumber);
    $decryptedCreditCard = $cryptoHandler->decrypt($encryptedCreditCard);

    // 日本語を含む入力値
    $japaneseText = "これはサンプルテキストです。";
    $encryptedJapaneseText = $cryptoHandler->encrypt($japaneseText);
    $decryptedJapaneseText = $cryptoHandler->decrypt($encryptedJapaneseText);

    // エスケープが必要な文字を含む入力値
    $escapedText = "This is a multi-line\nescaped text with special characters: \" & '";
    $encryptedEscapedText = $cryptoHandler->encrypt($escapedText);
    $decryptedEscapedText = $cryptoHandler->decrypt($encryptedEscapedText);

    // 結果を出力
    echo "Encrypted Credit Card Number: " . $encryptedCreditCard . "\n";
    echo "Decrypted Credit Card Number: " . $decryptedCreditCard . "\n\n";
    echo "Encrypted Japanese Text: " . $encryptedJapaneseText . "\n";
    echo "Decrypted Japanese Text: " . $decryptedJapaneseText . "\n\n";
    echo "Encrypted Escaped Text: " . $encryptedEscapedText . "\n";
    echo "Decrypted Escaped Text: " . $decryptedEscapedText . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
