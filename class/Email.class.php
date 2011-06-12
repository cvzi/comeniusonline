<?php

/**
 * class/Email.class.php
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
 * Description of Email
 *
 * @author cuzi
 */
class Email {

    public $to = null;
    public $from = null;
    public $subject = null;
    public $body = null;
    public $headers = null;
    public $smarty = null;
    public $error = 0;
    public $data = array();
    public $html = false;

    function __construct($smartydir, $templatefile, $to, $from, $subject) {

        $this->error = 0;
        $this->error += $this->check($to) ? 0 : 1;
        $this->error += $this->check($from) ? 0 : 1;
        $this->error += $this->check($subject) ? 0 : 1;

        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->templatefile = $templatefile;

        // Smarty
        require_once($smartydir . 'libs/Smarty.class.php');
        $this->smarty = new Smarty;
        $this->smarty->caching = false;
        $this->smarty->config_dir = $smartydir . 'config/';
        $this->smarty->cache_dir = $smartydir . 'cache/';
        $this->smarty->compile_dir = $smartydir . 'templates_c/';
    }

    function html($set=true) {
        $this->html = $set;
    }

    function assign($varname, $var, $nocache=false) {
        $this->data[$varname] = $var;
        $this->smarty->assign($varname, $var, $nocache);
    }

    function getJSON() {
        return json_encode($this->data);
    }

    function getJSON64() {
        return base64_encode($this->getJSON());
    }

    function check($value) {
        // Anti-header-injection - Use before mail()
        // By Victor Benincasa <vbenincasa(AT)gmail.com>
        // ERROR: Code injection attempt denied! Please don't use the following sequences in your message: 'TO:', 'CC:', 'CCO:' or 'Content-Type'.
        if (strpos($value, "TO:") !== false || strpos($value, "CC:") !== false || strpos($value, "CCO:") !== false || strpos($value, "Content-Type") !== false) {
            return false;
        }
        return true;
    }

    function send() {
        if ($this->error != 0) {
            return false;
        }

        $this->addHeader('From: ' . $this->from . "\r\n");
        $this->addHeader('Reply-To: ' . $this->from . "\r\n");
        $this->addHeader('Return-Path: ' . $this->from . "\r\n");
        $this->addHeader('X-mailer: PHP-' . phpversion() . "\r\n");
        if ($this->html) {
            $this->addHeader('Content-type: text/html; charset=UTF-8' . "\r\n");
            $this->assign('html',true);
        } else {
            $this->addHeader('Content-type: text/plain; charset=UTF-8' . "\r\n");
        }

        $this->body = $this->smarty->fetch($this->templatefile);

        return mail($this->to, '=?UTF-8?B?' . base64_encode($this->subject) . '?=', $this->body, $this->headers);
    }

    function addHeader($header) {
        $this->headers .= $header;
    }

}

?>