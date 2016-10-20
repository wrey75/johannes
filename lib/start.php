<?php

	include __DIR__."/../vendor/autoload.php";

	// Register the QMS classes
	spl_autoload_register(function ($class) {
		$class_path = str_replace("\\", "/", $class);
		$php_file = __DIR__."/{$class_path}.php";
		if( file_exists($php_file) ){
			//	echo "** including $class => $php_file\n";
			include $php_file;
			//	echo "** included $class => $php_file\n";
		}
 		else {
 			echo "** CAN NOT LOAD '{$php_file}'.\n";
 		}
	});
	
	
