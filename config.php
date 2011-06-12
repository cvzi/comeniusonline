<?php

// MySQL Data
$MySQLHost = 'localhost'; // Usually defaults to 'localhost'
$MySQLName = 'root';
$MySQLPassword = '';
$MySQLDBName = 'com2';

// MySQL Tables
$MySQL_tables = array();
$MySQL_tables['user'] = 'com_user';
$MySQL_tables['profile'] = 'com_profile';  // Additional profile tables:  $MySQL_tables['profile'].'_'.$listname; See $comeniusfile for additional list names
$MySQL_tables['notes'] = 'com_notes';


// Comenius Config
$comeniusfile = 'comenius.xml';

// Smarty Dir
$smartydir = 'smarty/';
// On UNIX machines: Be sure to CHMOD $smartydir . 'templates_c/' and make it writable for PHP


// Navi File
$navifile = 'navigation.xml';

// Class Dir
$classdir = 'class/';

// Error Strings File
$errorsfile = 'templates/errors.xml';

// autoload: Load class files dynamically during runtime
function __autoload($class_name) {
    global $classdir;
    $found = false;
    $found = @ include_once "$classdir$class_name.class.php";
    if ($found === false) {
        echo '<h1>Failed to load class file: ' . $class_name . '</h1>';
        exit;
    }
}

// avoid php warnings with undefined variables
function check(&$var) {
    $var = isset($var) ? $var : null;
}

?>