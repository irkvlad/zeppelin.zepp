    <?php
 ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 


	echo 'Xdebug';
	$object = new stdClass();
    $array = array(1, 'var_dump test', 4 => $object);
    var_dump($array);
	
	?>
	45