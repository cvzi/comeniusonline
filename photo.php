<?php

/**
 * photo.php
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



if ($user->get('group') > 1) { // teacher,admin,etc...

    $file = Profile::returnProfileImageByName($_GET['who']);
    $file = $file?$file:'photos/nophoto.png';

} else if ($user->get('group') == 1) { // student
    // MySQL Connection
    $mysql = new mysql($errors, $MySQLHost, $MySQLName, $MySQLPassword, $MySQLDBName, false, false);
    unset($MySQLName);
    unset($MySQLPassword);

    $uid = $user->get('uid');

    $profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid, array('favourite_lessons', 'hobbies', 'special'));


    if ($_GET['who'] == 'partner') {  // partner photo
        $partnerid = $profile->field['partnerid'] ? (integer) $profile->field['partnerid'] : false;

        $partnerprofile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $partnerid, array('favourite_lessons', 'hobbies', 'special'));

        if ($partnerprofile->field['photo']) {


            $filesize = filesize($partnerprofile->field['photo']);
            $info = getimagesize($partnerprofile->field['photo']);

            header('Content-Length: ' . $filesize);
            header('content-type: ' . $info['mime']);

            readfile($partnerprofile->field['photo']);


            $file = $partnerprofile->field['photo'];
        } else {
            $file = 'photos/nophoto.png';
        }
    } else { // own photo
        if ($profile->field['photo']) {


            $file = $profile->field['photo'];
        } else {
            $file = 'photos/nophoto.png';
        }
    }
} else {
    $file = 'photos/error.png';
}




$filesize = filesize($file);
$info = getimagesize($file);

header('Content-Length: ' . $filesize);
header('Content-Type: ' . $info['mime']);

readfile($file);
?>