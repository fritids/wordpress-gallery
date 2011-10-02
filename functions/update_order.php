<?php

require($_GET['load'] . '/wp-load.php');

if(update_option('jealous_library', $_GET['order'])){
	echo '<em style="color:green;">Order updated!</em>';
}

?>