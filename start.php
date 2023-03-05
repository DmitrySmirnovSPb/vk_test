<?php
	mb_internal_encoding('UTF-8');
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	set_include_path(get_include_path().PATH_SEPARATOR.'class'.PATH_SEPARATOR.'lib');
	spl_autoload_extensions('_class.php');
	spl_autoload_register();
?>