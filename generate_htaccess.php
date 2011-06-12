<?php

/**
 * generate_htaccess.php
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

// Fehlerverarbeitung
$errors = new errors($errorsfile,'error'); // error == standard css class


// Navi instanzieren
$navi = new navigation($errors, $navifile);
$pages = $navi->getPages();
$key = $navi->getHtaccess();


// Headers schreiben
header('Content-Type: text/plain; charset=utf-8');

$handler = fopen('.htaccess', 'w+');

if(!$handler)
    exit('Am error occured while trying to open/create the file .htaccess');


fwrite($handler,"RewriteEngine On\n#generated with generate_htaccess.php using $navifile\n\n");



foreach($pages as $page) {
    if($page['param'] == $key) {
      fwrite($handler, 'RewriteRule ^'.$page['name'].'$ ?'.$page['param'].'='.$page['name']." [qsappend]\n".'RewriteRule ^'.$page['name'].'/$ ?'.$page['param'].'='.$page['name']." [qsappend]\n\n");
    }
}

fwrite($handler,"\n#Vulnerable Files and Dirs\n\n");

$public = $navi->getVulnerableFiles();
foreach($public as $file) {
    if('dir' == $file[0]) {
fwrite($handler,"RewriteRule ^{$file[1]}.* index.php\n");
    } else {
        fwrite($handler,"RewriteRule ^{$file[1]}$ index.php\n");
    }
}


fwrite($handler,'#generated with generate_htaccess.php');

fclose($handler);

echo "Successfully created: .htaccess\nWith following content:\n###########################\n\n";

readfile('.htaccess');

?>