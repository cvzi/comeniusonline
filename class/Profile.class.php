<?php

/**
 * class/Profile.class.php
 *
 *       comeniusonline
 *
 *  UTF-8 encoded
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
 * @copyright Copyright (c) 2011, cuzi
 * @author cuzi <cuzi@openmail.cc>
 * @package comeniusonline
 * @version 1.06
 * @license http://gnu.org/copyleft/gpl.html GNU GPL
 *
 */
function compare_assoc_id($a, $b) {
    if ($a['id'] == $b['id']) {
        return 0;
    }

    return $a['id'] > $b['id'] ? 1 : -1;
}

function strToLowerASCII($string) {
    $convert_to = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
        "v", "w", "x", "y", "z", "a", "a", "a", "a", "a", "a", "ae", "c", "e", "e", "e", "e", "i", "i", "i", "i",
        "d", "n", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "a", "b", "b", "t", "a", "е", "e", "x",
        "e", "n", "n", "k", "n", "m", "h", "o", "h", "p", "c", "t", "y", "o", "x", "u", "j", "m", "m", "b", "b",
        "b", "e", "io", "r"
    );
    $convert_from = array(
        "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
        "V", "W", "X", "Y", "Z", "À", "Á", "Â", "Ã", "Ä", "Å", "Æ", "Ç", "È", "É", "Ê", "Ë", "Ì", "Í", "Î", "Ï",
        "Ð", "Ñ", "Ò", "Ó", "Ô", "Õ", "Ö", "Ø", "Ù", "Ú", "Û", "Ü", "Ý", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж",
        "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ъ",
        "Ь", "Э", "Ю", "Я"
    );

    $str = strToLowerASCII_fromLower(str_replace($convert_from, $convert_to, $string));

    $str = iconv("ascii", "UTF-8//IGNORE", iconv("UTF-8", "ascii//TRANSLIT//IGNORE", $str));

    $str = str_replace("'",'',$str);

    return $str;
}

function strToLowerASCII_fromLower($string) {
    $convert_to = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
        "v", "w", "x", "y", "z", "a", "a", "a", "a", "a", "a", "ae", "c", "e", "e", "e", "e", "i", "i", "i", "i",
        "d", "n", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "y", "a", "b", "b", "t", "a", "е", "e", "x",
        "e", "n", "n", "k", "n", "m", "h", "o", "h", "p", "c", "t", "y", "o", "x", "u", "j", "m", "m", "b", "b",
        "b", "e", "io", "r"
    );
    $convert_from = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
        "v", "w", "x", "y", "z", "à", "á", "â", "ã", "ä", "å", "æ", "ç", "è", "é", "ê", "ë", "ì", "í", "î", "ï",
        "ð", "ñ", "ò", "ó", "ô", "õ", "ö", "ø", "ù", "ú", "û", "ü", "ý", "а", "б", "в", "г", "д", "е", "ё", "ж",
        "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы",
        "ь", "э", "ю", "я"
    );

    return str_replace($convert_from, $convert_to, $string);
}

function randomStr($min, $max, $chars=false) {
    if (!$chars) {
        $chars = "23456789abcdefghkmnpqrstuvwxyz"; // Zeichen die im String vorkommen dürfen
    }
    $stringlength = rand($min, $max);
    $i = 0;
    $result = "";
    while ($i < $stringlength) {
        $result .= $chars[rand(0, strlen($chars) - 1)];
        $i++;
    }
    return $result;
}

/**
 * Description of Profile
 *
 * @author cuzi
 */
class Profile {

    private $xml;
    private $mysql;
    public $errors;
    public $mysqltables;
    public $filename;
    public $field = array();
    public $uid = false;
    public $tables = array();
    public $addstudents = array();
    public $setPartner = array();
    public $removePartner = array();
    public $profileField = array();
    public $subjectField = array();

    function __construct($tables, &$errors, $file, &$mysql, $id=false, $additionaltables=false) {
        $this->mysqltables = $tables;
        $this->filename = $file;
        $this->errors = &$errors;
        $this->mysql = &$mysql;


        if ($errors && (!$file || !$mysql)) {
            $this->errors[] = 'Unexpected Parameter in Profile::__construct(&$errors, $file, &$mysql)';
            return;
        }


        $this->xml = new SimpleXMLElement($file, null, true);
        $syntax = $this->checkIntegrity();
        if (!$syntax) {
            $this->xml = false;
        }

        if ($additionaltables) {
            $this->tables = $additionaltables;
        }


        if ($id) {
            $this->getProfileById($id);
            $this->uid = $id;
        }
    }

    function getProfileById($id) {

        $sql = dsprintf('SELECT * FROM `%(usertable)s` LEFT JOIN `%(profiletable)s` ON `%(usertable)s`.`id` = `%(profiletable)s`.`uid` WHERE `%(usertable)s`.`id` = %(uid)u', array(
                    'uid' => $id,
                    'usertable' => $this->mysqltables['user'],
                    'profiletable' => $this->mysqltables['profile']
                ));

        $result = $this->mysql->select($sql, 'assoc');

        if ($result) {
            $this->field = $result;
            $this->field['birth'] = $this->field['birth'] ? strtotime($this->field['birth']) : false;
        } else {
            $this->errors[] = 'Could not find user (wrong user $id ?!) in Profile::getProfileById($id)';
        }

        $this->getProfileImage($id);


        if ($this->tables) {
            foreach ($this->tables as $tablename) {

                $sql = dsprintf('SELECT `rank`,`subject`,`text` FROM `%(profiletable)s_%(tablename)s` WHERE `uid` = %(uid)u', array(
                            'uid' => $id,
                            'tablename' => $tablename,
                            'profiletable' => $this->mysqltables['profile']
                        ));

                $result = $this->mysql->select($sql, 'assocList');
                $this->field[$tablename] = array();
                if ($result) {
                    foreach ($result as &$row) {
                        $this->field[$tablename][$row['rank']] = $row;
                    }
                }
            }
        }

        return $this;
    }

    function setStatus($i) {
        if (isset($this->field['id'])) {

            $sql = dsprintf('UPDATE `%(usertable)s` SET `status` = %(status)d WHERE `id` = %(id)u', array(
                        'status' => (integer) $i,
                        'id' => (integer) $this->field['id'],
                        'usertable' => $this->mysqltables['user']
                    ));


            $result = $this->mysql->execute($sql);
            if ($result) {
                return true;
            }

            $this->errors[] = 'Unexpected Database Error in Profile::setStatus($i)';
            return false;
        } else {
            $this->errors[] = 'Could not find user. Run Profile::getProfileById($id) first';
            return false;
        }
    }

    function returnProfileImageByName($name) {

        if (file_exists('photos/' . $name . '.png')) {
            return 'photos/' . $name . '.png';
        } else if (file_exists('photos/' . $name . '.gif')) {
            return 'photos/' . $name . '.gif';
        } else if (file_exists('photos/' . $name . '.jpg')) {
            return 'photos/' . $name . '.jpg';
        }
        return false;
    }

    function getProfileImage($id) {

        if ($this->field['name']) {
            if (file_exists('photos/' . $this->field['name'] . '.png')) {
                $this->field['photo'] = 'photos/' . $this->field['name'] . '.png';
            } else if (file_exists('photos/' . $this->field['name'] . '.gif')) {
                $this->field['photo'] = 'photos/' . $this->field['name'] . '.gif';
            } else if (file_exists('photos/' . $this->field['name'] . '.jpg')) {
                $this->field['photo'] = 'photos/' . $this->field['name'] . '.jpg';
            } else {
                $this->field['photo'] = false;
            }
        } else {
            $this->errors[] = 'Could not find profile, please run Profile::getProfileById($id) before Profile::getProfileImage($id)';
        }

        return $this;
    }

    function update() {
        if ($this->uid) {
            return $this->getProfileById($this->uid);
        }
    }

    function getAllUsers($max_group) {

        $sql = dsprintf('SELECT `id`,`name`,`group`,`lastlogin`,`partnerid`,`status`, `surname` , `firstname`, `homeschool`,`destschool` FROM `%(usertable)s` LEFT JOIN `%(profiletable)s` ON `%(profiletable)s`.`uid` = `%(usertable)s`.`id` WHERE `group` <= %(max)u ORDER BY `surname` , `firstname`', array(
                    'max' => (integer) $max_group,
                    'usertable' => $this->mysqltables['user'],
                    'profiletable' => $this->mysqltables['profile']
                ));

        $result = $this->mysql->select($sql, 'assocList');

        return $result;
    }

    function getGroup($group) {

        $sql = dsprintf('SELECT * FROM `%(usertable)s` LEFT JOIN `%(profiletable)s` ON `%(profiletable)s`.`uid` = `%(usertable)s`.`id` WHERE `group` = %(group)u ORDER BY `surname` , `firstname`', array(
                    'group' => (integer) $group,
                    'usertable' => $this->mysqltables['user'],
                    'profiletable' => $this->mysqltables['profile']
                ));

        $result = $this->mysql->select($sql, 'assocList');

        return $result;
    }

    /*
     * Fetches data from comenius.xml
     * $name                String      Name of the holder element, that contains the subject elements
     * $additional_attr     Array       Strings with additional attributes (id will be fetched automatically). Format:  array('Type1 attributename1','Type2 attributename2'); The Type is parsed with native settype()
     * getList($name)
     */

    function getList($name, $additional_attr=array()) {
        $list = array();
        foreach ($this->xml->{$name}->subject as $country) {
            $attr = $country->attributes();

            $id = (Integer) $attr['id'];
            $title = (String) $country;

            $list[$id] = array('title' => $title, 'id' => $id);

            if ($additional_attr) {
                foreach ($additional_attr as &$value) {
                    $parts = explode(' ', $value);
                    $a = (String) $attr[$parts[1]];
                    settype($a, $parts[0]);
                    $list[$id][$parts[1]] = $a;
                }
            }
        }
        return $list;
    }

    /*
     * Adds a user to the database.
     * Does not commit/save changes.
     */

    function db_addUser($group, $firstname, $surname, $homeschoolid=false) {
        $group = (integer) $group;
        $firstname = $this->mysql->escape($firstname);
        $surname = $this->mysql->escape($surname);
        $homeschoolid = (integer) $homeschoolid;

        $this->addstudents[] = array($group, $firstname, $surname, $homeschoolid);
        return true;
    }

    function db_addUser2($group, $loginname, $passwordhash, $email) {
        $group = (integer) $group;
        $loginname = $this->mysql->escape($loginname);
        $email = $this->mysql->escape($email);
        $passwordhash = $this->mysql->escape($passwordhash);

        $this->addstudents[] = array($group, $loginname, $email, $passwordhash, 'addUser2');
        return true;
    }

    /*
     * Changes the partnerid of a user
     * Remember to change both users!!!
     */

    function db_setPartner($userid, $partnerid=0) {
        $userid = (integer) $userid;
        $partnerid = (integer) $partnerid;

        $this->setPartner[] = array($userid, $partnerid);
        return true;
    }

    /*
     * Removes the partnerid from the user and from the partner
     * Remember to change both users!!!
     */

    function db_removePartner($partnerid) {
        $partnerid = (integer) $partnerid;

        $this->removePartner[] = $partnerid;
        return true;
    }

    /*
     * Changes a field of the user profile
     */

    function db_changeProfileField($userid, $fieldname, $newvalue) {

        if ($this->profileField[$userid]) {
            $this->profileField[$userid][] = array($fieldname, $newvalue);
        } else {
            $this->profileField[$userid] = array();
            $this->profileField[$userid][] = array($fieldname, $newvalue);
        }
        return true;
    }

    /*
     * Changes a profile field in a subject table
     */

    function db_changeSubjectField($tablename, $userid, $rank, $subject, $text) {
        $this->subjectField[] = array($this->mysqltables['profile'] . '_' . $tablename, $userid, $rank, $subject, $text);
        return true;
    }

    /*
     * Commit/save DBchanges.
     */

    function db_commit() {

        // addUser

        if ($this->addstudents) {
            // Get a list of all loginnames
            $allusers = $this->getAllUsers(100);

            $usersql = array();
            $profilesql = array();

            foreach ($this->addstudents as &$student) {


                if ($student[4] == 'addUser2') {
                    list($group, $loginname, $email, $password_hash, $tmp) = $student;

                    if (!$this->db_loginname_valid($allusers, $loginname)) {

                        $this->errors[] = 'The username ' . $loginname . ' is already in use (error occurred in Profile::db_commit())';
                        return false;
                    }

                    $allusers[] = array('name' => $loginname); // Save new loginname (for db_loginname_valid)

                    $usersql[] = dsprintf('("%(loginname)s", NULL, "%(passwordhash)s", %(group)u, NULL, NULL, 0)', array(
                                'loginname' => $loginname,
                                'passwordhash' => $password_hash,
                                'group' => $group
                            ));

                    $profilesql[] = dsprintf('("###", NULL, NULL, NULL, "%(email)s")', array(
                                'email' => $email
                            ));
                } else {

                    list($group, $firstname, $surname, $homeschoolid) = $student;

                    // Find random password
                    $password = randomStr(4, 7);
                    $password_hash = hash('sha512', $password);

                    // Find unique loginname by appending firstname to lowercase surname
                    // Surname is shortened to 2 cases. If the loginname already exists, the surname will be extended to more cases or appended with numbers

                    $i = 2;
                    $appendNumber = 2;
                    $len = strlen($firstname);
                    $loginname = strToLowerASCII($surname) . strToLowerASCII(substr($firstname, 0, $i));


                    while (!$this->db_loginname_valid($allusers, $loginname)) {
                        $i++;
                        if ($i <= $len) {
                            $loginname = strToLowerASCII($surname) . strToLowerASCII(substr($firstname, 0, $i));
                        } else {
                            $loginname = strToLowerASCII($surname) . strToLowerASCII($firstname) . $appendNumber;
                            $appendNumber++;
                        }
                    }



                    $allusers[] = array('name' => $loginname); // Save new loginname (for db_loginname_valid)

                    $usersql[] = dsprintf('("%(loginname)s", "%(plaintextpassword)s", "%(passwordhash)s", %(group)u, NULL, NULL, 0)', array(
                                'loginname' => $loginname,
                                'plaintextpassword' => $password,
                                'passwordhash' => $password_hash,
                                'group' => $group
                            ));
                    $profilesql[] = dsprintf('("###", "%(surname)s", "%(firstname)s", %(homeschool)u, NULL)', array(
                                'surname' => $surname,
                                'firstname' => $firstname,
                                'homeschool' => $homeschoolid
                            ));
                    // The ### will be replaced with the unique primary key of the user table
                }
            }

            $usersql_gapsfilled = 'INSERT INTO `' . $this->mysqltables['user'] . '` (`name`, `plaintextpassword`, `password`, `group`, `lastlogin`, `partnerid`, `status`)
             VALUES ' . implode(',', $usersql);


            $result = $this->mysql->execute($usersql_gapsfilled);
            if (!$result) {
                return false;
            }
            $first_id = (integer) $this->mysql->id(); // Returns only the first generated id
            // Replace the ### with the generated ids of the user table
            for ($i = 0, $len = count($profilesql); $i < $len; $i++) {
                $profilesql[$i] = str_replace('###', $first_id + $i, $profilesql[$i]);
            }

            $profilesql_gapsfilled = 'INSERT INTO `' . $this->mysqltables['profile'] . '` (`uid`, `surname`, `firstname`, `homeschool`, `email`)
             VALUES ' . implode(',', $profilesql);
            $result = $this->mysql->execute($profilesql_gapsfilled);
            if (!$result) {
                return false;
            }
        }

        // removePartner
        if ($this->removePartner) {

            $noerrors = true;
            foreach ($this->removePartner as &$partnerid) {

                $sql = dsprintf('UPDATE `%(usertable)s` SET `partnerid` = NULL WHERE `id` = %(partnerid)u OR `partnerid` = %(partnerid)u', array(
                            'partnerid' => $partnerid,
                            'usertable' => $this->mysqltables['user']
                        ));
                $result = $this->mysql->execute($sql);
                if (!$result) {
                    $noerrors = false;
                }
            }

            if (!$noerrors) {
                return false;
            }
        }

        // setPartner
        if ($this->setPartner) {
            $noerrors = true;
            foreach ($this->setPartner as &$pair) {
                list($userid, $partnerid) = $pair;

                $sql = dsprintf('UPDATE `%(usertable)s` SET `partnerid` = %(partnerid)u WHERE `id` = %(userid)u', array(
                            'userid' => $userid,
                            'partnerid' => $partnerid,
                            'usertable' => $this->mysqltables['user']
                        ));
                $result = $this->mysql->execute($sql);
                if (!$result) {
                    $noerrors = false;
                }
            }

            if (!$noerrors) {
                return false;
            }
        }

        // profileField
        if ($this->profileField) {
            $noerrors = true;
            foreach ($this->profileField as $userid => &$user) {
                $setsql = array();
                foreach ($user as &$field) {

                    list($fieldname, $fieldvalue) = $field;

                    $setsql[] = dsprintf(' `%(profiletable)s`.`%(fieldname)s` = "%(fieldvalue)s" ', array(
                                'fieldname' => $this->mysql->escape($fieldname),
                                'fieldvalue' => $fieldvalue != 'NULL' ? $this->mysql->escape($fieldvalue) : $fieldvalue,
                                'profiletable' => $this->mysqltables['profile']
                            ));
                }


                $sql = dsprintf('UPDATE `%(profiletable)s` SET %(sets)s WHERE `%(profiletable)s`.`uid` = %(userid)u', array(
                            'userid' => $userid,
                            'sets' => implode(',', $setsql),
                            'profiletable' => $this->mysqltables['profile']
                        ));

                $result = $this->mysql->execute($sql);
                if (!$result) {
                    $noerrors = false;
                }
            }

            if (!$noerrors) {
                return false;
            }
        }


        // subjectField
        if ($this->subjectField) {
            $noerrors = true;
            foreach ($this->subjectField as $value) {

                list($tablename, $userid, $rank, $subject, $text) = $value;


                $sql = dsprintf('DELETE FROM `%(tablename)s` WHERE `uid` = %(userid)u AND `rank` = %(rank)u', array(
                            'tablename' => $this->mysql->escape($tablename),
                            'userid' => (integer) $userid,
                            'rank' => (integer) $rank,
                            'subject' => (integer) $subject
                        ));

                $result = $this->mysql->execute($sql);
                if (!$result) {
                    $noerrors = false;
                }

                $sql = dsprintf('
INSERT INTO `%(tablename)s` (`uid`,`rank`,`subject`,`text`) VALUES (%(userid)u,%(rank)u,%(subject)u,"%(text)s")', array(
                            'tablename' => $this->mysql->escape($tablename),
                            'userid' => (integer) $userid,
                            'rank' => (integer) $rank,
                            'subject' => (integer) $subject,
                            'text' => $text != 'NULL' ? $this->mysql->escape($text) : $text
                        ));

                $result = $this->mysql->execute($sql);
                if (!$result) {
                    $noerrors = false;
                }
            }

            if (!$noerrors) {
                return false;
            }
        }



        return true;
    }

    /*
     * Check whether a loginname is valid.
     */

    function db_loginname_valid($allusers, $loginname) {
        foreach ($allusers as &$user) {
            if ($user['name'] == $loginname) {
                return false;
            }
        }
        return true;
    }

    function deleteUser($id) {
        $noerrors = true;


        $sql = 'DELETE FROM `' . $this->mysqltables['user'] . '` WHERE `id` = ' . (integer) $id;
        $result = $this->mysql->execute($sql);
        if (!$result) {
            $noerrors = false;
        }


        $sql = 'DELETE FROM `' . $this->mysqltables['profile'] . '` WHERE `uid` = ' . (integer) $id;
        $result = $this->mysql->execute($sql);
        if (!$result) {
            $noerrors = false;
        }

        $sql = 'DELETE FROM `' . $this->mysqltables['profile'] . '_favourite_lessons` WHERE `uid` = ' . (integer) $id;
        $result = $this->mysql->execute($sql);
        if (!$result) {
            $noerrors = false;
        }

        $sql = 'DELETE FROM `' . $this->mysqltables['profile'] . '_hobbies` WHERE `uid` = ' . (integer) $id;
        $result = $this->mysql->execute($sql);
        if (!$result) {
            $noerrors = false;
        }

        $sql = 'DELETE FROM `' . $this->mysqltables['profile'] . '_special` WHERE `uid` = ' . (integer) $id;
        $result = $this->mysql->execute($sql);
        if (!$result) {
            $noerrors = false;
        }

        if (!$noerrors) {
            return false;
        }
        return true;
    }

    function getPlainPassword($id) {
        $sql = 'SELECT `plaintextpassword` FROM `' . $this->mysqltables['user'] . '` WHERE `id` = ' . (integer) $id;
        $result = $this->mysql->select($sql, 'field');

        if ($result) {
            return (String) $result;
        }
        return false;
    }

    function changePassword($id, $password, $plaintext=false) {
        $plain = 'NULL';
        if ($plaintext) {
            $plain = '"' . $this->mysql->escape($password) . '"';
        }

        $password = hash('sha512', $password);
        $sql = dsprintf('UPDATE `%(usertable)s` SET `password` = "%(password)s",`plaintextpassword` = %(plain)s WHERE `id` = %(id)u', array(
                    'password' => $password,
                    'plain' => $plain,
                    'id' => (integer) $id,
                    'usertable' => $this->mysqltables['user']
                ));


        $result = $this->mysql->execute($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    function optimizeTable() {

        $error = 0;

        $result = $this->mysql->execute(sprintf('OPTIMIZE TABLE `' . $this->mysqltables['user'] . '`', (string) $set->result));
        $error = $result ? $error : $error + 1;

        $result = $this->mysql->execute(sprintf('OPTIMIZE TABLE `' . $this->mysqltables['profile'] . '`', (string) $set->foreach));
        $error = $result ? $error : $error + 1;

        $result = $this->mysql->execute(sprintf('OPTIMIZE TABLE `' . $this->mysqltables['profile'] . '_favourite_lessons`', (string) $set->foreach));
        $error = $result ? $error : $error + 1;

        $result = $this->mysql->execute(sprintf('OPTIMIZE TABLE `' . $this->mysqltables['profile'] . '_hobbies`', (string) $set->foreach));
        $error = $result ? $error : $error + 1;

        $result = $this->mysql->execute(sprintf('OPTIMIZE TABLE `' . $this->mysqltables['profile'] . '_special`', (string) $set->foreach));
        $error = $result ? $error : $error + 1;

        return $error ? false : true;
    }

    function checkIntegrity() {

        foreach ($this->xml->set as $set) {
            $attr = $set->attributes();
            $combination = (Boolean) $attr['combination'];
            if (!$set->foreach || !$set->a || !$set->result || ($combination && !$set->a[1])) {
                $this->errors[] = 'Syntax error in ' . $this->filename . ' in Survey::db_createTables()';
                return false;
            }
        }
        return true;
    }

}

?>