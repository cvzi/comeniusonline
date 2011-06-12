<?php

/**
 * class/Notes.class.php
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

/**
 * Description of Notes
 *
 * @author cuzi
 */
class Notes {

    private $mysql;
    public $errors;
    public $mysqltables;
    public $subjectid;

    function __construct($tables, &$errors, &$mysql, $subjectid=false) {
        $this->mysqltables = $tables;
        $this->errors = &$errors;
        $this->mysql = &$mysql;


        if ($errors && (!$tables || !$mysql)) {
            $this->errors[] = 'Unexpected Parameter in Notes::__construct($tables,&$errors, &$mysql, $id=false)';
            return;
        }

        if ($subjectid) {
            $this->getNotesBySubjectId($subjectid);
            $this->subjectid = $subjectid;
        }
    }

    function getNotesBySubjectId($id) {

        $sql = dsprintf('SELECT * FROM `%(notestable)s` WHERE `%(notestable)s`.`subjectuid` = %(uid)u', array(
                    'uid' => $id,
                    'notestable' => $this->mysqltables['notes']
                ));

        $result = $this->mysql->select($sql, 'assocList');

        if ($result) {
            $this->notes = $result;
        } else {
            $this->notes = array();
        }

        return $this;
    }

    function addNote($authorid, $position, $text, $subjectid=false, $time=false, $read=0, $done=0) {

        $authorid = (integer) $authorid;
        $position = $this->mysql->escape($position);
        $text = $this->mysql->escape($text);


        $subjectid = (integer) $subjectid;
        if (!$subjectid && $this->subjectid) {
            $subjectid = $this->subjectid;
        } else if (!$subjectid) {
            $this->errors[] = 'Notes::addNote() expected 4th parameter to be an integer if a predifined subjectid cannot be found!';
            return false;
        }

        if (!$time) {
            $time = 'NOW()';
        } else {
            $time = '"' . $this->mysql->escape($time) . '"';
        }

        $read = $read ? 1 : 0;
        $done = $done ? 1 : 0;

        $sql = dsprintf('INSERT INTO `%(notestable)s` (
                `subjectuid` , `authoruid` , `time` , `position` , `read` , `done` , `text` )
                 VALUES (
                %(subjectid)u, %(authorid)u, %(time)s, "%(position)s", %(read)d, %(done)d, "%(text)s" )', array(
                    'subjectid' => $subjectid,
                    'authorid' => $authorid,
                    'time' => $time,
                    'position' => $position,
                    'read' => $read,
                    'done' => $done,
                    'text' => $text,
                    'notestable' => $this->mysqltables['notes']
                ));


        $result = $this->mysql->execute($sql);

        if ($result) {
            return $this->mysql->id();
        }

        $this->errors[] = 'Unexpected Database Error in Notes::addNote()';
        return false;
    }

    function deleteNote($noteid) {
        $noteid = (integer) $noteid;
        $sql = dsprintf('DELETE FROM `%(notestable)s` WHERE `id` = %(noteid)d', array(
                    'noteid' => $noteid,
                    'notestable' => $this->mysqltables['notes']
                ));

        $result = $this->mysql->execute($sql);

        if ($result) {
            return true;
        }

        $this->errors[] = 'Unexpected Database Error in Notes::addNote()';
        return false;
    }

    function optimizeTable() {
        $result = $this->mysql->execute(sprintf('OPTIMIZE TABLE `' . $this->mysqltables['notes'] . '`', (string) $set->result));

        return $result ? true : false;
    }

}

?>