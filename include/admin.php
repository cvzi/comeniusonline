<?php

/**
 * include/admin.php
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
$smarty->assign('moduleTpl', 'admin.html');
$profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql);

if ('optimize' == $_GET['do']) {
    $result = 0;

    $result += $profile->optimizeTable();

    $notes = new Notes($MySQL_tables, $errors, $mysql);
    $result += $notes->optimizeTable();


    if ($result == 2) {
        $errors[] = array('text' => 'All tables werre optimized!', 'class' => 'hint');
    } else {
        $errors[] = 'Unexpected error! Profile::optimizeTable() and/or Notes::optimizeTable() returned false';
    }

    $smarty->assign('redirect', $pages['admin']['link']);
} else if ('createTeacher' == $_GET['do']) {

    $username = $_POST['username'];
    $plainpassword = $_POST['password'];
    $password = hash('sha512', $_POST['password']);
    $emailaddress = $_POST['emailaddress'] ? $_POST['emailaddress'] : 'NULL';
    $welcomemail = $_POST['welcomemail'] ? true : false;

    $profile->db_addUser2(2, $username, $password, $emailaddress);

    $result = $profile->db_commit();

    if ($result) {
        $errors[] = array('class' => 'hint', 'text' => '#NewTeacherWasAdded#');
        if ($welcomemail) {

            $to = $emailaddress;
            $from = $navi->getEmail();
            $subject = 'Comenius: Online account notification';
            $email = new Email($smartydir, $navi->getTemplateDir() . 'mail.newteacheraccount.html', $to, $from, $subject);

            $email->assign('username', $username);
            $email->assign('password', $plainpassword);
            $email->assign('to', $emailaddress);
            $email->assign('from', $navi->getEmail());
            $email->assign('baseurl', $navi->getBase());

            $data = urlencode($email->getJSON64());

            $email->assign('viewonline', $navi->getBase() . 'email.php?type=teacherwelcome&data=' . $data);

            $result = $email->send();
            if (!$result) {
                $errors[] = '#SendEmailError#';
            }
        }
    } else {
        $errors[] = '#NewTeacherFailed#';
    }
} else if ('deleteTeacher' == $_GET['do']) {
    $uid = $_GET['uid'];

    $result = $profile->deleteUser($uid);

    if ($result) {
        $errors[] = array('class' => 'hint', 'text' => '#TeacherWasDeleted#');
    } else {
        $errors[] = '#TeacherDeleteFailed#';
    }
    $smarty->assign('redirect', $pages['admin']['link']);
} else if ('reset' == $_GET['do']) {
    if ($_GET['confirm']) {
        $errornumber = 0;


        // Maximum group
        $maxgroup = 5; // Admins
        if($_GET['withoutteachers']) {
          $maxgroup = 2;  // Teachers
        }

        // Delete photos
        $dirname = 'photos';
        $dir_handle = opendir($dirname);
        while (false !== ($file = readdir($dir_handle))) {
            if ($file != "." && $file != ".." && $file != "error.png" && $file != "nophoto.png") {
                unlink($dirname . "/" . $file);
            }
        }
        closedir($dir_handle);

        // Database

        $sql = 'TRUNCATE TABLE `' . $MySQL_tables['profile'] . '_favourite_lessons`';
        $errornumber += $mysql->execute($sql)?0:1;
        $sql = 'TRUNCATE TABLE `' . $MySQL_tables['profile'] . '_hobbies`';
        $errornumber += $mysql->execute($sql)?0:1;
        $sql = 'TRUNCATE TABLE `' . $MySQL_tables['profile'] . '_special`';
        $errornumber += $mysql->execute($sql)?0:1;
        $sql = 'TRUNCATE TABLE `' . $MySQL_tables['notes'] . '`';
        $errornumber += $mysql->execute($sql)?0:1;
        $sql = 'DELETE FROM `' . $MySQL_tables['user'] . '` WHERE `group` < '.$maxgroup;
        $errornumber += $mysql->execute($sql)?0:1;

        $sql = 'SELECT `id` FROM `' . $MySQL_tables['user'] . '`';
        $result = $mysql->select($sql, 'assocList');
        $ids = array();
        foreach ($result as $row) {
            $ids[] = $row['id'];
        }
        $ids = ' `uid` != ' . implode(' AND `uid` != ', $ids).' ';

        $sql = 'DELETE FROM `' . $MySQL_tables['profile'] . '` WHERE ' . $ids;
        $errornumber += $mysql->execute($sql)?0:1;

        if ($errornumber == 0) {
            $errors[] = array('class' => 'hint', 'text' => '#DatabaseReset#');
            $smarty->assign('redirect', $pages['admin']['link']);
        } else {
            $errors[] = '#DatabaseResetFailed#';
        }
    } else {
        $smarty->assign('confirm_reset', true);
    }
}




$allteachers = $profile->getGroup(2);
$smarty->assign('allteachers', $allteachers);

//Config XMLs
$smarty->assign('navigationXML_content', file_get_contents($navifile));
$smarty->assign('navigationXML_name', $navifile);

$smarty->assign('comeniusXML_content', file_get_contents($comeniusfile));
$smarty->assign('comeniusXML_name', $comeniusfile);

$smarty->assign('errorsXML_content', file_get_contents($errorsfile));
$smarty->assign('errorsXML_name', $errorsfile);
?>