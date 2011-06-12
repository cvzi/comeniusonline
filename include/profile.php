<?php

/**
 * include/myprofile.php
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

if ($print === true) {
    $smarty->assign('moduleTpl', 'print.profile.html');
} else {
    $smarty->assign('moduleTpl', 'profile.html');
}


$uid = $user->get('uid');
if($user->get('group') == 1) {
    $profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid);
} else {
    $profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql); // Important, because teachers/admin do not have profile
}

$partnerid = $profile->field['partnerid'] ? (integer) $profile->field['partnerid'] : false;

$smarty->assign('photo', false);

if ($user->get('group') == 1 && isset($_GET['my'])) { // Show own profile
    $partnerprofile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid, array('favourite_lessons', 'hobbies', 'special'));

    $countries = $profile->getList('countries', array('integer intdialingcode'));
    $schools = $profile->getList('schools');
    $lessons = $profile->getList('lessons');
    $hobbies = $profile->getList('hobbies');
    $special = $profile->getList('special');

    $smarty->assign('myprofile', $partnerprofile);
    $smarty->assign('countries', $countries);
    $smarty->assign('schools', $schools);
    $smarty->assign('lessons', $lessons);
    $smarty->assign('hobbies', $hobbies);
    $smarty->assign('special', $special);

    $smarty->assign('photo', 'my');
} else if ($user->get('group') == 1 && $partnerid) { // Show partner's profile
    $partnerprofile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $partnerid, array('favourite_lessons', 'hobbies', 'special'));

    if ($partnerprofile->field['status'] == 2) {
        $countries = $profile->getList('countries', array('integer intdialingcode'));
        $schools = $profile->getList('schools');
        $lessons = $profile->getList('lessons');
        $hobbies = $profile->getList('hobbies');
        $special = $profile->getList('special');

        $smarty->assign('myprofile', $partnerprofile);
        $smarty->assign('countries', $countries);
        $smarty->assign('schools', $schools);
        $smarty->assign('lessons', $lessons);
        $smarty->assign('hobbies', $hobbies);
        $smarty->assign('special', $special);

        $smarty->assign('photo', 'partner');
    } else {
        $errors[] = '#ProfilePartnerNotApproved#';
    }
} else if ($user->get('group') == 1) {
    $errors[] = '#ProfilePartnerNotFound#';
} else if ($user->get('group') > 1 && (integer) $_GET['uid']) { // Show specific user profile; (only teacher/admin)
    $uid = (integer) $_GET['uid'];

    $partnerprofile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid, array('favourite_lessons', 'hobbies', 'special'));

    $countries = $profile->getList('countries', array('integer intdialingcode'));
    $schools = $profile->getList('schools');
    $lessons = $profile->getList('lessons');
    $hobbies = $profile->getList('hobbies');
    $special = $profile->getList('special');

    $smarty->assign('myprofile', $partnerprofile);
    $smarty->assign('countries', $countries);
    $smarty->assign('schools', $schools);
    $smarty->assign('lessons', $lessons);
    $smarty->assign('hobbies', $hobbies);
    $smarty->assign('special', $special);

    // Notes
    $notes = new Notes($MySQL_tables, $errors, $mysql, $uid);
    $notes_json = json_encode($notes->notes);
    $smarty->assign('notes_json', $notes_json);

    // Form
    $allusers = $profile->getAllUsers(1);
    $smarty->assign('allusers', $allusers);
} else if ($user->get('group') > 1 && !$_GET['uid']) { // Show form to select a user; (only teacher/admin)
    // choose user
    $allusers = $profile->getAllUsers(1);
    $smarty->assign('allusers', $allusers);
} else {
    $errors[] = '#ProfileNotFound#';
}
?>