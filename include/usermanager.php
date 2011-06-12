<?php

/**
 * include/usermanager.php
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
// Template auswählen
$smarty->assign('moduleTpl', 'usermanager.html');

// Profile
$profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql);

// Schools
$schools = $profile->getList('schools');


// Handle Form Actions
// Add students/users
if (isset($_POST['addstudents'])) {

    // School valid?
    $homeschool_id = (integer) $_POST['home_school'];
    if ($homeschool_id == 0) {
        $valid = true;
    } else {
        $valid = false;
        foreach ($schools as &$school) {
            if ($school['id'] == $homeschool_id) {
                $valid = true;
            }
        }
    }
    if ($valid) {

        $dataformat = $_POST['data_format'] == 'surname_firstname' ? 'surname_firstname' : 'firstname_surname';

        $rawdata = $_POST['data'];
        $lines = explode("\n", $rawdata);

        foreach ($lines as &$line) {
            $parts = explode(',', $line);
            if ($dataformat == 'surname_firstname') {
                $profile->db_addUser(1, trim($parts[1]), trim($parts[0]), $homeschool_id);
            } else {
                $profile->db_addUser(1, trim($parts[0]), trim($parts[1]), $homeschool_id);
            }
        }
        $result = $profile->db_commit();


        if ($result) {
            $errors[] = array('class' => 'hint', 'text' => '#NewUsersWereAdded#');
            $smarty->assign('redirect', $pages['usermanager']['link']);
        } else {
            $errors[] = '#NewUsersFailed#';
        }
    } else {
        $errors[] = '#NewUsersWrongSchool#';
    }
} else if ('show_passwords' == $_GET['do']) {

    $sql = 'SELECT `name`,`plaintextpassword`,`surname`,`firstname` FROM `'.$MySQL_tables['user'].'` LEFT JOIN `'.$MySQL_tables['profile'].'` ON `'.$MySQL_tables['profile'].'`.`uid` = `id` WHERE `group` < 3 ORDER BY `surname`,`firstname`';
    $result = $mysql->select($sql, 'assocList');
    $smarty->assign('userpasswords', $result);

    $shortindex = $navi->getBase();
    $smarty->assign('shortindex', $shortindex);
} else if ('approve' == $_GET['do']) {

    $uid = (integer) $_GET['uid'];

    $myprofile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid);
    $result = $myprofile->setStatus(2);
    if ($result) {
        $errors[] = array('class' => 'hint', 'text' => '#ProfileApproved#');
    } else {
        $errors[] = '#ProfileApproveFailed#';
    }
} else if ('deactivate' == $_GET['do']) {

    $uid = (integer) $_GET['uid'];

    $myprofile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid);
    $result = $myprofile->setStatus(0);
    if ($result) {
        $errors[] = array('class' => 'hint', 'text' => '#ProfileDeactivated#');
    } else {
        $errors[] = '#ProfileDeactivateFailed#';
    }
}





// All students/users
$allusers = $profile->getAllUsers(1);


// Output
$allusers_json = json_encode($allusers);
$schools_json = json_encode($schools);

$smarty->assign('allusers_json', $allusers_json);
$smarty->assign('schools_json', $schools_json);
$smarty->assign('schools', $schools);
?>