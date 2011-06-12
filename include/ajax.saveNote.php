<?php

/**
 * include/ajax.saveNote.php
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


$authorid = (integer) $user->get('uid');;
$subjectid = (integer) $_GET['uid'];
$text = $_GET['text'];
$position = $_GET['position'];

if($subjectid && $text && $position) {

    $notes = new Notes($MySQL_tables, $errors, $mysql);
    $noteid = $notes->addNote($authorid,$position,$text,$subjectid);

    if($noteid !== false) {
        $jsonresult = 1;


    // User has Email adress?
    $profile = new Profile($MySQL_tables, $errors, $comeniusfile, $mysql, $subjectid);

    // Send an email:
    if ($profile->field['email']) {

            $to = $profile->field['email'];
            $from = $navi->getEmail();
            $subject = 'Comenius: A teacher left a comment';
            $email = new Email($smartydir, $navi->getTemplateDir() . 'mail.newnote.html', $to, $from, $subject);

            $email->assign('username', $profile->field['name']);
            $email->assign('to', $emailaddress);
            $email->assign('from', $navi->getEmail());
            $email->assign('baseurl', $navi->getBase());
            $email->assign('notetext', htmlentities($text));

            $data = urlencode($email->getJSON64());

            $email->assign('viewonline', $navi->getBase() . 'email.php?type=newnote&data=' . $data);

            $jsonresult = $email->send()?2:1;
        }

    }

} else {
    $jsonresult = -1;
}

$json = sprintf('{"result":%u,"noteid":%d}', $jsonresult,$noteid);
?>