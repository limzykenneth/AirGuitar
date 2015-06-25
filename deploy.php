<?php
	/**
	 * GIT DEPLOYMENT SCRIPT
	 *
	 * Used for automatically deploying websites via github or bitbucket, more deets here:
	 *
	 *		https://gist.github.com/1809044
	 */

	$request=$_SERVER['HTTP_USER_AGENT'];
	base64_encode($request);
	error_log($request);

	function verify_request(){
		$message = "12345";
		$key     = getenv("GIT_TOKEN");
	    $hash    = hash_hmac("sha1", $key, $message);
	    var_dump($hash);
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