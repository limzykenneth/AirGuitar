<?php
	/**
	 * SECURE GIT DEPLOYMENT SCRIPT
	 *
	 * Used for automatically deploying websites securely via github
	 *
	 *		Forked from https://gist.github.com/1809044
	 */

	$agent=$_SERVER['HTTP_USER_AGENT'];
	$signature=$_SERVER['HTTP_X_HUB_SIGNATURE'];
	$body=@file_get_contents('php://input');

	// The commands
	$commands = array(
		'git pull origin master',
		'git status',
		'git submodule sync',
		'git submodule update',
		'git submodule status',
	);

	base64_encode($agent);
	base64_encode($signature);
	if (strpos($agent,'GitHub-Hookshot') !== false){
		if (hash_equals($signature, verify_request())){
			// Run the commands
			foreach($commands AS $command){
				// Run it
				$tmp = shell_exec($command);
			}
		}else{
			header('HTTP/1.1 403 Forbidden');
			echo "Invalid request.";
		}
	}else{
		header('HTTP/1.1 403 Forbidden');
		echo "Invalid request.";
	}


	function verify_request(){
		$message = $GLOBALS['body'];
		$key     = "6217e99d55719fcad18aeed6f19fb9bcee225d1d";
	    $hash    = hash_hmac("sha1", $message, $key);
	    $hash = "sha1=".$hash;
	    return $hash;
	}

	function hash_equals( $a, $b ) {
	    $a_length = strlen( $a );
	    if ( $a_length !== strlen( $b ) ) {
	        return false;
	    }
	    $result = 0; 
	    // Do not attempt to "optimize" this.
	    for ( $i = 0; $i < $a_length; $i++ ) {
	        $result |= ord( $a[ $i ] ) ^ ord( $b[ $i ] );
	    } 
	    return $result === 0;
	}
?>