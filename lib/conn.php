<?php
require_once 'Database.php';

session_start();

global $_CONFIG;
$conn = new Database($_CONFIG['db']);
?>