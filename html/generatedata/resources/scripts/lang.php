<?php

// TODO this kinda sucks. We've already initialized Core in the calling page. Can't we get around re-doing it here?
// Check sessions? What's stored there? Could we store everything, or would it be better to just re-init Core like now?
// Does it matter?
require_once(realpath(dirname(__FILE__) . "/../../library.php"));
Core::init();

//session_start();
header("Cache-control: private");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-type: application/x-javascript");


$js = Core::$language->getLanguageStringsJS();
?>
define([
], function() {

	<?php echo $js?>

	return L;
});