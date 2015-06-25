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

	base64_encode($agent);
	base64_encode($signature);
	if (strpos($agent,'GitHub-Hookshot') !== false){
		error_log($signature);
		error_log(verify_request());
	}else{
		error_log('Request header is invalid.');
	}

	function verify_request(){
		$message = $GLOBALS['body'];
		error_log($message);
		$key     = "6217e99d55719fcad18aeed6f19fb9bcee225d1d";
	    $hash    = hash_hmac("sha1", $key, $message);
	    return $hash;
	}

	// The commands
	$commands = array(
		'git pull origin master',
		'git status',
		'git submodule sync',
		'git submodule update',
		'git submodule status',
	);

	// Run the commands
	foreach($commands AS $command){
		// Run it
		$tmp = shell_exec($command);
	}

?>