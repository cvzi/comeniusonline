<?php

/**
 * include/ajax.connectPartners.php
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

$profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql);



list($id0, $id1) = explode(',', $_GET['ids']);

$id0 = (integer) $id0;
$id1 = (integer) $id1;

if ($id0 == $id1) { // Same id is impossible    
    $jsonresult = 0;
} else if ($id0 && !$id1) { // Remove partner with $id0
    $profile->db_removePartner($id0);
    $result = $profile->db_commit();
    if ($result) {
        $jsonresult = 1;
    } else {
        $jsonresult = -1;
    }
} else if ($id0 && $id1) { // Connect partners
    $profile->db_removePartner($id0);
    $profile->db_removePartner($id1);

    $profile->db_setPartner($id0, $id1);

    $profile->db_setPartner($id1, $id0);

    $result = $profile->db_commit();

    if ($result) {
        $jsonresult = 2;
    } else {
        $jsonresult = -2;
    }
} else { // Wrong data
    $jsonresult = 0;
}

$json = sprintf('{"result":%d}', $jsonresult);
?>