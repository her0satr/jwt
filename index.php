<?php
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	include 'vendor/autoload.php';

	use Namshi\JOSE\SimpleJWS;
	
	// encode
    $jws  = new SimpleJWS(array(
        'alg' => 'RS256'
    ));
    $jws->setPayload(array(
        'uid' => '12345 12345',
    ));
	$privateKey = openssl_pkey_get_private(file_get_contents("/site-data/html/_temp/composer/key/private.pem"), 'hello');
	$jws->sign($privateKey);
	echo $jws->getTokenString().'<br /><br /><br />';
	print_r($jws->getPayload());
	echo '<br /><br /><br />';
	
	
	// decode
	$my_jws = SimpleJWS::load($jws->getTokenString());
	$public_key = openssl_pkey_get_public(file_get_contents("/site-data/html/_temp/composer/key/public.pem"));
	
	// verify that the token is valid and had the same values
	// you emitted before while setting it as a cookie
	if ($my_jws->isValid($public_key, 'RS256')) {
		$payload = $my_jws->getPayload();
		print_r($payload);
	} else {
		echo 'decode fail.';
	}
	
	