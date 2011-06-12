<?php

/**
 * index.php
 *
 *       comeniusonline
 *
 *  comeniusonline is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  comeniusonline is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with comeniusonline; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *  For questions contact
 *  cuzi@openmail.cc
 *
 * @copyright 2011 cuzi
 * @author cuzi <cuzi@openmail.cc>
 * @package comeniusonline
 * @version 1.06
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 */

#ini_set('display_errors', '1');
#error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE);

// Config
$found = false;
$found = @ include getcwd() . '/config.php';
// Leave getcwd() here, else we might not get an error
// as php will probably find an alien config.php in the php.ini's include_path
if ($found === false) {
    echo '<h1>Failed to load config file</h1>';
    exit; // A wrong config.php could damage this program as well as make it vulnerable to criminals
}

// Session
session_start();

// Weitere Ausgaben bzw. PHP Fehler abfangen
ob_start();

// Fehlerverarbeitung
$errors = new errors($errorsfile, 'error'); // error == standard css class
$escapeBuffer = true;

// User
$user = new user();
$user->standard('group', 0);
$user->standard('mobile', null);

// MySQL Connection
$mysql = new mysql($errors, $MySQLHost, $MySQLName, $MySQLPassword, $MySQLDBName, false, false);
unset($MySQLName);
unset($MySQLPassword);

// Smarty instanzieren
$found = false;
$found = @ include_once $smartydir . 'libs/Smarty.class.php';
if ($found === false) {
    $buffer = ob_get_contents();
    ob_end_clean();
    echo '<h1>Failed to load smarty class file</h1>';
    if ($buffer) {
        echo '<pre><h2>Info:</h2>Smarty.class.php<h2>Output:</h2>' . $buffer . '</pre>';
    }
    exit; // It's senseless to proceed, if the template system could not be inited
}


$smarty = new Smarty;
$smarty->caching = false;
$smarty->template_dir = 'templates/';
$smarty->config_dir = $smartydir . 'config/';
$smarty->cache_dir = $smartydir . 'cache/';
$smarty->compile_dir = $smartydir . 'templates_c/';


// Navi instanzieren
try {
    $navi = new navigation($errors, $navifile);
    $pages = $navi->getPages();
    $smarty->template_dir = $navi->getTemplateDir();
} catch (Exception $e) {
    $buffer = ob_get_contents();
    ob_end_clean();
    echo '<h1>Failed to load navigation file</h1>';
    if ($buffer) {
        echo '<pre><h2>Info:</h2>
' . $e->getMessage() . '

<h2>Output:</h2>' . $buffer . '</pre>';
    }
    exit;
}



// Mobile Browser?
$maybemobile = false;
$isMobileDevice = false;

if (($user->get('mobile') || isset($_GET['mobile'])) && !isset($_GET['classic'])) {
    $isMobileDevice = $navi->tryMobileDevice('', true);
} else if (isset($_GET['classic'])) {
    $isMobileDevice = $navi->tryMobileDevice('', false);
    $maybemobile = true;
} else {
    $isMobileDevice = $navi->tryMobileDevice($_SERVER['HTTP_USER_AGENT']);
    $maybemobile = $isMobileDevice;
}

if ($user->get('maybemobile', false)) {
    $maybemobile = true;
}


if ($isMobileDevice !== $user->get('mobile')) {
    $user->set('mobile', $isMobileDevice);
}
if ($maybemobile) {
    $user->set('maybemobile', true);
}

// Javascript
$jscode = '';
$jsscripts = array();

// Angeforderte Seite raussuchen
$page = $navi->getRequestedPage($_GET, $user->get('group'));
$found = false;
$found = @ include $page['path'];
if ($found === false) {
    $errors[] = '#PageNotFound#';
    $escapeBuffer = false;
}

// Headers schreiben
header('Content-Type: text/html; charset=utf-8');

// Vars
$smarty->assign('user', $user);
$smarty->assign('mobile', $isMobileDevice);
$smarty->assign('maybemobile', $maybemobile);
$smarty->assign('pages', $pages);
$smarty->assign('page', $page);
$smarty->assign('baseurl', $navi->getBase());
$smarty->assign('jscode', $jscode);
$smarty->assign('jsscripts', $jsscripts);


$smarty->assign('errors', $errors->get()); // last!
// Buffer
$buffer = ob_get_contents();
if ($buffer) {
    if ($escapeBuffer) {
        if (strpos($buffer, '<b>') === false) { // It is probably not a PHP Error
            $smarty->assign('outputbuffer', htmlspecialchars($buffer));
        } else {
            $smarty->assign('outputbuffer', $buffer);
        }
    } else {
        $smarty->assign('outputbuffer', $buffer);
    }
} else {
    $smarty->assign('outputbuffer', false);
}
ob_end_clean();


// Ausgabe
$smarty->display($navi->getFrame());
?>
