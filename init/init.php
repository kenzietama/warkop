<?php
//namespace init;

spl_autoload_register(function ($class) {
	$excludedClasses = [
//		'Database',
//		'Functions'
	];

	$excludedDir = [
//		'init'
	];



	if (in_array($class, $excludedClasses, true)) {
		return;
	}

	$class = str_replace('App', '', $class);
	$dir = __DIR__ . $class . '.php';
	$dir = str_replace('\\init', '', $dir);
	require_once $dir;
//	require_once __DIR__ . $class . '.php';
});

//spl_autoload_register(function ($class) {
//	// Define the directories you want to exclude (e.g., "init")
//	$excludedDir = 'init';
//
//	// Check if the class belongs to the excluded directory
//	$classFile = str_replace('\\', '/', $class); // Convert the namespace to a file path
//	if (strpos($classFile, $excludedDir) !== false) {
//		// Remove 'init' from the file path
//		$classFile = str_replace('init/', '', $classFile);
//	}
//
//	// Construct the full path to the class file
//	$file = __DIR__ . '/' . $classFile . '.php';
//
//	// Include the class file if it exists
//	if (file_exists($file)) {
//		require_once $file;
//	}
//});



//spl_autoload_register(function ($class) {
//	$excludedClasses = [
////		'init',
////		'createmenu'
//	];
//
//	// Directories to exclude
//	$excludedDirs = [
////		'init'
//	];
//
//	// Check if class is in the excluded classes array
//	if (in_array($class, $excludedClasses, true)) {
//		return;
//	}
//
//	// Build the file path for the class
//	$classPath = str_replace('App\\', '', $class); // Adjust for namespace if needed
//	$filePath = __DIR__ . DIRECTORY_SEPARATOR . $classPath . '.php';
//
//	// Exclude files inside the 'init' folder
//	foreach ($excludedDirs as $excludedDir) {
//		if (strpos($filePath, $excludedDir . DIRECTORY_SEPARATOR) !== false) {
//			return; // Skip this file if it's inside the excluded directory
//		}
//	}
//
//	// Require the class file
//	if (file_exists($filePath)) {
//		require_once $filePath;
//	}
//});


// Adjust the autoloader to respect namespaces, if needed
spl_autoload_register(function ($class) {
	$excludedClasses = [
//		'Database', // Exclude certain classes
//		'Functions'
	];

	// Directories to exclude
	$excludedDirs = [
		'init'
	];

	// Check if class is in the excluded classes array
	if (in_array($class, $excludedClasses, true)) {
		return;
	}

	// Replace the namespace separator with directory separator
	$classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);

	$filePath = __DIR__ . DIRECTORY_SEPARATOR . $classPath . '.php';

	// Exclude files inside the 'init' folder
	foreach ($excludedDirs as $excludedDir) {
		if (strpos($filePath, $excludedDir . DIRECTORY_SEPARATOR) !== false) {
			return; // Skip this file if it's inside the excluded directory
		}
	}

	// Require the class file
	if (file_exists($filePath)) {
		require_once $filePath;
	}
});
