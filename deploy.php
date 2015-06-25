<?php
	/**
	 * GIT DEPLOYMENT SCRIPT
	 *
	 * Used for automatically deploying websites via github or bitbucket, more deets here:
	 *
	 *		https://gist.github.com/1809044
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
			error_log("Success!");
		}else{
			error_log("Signature is invalid.");
		}
	}else{
		error_log('Request header is invalid.');
	}


	function verify_request(){
		$message = $GLOBALS['body'];
		$key     = "6217e99d55719fcad18aeed6f19fb9bcee225d1d";
	    $hash    = hash_hmac("sha1", $message, $key);
	    return $hash;
	}

	// Run the commands
	foreach($commands AS $command){
		// Run it
		$tmp = shell_exec($command);
	}

?>