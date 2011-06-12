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
function resizeImage($filename, $type, $new_w, $new_h, $info) {

    switch ($type) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($filename);
            break;
        case 'png':
            $src_img = imagecreatefrompng($filename);
            break;
        case 'gif':
            $src_img = imagecreatefromgif($filename);
            break;
        default:
            return false;
    }

    $old_w = $info[0];
    $old_h = $info[1];



    if ($old_w > $new_w) {
        $thumb_w = $new_w;
        $thumb_h = (integer) ( $new_w / $old_w * $old_h );
    } else {
        $thumb_h = $new_h;
        $thumb_w = (integer) ( $new_h / $old_h * $old_w );
    }

    $dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_w, $old_h);
    imagedestroy($src_img);
    switch ($type) {
        case 'jpg':
            imagejpeg($dst_img, $filename);
            break;
        case 'png':
            imagepng($dst_img, $filename);
            break;
        case 'gif':
            imagegif($dst_img, $filename);
            break;
    }

    imagedestroy($dst_img);
    return true;
}

// Template auswählen
$smarty->assign('moduleTpl', 'myprofile.html');

$uid = $user->get('uid');


$profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $uid, array('favourite_lessons', 'hobbies', 'special'));


$countries = $profile->getList('countries', array('integer intdialingcode'));
$schools = $profile->getList('schools');
$lessons = $profile->getList('lessons');
$hobbies = $profile->getList('hobbies');
$special = $profile->getList('special');

// Check status

if ($profile->field['status'] == 2) {

    if ($print === true) {
        $smarty->assign('moduleTpl', 'print.profile.html');
    } else {
        $smarty->assign('moduleTpl', 'profile.html');
    }

    $smarty->assign('unchangeable', 1);

    $smarty->assign('photo', 'my');

    if ($_POST['email']) {

        $email = $_POST['email'];
        $profile->db_changeProfileField($uid, 'email', $email);
        $result = $profile->db_commit();
        if ($result) {
            $errors[] = array('class' => 'hint', 'text' => '#EmailSaved#');
        } else {
            $errors[] = '#EmailSaveFailed#';
        }
    }
} else {

    if ($_POST) {

        $fields = array();

        $fields['surname'] = $_POST['surname'];
        $fields['firstname'] = $_POST['firstname'];
        $fields['sex'] = $_POST['sex'] == '1' ? 1 : 2;
        $fields['address_street'] = $_POST['address_street'];
        $fields['address_postcode'] = $_POST['address_postcode'];
        $fields['address_town'] = $_POST['address_town'];
        $fields['adress_country'] = (integer) $_POST['adress_country'];
        $fields['homeschool'] = (integer) $_POST['homeschool'];
        $fields['destschool'] = (integer) $_POST['destschool'];
        $fields['destcountry'] = (integer) $_POST['destcountry'];
        $fields['phone_number'] = $_POST['phone_number'];
        $fields['phone_number_mobile'] = $_POST['phone_number_mobile'];
        $fields['email'] = $_POST['email'];
        $fields['aboutmyfamily'] = $_POST['aboutmyfamily'];
        $fields['hobbies_text'] = $_POST['hobbies_text'];
        $fields['surname'] = $_POST['surname'];

        if (trim($_POST['birth']) == '' || strtotime($_POST['birth']) <= 0) {
            $fields['birth'] = 'NULL';
        } else {
            $fields['birth'] = date('y-m-d', strtotime($_POST['birth']));
        }



        foreach ($fields as $name => $value) {
            if (trim($value) != '') {
                $profile->db_changeProfileField($uid, $name, $value);
            }
        }


        $name = 'favourite_lessons';
        if ($_POST[$name . '_int']) {
            for ($i = 0, $len = count($_POST[$name . '_int']); $i < $len; $i++) {
                $int = (integer) $_POST[$name . '_int'][$i];
                $text = $_POST[$name . '_text' . $i];
                if ($int >= 0 && $int <= count($lessons)) {
                    $profile->db_changeSubjectField($name, $uid, $i + 1, $int, $text);
                }
            }
        }

        $name = 'hobbies';
        if ($_POST[$name . '_int']) {
            for ($i = 0, $len = count($_POST[$name . '_int']); $i < $len; $i++) {
                $int = (integer) $_POST[$name . '_int'][$i];
                $text = $_POST[$name . '_text' . $i];
                if ($int >= 0 && $int <= count($hobbies)) {
                    $profile->db_changeSubjectField($name, $uid, $i + 1, $int, $text);
                }
            }
        }

        $name = 'special';
        for ($i = 0, $len = count($special); $i < $len; $i++) {
            if ($_POST[$name . '_text' . $i]) {
                $text = $_POST[$name . '_text' . $i];
                $profile->db_changeSubjectField($name, $uid, $i + 1, $i, $text);
            }
        }



        $result = $profile->db_commit();
        if ($result) {
            $errors[] = array('class' => 'hint', 'text' => '#ProfileSaved#');
        } else {
            $errors[] = '#ProfileSaveFailed#';
        }

        if ($_FILES['profileimage'] && $_FILES['profileimage']['size']) {
            $tmp = $_FILES['profileimage']['tmp_name'];

            $info = getimagesize($tmp);
            /*
             * [0]=> int(1024)
             * [1]=> int(768)
             * [2]=> int(2)
             * [3]=> string(25) "width="1024" height="768""
             * ["bits"]=> int(8)
             * ["channels"]=> int(3)
             * ["mime"]=> string(10) "image/jpeg"
             *
             */
            if ($info) {
                switch ($info['mime']) {
                    case 'image/jpeg':
                        $ext = 'jpg';
                        break;
                    case 'image/png':
                        $ext = 'png';
                        break;
                    case 'image/gif':
                        $ext = 'gif';
                        break;
                    default:
                        $ext = false;
                }
                if ($ext) {
                    $newfile = 'photos/' . $user->get('name') . '.' . $ext;

                    // Delete old photos
                    @unlink('photos/' . $user->get('name') . '.png');
                    @unlink('photos/' . $user->get('name') . '.gif');
                    @unlink('photos/' . $user->get('name') . '.jpg');

                    if (move_uploaded_file($tmp, $newfile)) {
                        $errors[] = array('class' => 'hint', 'text' => '#ProfileImageSaved#');
                        if ($info[0] > 1100 || $info[0] > 1100) {
                            resizeImage($newfile, $ext, 1000, 1000, $info);
                        }
                    } else {
                        $errors[] = '#ProfileImageUnexpectedError#';
                    }
                } else {
                    $errors[] = '#ProfileImageFormat#';
                }
            } else {
                $errors[] = '#ProfileImageFormat#';
            }
        }

        if ($_POST['handitin']) {
            $result = $profile->setStatus(1);
            if ($result) {
                $errors[] = array('class' => 'hint', 'text' => '#ProfileHandedIn#');
            } else {
                $errors[] = '#ProfileHandInFailed#';
            }
        }
    }

    $profile->update();

    if ($profile->field['status'] == 1 && !$_POST['handitin']) {
        $errors[] = array('class' => 'hint nofadeout', 'text' => '#ProfileHandedIn#');
    }
}


// Notes
$notes = new Notes($MySQL_tables, $errors, $mysql, $uid);
$notes_json = json_encode($notes->notes);
$smarty->assign('notes_json', $notes_json);


$smarty->assign('myprofile', $profile);
$smarty->assign('countries', $countries);
$smarty->assign('schools', $schools);
$smarty->assign('lessons', $lessons);
$smarty->assign('hobbies', $hobbies);
$smarty->assign('special', $special);
?>