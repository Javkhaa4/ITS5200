<?php
include 'AES.php';

function _encrypt($text, $key, $blockSize) {
    $aes = new AES($text, $key, $blockSize);
    return $aes->encrypt();
}

function _decrypt($text, $key, $blockSize) {
    $aes = new AES($text, $key, $blockSize);
    return $aes->decrypt();
}
?>

