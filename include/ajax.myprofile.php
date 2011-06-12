<?php

/**
 * include/ajax.myprofile.php
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

$uid = $user->get('uid');


$fields = array(
    'surname' => 'string',
    'firstname' => 'string',
    'sex' => 'int',
    'address_street' => 'string',
    'address_postcode' => 'string',
    'address_town' => 'string',
    'adress_country' => 'int',
    'homeschool' => 'int',
    'destschool' => 'int',
    'phone_number' => 'string',
    'phone_number_mobile' => 'string',
    'email' => 'string',
    'birth' => 'string',
    'aboutmyfamily' => 'string',
    'hobbies_text' => 'string',
    'favourite_lessons' => 'array',
    'hobbies' => 'array',
    'special' => 'array'
);


$fieldname = $_GET['fieldname'];
$fieldvalue = $_GET['fieldvalue'];
$fieldtype = $_GET['fieldtype'] == 'text' ? 'text' : 'int';
$accepted = false;
$rank = 0;

if ($fieldname == 'birth') {
    if (trim($fieldvalue) == '' || strtotime($fieldvalue) <= 0) {
        $fieldvalue = 'NULL';
    } else {
        $fieldvalue = date('y-m-d', strtotime($fieldvalue));
    }
}

if ($fields[$fieldname] == 'string') {
    $accepted = true;
} else if ($fields[$fieldname] == 'int') {
    $fieldvalue = (integer) $fieldvalue;
    $accepted = true;
} else {
    $parts = explode('[', $fieldname);
    if ($parts[1]) {
        $fieldname = $parts[0];
        if ($fields[$fieldname] == 'array') {
            $rank = explode(']', $parts[1]);
            $rank = (integer) $rank[0];
            $accepted = true;
        }
    }
}

if ($accepted) {

    $profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid, array('favourite_lessons', 'hobbies', 'special'));

    if ($fields[$fieldname] == 'array') {
        if ($fieldtype == 'int') {
            $profile->db_changeSubjectField($fieldname, $uid, $rank + 1, $fieldvalue, $profile->field[$fieldname][$rank + 1]['text']);
        } else {
            $profile->db_changeSubjectField($fieldname, $uid, $rank + 1, $profile->field[$fieldname][$rank + 1]['subject'], $fieldvalue);
        }

        $result = $profile->db_commit();
        if ($result) {
            $jsonresult = 1;
        } else {
            $jsonresult = -1;
        }
    } else {
        $profile->db_changeProfileField($uid, $fieldname, $fieldvalue);

        $result = $profile->db_commit();
        if ($result) {
            $jsonresult = 1;
        } else {
            $jsonresult = -1;
        }
    }
} else {
    $jsonresult = 0;
}










$json = sprintf('{"result":%d}', $jsonresult);
?>