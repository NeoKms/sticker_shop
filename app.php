<?php
require_once 'autoload.php';
try{
    App::init();
}
catch (PDOException $e){
    echo "DB is not available </br>";
}
catch (Exception $e){
	if (isset($_REQUEST['DEBUG'])) {
		echo $e->getMessage().PHP_EOL."<pre>".print_r($e->getTrace(),true)."</pre>";
	}
}
