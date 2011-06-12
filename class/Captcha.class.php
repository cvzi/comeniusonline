<?php

/**
 * class/Captcha.class.php
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
 * Description of Captcha
 *
 * @author cuzi
 */
class Captcha {

    private $errors;
    private $user;
    private $imgstr;
    private $oldsolution = null;
    public $gd = false;

    function __construct(&$errors, &$user, $str) {

        $this->errors = &$errors;
        $this->user = &$user;

        $this->imgstr = $str;
        $this->setSolution($str);

        if (extension_loaded('gd') && function_exists('gd_info')) {
            $this->gd = true;
        } else {
            $this->errors[] = 'Error in Captcha::__construct(): GD Library is not available!';
        }
    }

    function setSolution($str) {
        if ($this->oldsolution === null) {
            $this->oldsolution = $this->user->get('captcha_solution', false);
        }
        $this->user->set('captcha_solution', $str);
    }

    function setImageText($str) {
        $this->imgstr = $str;
    }

    function getImageResource() {
        if (!$this->gd) {
            return false;
        }
        $font = 5;
        $width = imagefontwidth($font) * strlen($this->imgstr) + 10;
        $height = imagefontheight($font) + 3;
        $im = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $white);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagestring($im, $font, 1, 1, $this->imgstr, $black);
        $angle = (mt_rand(0, 1) ? 1 : -1) * mt_rand(0, 5);
        $im = imagerotate($im, $angle, $white);
        return $im;
    }

    function getBase64($quality=70) {
        $im = $this->getImageResource();

        if (!$im) {
            return false;
        }

        $oldoutput = ob_get_contents();
        ob_end_clean();
        ob_start();
        imagejpeg($im, NULL, $quality);
        $contents = ob_get_contents();
        ob_end_clean();
        ob_start();
        if ($oldoutput) {
            echo $oldoutput;
        }
        imagedestroy($im);
        $imgbase64 = base64_encode($contents);
        return $imgbase64;
    }

    function solve($str) {
        $shouldbe = $this->oldsolution;
        if (!$shouldbe || $shouldbe != $str) {
            return false;
        }
        return true;
    }

}

?>