<?php

/**
 * include/ajax.changePassword.php
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
$jsonresult = 0;

$id = (integer) $_GET['uid'];

$password = $_GET['password'];


$plaintext = $_GET['plaintext']?true:false;


$profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql);

$result = $profile->changePassword($id,$password,$plaintext);

if ($result) {
    $jsonresult = 1;
} else {
    $jsonresult = -1;
}


$json = sprintf('{"result":%d}', $jsonresult);
?>