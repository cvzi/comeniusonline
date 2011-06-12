<?php

/**
 * email.php
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

// Smarty instanzieren
require_once($smartydir . 'libs/Smarty.class.php');
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

// Vars
$smarty->assign('pages', $pages);
$smarty->assign('baseurl', $navi->getBase());
ob_end_clean();


$types = array(
    'teacherwelcome' => 'mail.newteacheraccount.html',
    'lostpassword' => 'mail.lostpassword.html',
    'newnote' => 'mail.newnote.html');

if ($types[$_GET['type']]) {
    $data = json_decode(stripslashes(base64_decode($_GET['data'])));
    if ($data) {
        foreach ($data as $key => &$value) {
            $smarty->assign($key, $value);
        }
    }

    // If no html is in the template it will automatically use the text
    $smarty->assign('html', true);
    $output = $smarty->fetch($types[$_GET['type']]);

    // Headers schreiben
    if ($smarty->get_template_vars('htmlheader')) {
        header('Content-Type: text/html; charset=utf-8');
    } else {
       $smarty->assign('html', false);
       $output = $smarty->fetch($types[$_GET['type']]);
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo $output;

} else {
    header('Content-Type: text/plain; charset=utf-8');
    exit('Wrong type');
}
?>
