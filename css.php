<?php

/**
 * css.php
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

#ini_set('display_errors', '1');
#error_reporting(E_ALL);
error_reporting(E_ALL ^ E_NOTICE);

// Config
$found = false;
$found = @ include getcwd() . '/config.php';
// Leave getcwd() here, else we might not get an error
// as php will probably find an alien config.php in the php.ini's include_path
if ($found === false) {
    echo '<h1>Failed to load config file</h1>';
    exit; // A wrong config.php could damage this program as well as make it vulnerable to criminals
}

// Navi instanzieren
$errors = array();
$navi = new navigation($errors, $navifile);
$baseurl = $navi->getBase();



$q = explode(',', $_GET['q']);

header('content-type: text/css; charset: UTF-8');
header('cache-control: must-revalidate');
$offset = 60 * 60;
$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
header ($expire);


ob_start('compress');

function compress($buffer) {
    // remove tabs and newlines
    $buffer = str_replace(array("\n","\t"), '', $buffer);
    return $buffer;
}


?>
<?php if(in_array('general',$q)): ?>
.fl {
    float:left
}
.fr {
    float:right
}
.cl {
    clear:left
}
.cr {
    clear:right
}
.cb {
    clear:both
}
input:focus
{
  outline: none;
}
<?php endif; ?>

<?php if (in_array('errors',$q)): ?>


.hint, .error {
    text-align: center;
    position: relative;
    color: #3d643d;
    margin: 0 -30px 30px -30px;
    padding: 5px 0;
    text-shadow: 0 1px rgba(0,0,0,.8);
    background: #97f997;
    background-image: -moz-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -webkit-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image:  linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    -moz-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    -webkit-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    box-shadow: 0 2px 0 rgba(0,0,0,.3);
}

.error {
    background:LightCoral;
    color:Black;
}

.hint:before, .hint:after ,.error:before, .error:after
{
    content: '';
    position: absolute;
    border-style: solid;
    border-color: transparent;
    bottom: -10px;
    z-index:10;
}

.hint:before, .error:before
{
    border-width: 0 10px 10px 0;
    border-right-color: #222;
    left: 0;
    z-index:10;
}

.hint:after, .error:after
{
    border-width: 0 0 10px 10px;
    border-left-color: #222;
    right: 0;
    z-index:10;
}

.buffer {
    font-family:sans-serif;
    padding:10px;
    margin-top:5px;
    color:White;
    background:LightSkyBlue;
    border:5px DodgerBlue solid;
    white-space:pre-wrap;
}
<?php endif; ?>

<?php if (in_array('design',$q)): ?>

html
{
    background: #ddd;
}

body
{
    margin: 30px auto;
    padding: 20px;
    width: 90%;
    background: #fff;
    font-family: 'trebuchet MS', Arial, helvetica;

    -moz-border-radius: 10px;
    border-radius: 10px;
    -moz-box-shadow: 0 0 10px #555;
    -webkit-box-shadow: 0 0 10px #555;
    box-shadow: 0 0 10px #555;
}

<?php
// Logo
?>

#logo {
    color: #FFFFFF;
    font-family: Tahoma;
    font-size: 55px;
    margin: 25px 0;
    text-align: center;
    text-shadow: 0 0 4px #DB6969, 0 0 0.2em #DB6969;
}

<?php
// Left Slider
?>

#slideout {
    position: fixed;
    bottom: 40px;
    left: 0;
    -webkit-transition-duration: 0.3s;
    -moz-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;

}
#slideout_button {
    color:White;
    text-align:center;
    font-weight:bold;

    -webkit-border-top-right-radius: 10px;
    -webkit-border-bottom-right-radius: 10px;
    -moz-border-radius-topright: 10px;
    -moz-border-radius-bottomright: 10px;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    padding:5px;
    background:#DB6969;
    z-index:14;

}
#slideout_inner {
    position: fixed;
    bottom: 40px;
    left: -300px;
    -webkit-transition-duration: 0.3s;
    -moz-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
    background:#DB6969;
    min-height:150px;
    width:300px;
    z-index:15;
}
#slideout:hover {
    left: 300px;
}
#slideout:hover #slideout_inner {
    left: 0;
}


<?php
// Headings
?>

h1{
    text-align: center;
    position: relative;
    color: #fff;
    margin: 0 -30px 30px -30px;
    padding: 10px 0;
    text-shadow: 0 1px rgba(0,0,0,.8);
    background: #5c5c5c;
    background-image: -moz-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -webkit-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image:  linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    -moz-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    -webkit-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    box-shadow: 0 2px 0 rgba(0,0,0,.3);
}

h1:before, h1:after
{
    content: '';
    position: absolute;
    border-style: solid;
    border-color: transparent;
    bottom: -10px;
    z-index:10;
}

h1:before
{
    border-width: 0 10px 10px 0;
    border-right-color: #222;
    left: 0;
    z-index:10;
}

h1:after
{
    border-width: 0 0 10px 10px;
    border-left-color: #222;
    right: 0;
    z-index:10;
}



h2{
    text-align: center;
    position: relative;
    color: #fff;
    margin: 0 -30px 30px -30px;
    padding: 5px 0;
    text-shadow: 0 1px rgba(0,0,0,.8);
    background: #5c5c5c;
    background-image: -moz-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -webkit-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image:  linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    -moz-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    -webkit-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    box-shadow: 0 2px 0 rgba(0,0,0,.3);
}

h2:before, h2:after
{
    content: '';
    position: absolute;
    border-style: solid;
    border-color: transparent;
    bottom: -10px;
    z-index:10;
}

h2:before
{
    border-width: 0 10px 10px 0;
    border-right-color: #222;
    left: 0;
    z-index:10;
}

h2:after
{
    border-width: 0 0 10px 10px;
    border-left-color: #222;
    right: 0;
    z-index:10;
}


<?php
// Content: top right corner
?>

#shortuserinfo {
    background-color: #DB6969;
    background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, rgba(255, 255, 255, 0)),color-stop(1, rgba(255, 255, 255, 0.8)));
    background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -o-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    box-shadow: 0 2px 2px #CCCCCC;
    color: #222222;
    float: right;
    font-size: 0.8em;
    font-weight: bold;
    margin: -26px 0 40px;
    padding: 10px 30px;
    position: relative;
    text-shadow: 0 1px rgba(255, 255, 255, 0.8);
}

#footerinfo {
    background-color: Silver;
    background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, rgba(255, 255, 255, 0)),color-stop(1, rgba(255, 255, 255, 0.8)));
    background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -o-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    box-shadow: 0 2px 2px #CCCCCC;
    color: #222222;
    float: right;
    font-size: 0.7em;
    font-weight: bold;
    padding: 10px 30px;
    position: relative;
    text-shadow: 0 1px rgba(255, 255, 255, 0.8);
}

#footerinfo a,a:link,.jslink,a:hover,a:focus,.jslink:hover,a:visited {
    color:Black;
    text-decoration:none;
}
#footerinfo a:hover,.jslink:hover {
    color:Black;
    text-decoration:underline;
}

#copyinfo {
    background-color:none;
    box-shadow:none;
    color: Silver;
    float: left;
    font-size: 0.7em;
    padding: 0px;
    position: relative;
    text-shadow: none;
}

#copyinfo a,a:link,.jslink,a:hover,a:focus,.jslink:hover,a:visited {
    color:Silver;
    text-decoration:none;
}
#copyinfo a:hover,.jslink:hover {
    color:Silver;
    text-decoration:underline;
}

<?php
// Buttons (not input buttons)
?>

.button
{
    color: White;

    text-decoration: none;
    margin: 10px;

    font: bold 1.5em 'Trebuchet MS',Arial, Helvetica; /*Change the em value to scale the button*/
    display: inline-block;
    text-align: center;

    border: 1px solid #9c9c9c; /* Fallback style */
    border: 1px solid rgba(0, 0, 0, 0.3);

    text-shadow: 0 1px 0 rgba(0,0,0,0.4);

    box-shadow: 0 0 .05em rgba(0,0,0,0.4);
    -moz-box-shadow: 0 0 .05em rgba(0,0,0,0.4);
    -webkit-box-shadow: 0 0 .05em rgba(0,0,0,0.4);

}


.button:link,a.button
{
    color: White;
    text-decoration: none;
}
.button, .button span
{
    -moz-border-radius: .3em;
    border-radius: .3em;
}

.button span
{
    border-top: 1px solid #fff; /* Fallback style */
    border-top: 1px solid rgba(255, 255, 255, 0.5);
    display: block;
    padding: 0.5em 2.5em;
    color:white;
}

.button:hover
{
    color: #fff;
    text-decoration: none;

    box-shadow: 0 0 .1em rgba(0,0,0,0.4);
    -moz-box-shadow: 0 0 .1em rgba(0,0,0,0.4);
    -webkit-box-shadow: 0 0 .1em rgba(0,0,0,0.4);
}

.button:active
{
    /* When pressed, move it down 1px */
    position: relative;
    top: 1px;
}

.button-red
{
    background: #D92020;
    background: -moz-linear-gradient(-90deg, #DB6969, #D92020);
    background: -webkit-gradient(linear, left top, left bottom, from(#DB6969), to(#D92020) );
    filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr='#DB6969', EndColorStr='#D92020');
    color:white;
}
.button-red:hover
{
    background: #DB6969;
    background: -moz-linear-gradient(-90deg, #D92020, #DB6969);
    background: -webkit-gradient(linear, left top, left bottom, from(#D92020), to(#DB6969) );
    filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr='#D92020', EndColorStr='#DB6969');
    color:white;
}
.button-red:active
{
    background: #D92020;
    color:white;
}

.button-green
{
    background: #84D684;
    background: -moz-linear-gradient(-90deg, #4CBD4C, #84D684);
    background: -webkit-gradient(linear, left top, left bottom, from(#4CBD4C), to(#84D684) );
    filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr='#4CBD4C', EndColorStr='#84D684');
    color:white;
}
.button-green:hover
{
    background: #4CBD4C;
    background: -moz-linear-gradient(-90deg, #84D684, #4CBD4C);
    background: -webkit-gradient(linear, left top, left bottom, from(#84D684), to(#4CBD4C) );
    filter: progid:DXImageTransform.Microsoft.Gradient(GradientType=0, StartColorStr='#84D684', EndColorStr='#4CBD4C');
    color:white;
}
.button-green:active
{
    background: #84D684;
    color:white;
}



<?php
// Menu / Navigation
?>

#menu
{
    position: relative;

    height:16px;
    width: 650px;
    margin: -26px 0 40px;
    padding:7px 30px 15px;
    float: left;

    list-style: none;

    background-color: #DB6969;
    background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, rgba(255, 255, 255, 0)),color-stop(1, rgba(255, 255, 255, 0.8)));
    background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -o-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));

    -moz-box-shadow: 0 2px 2px #CCCCCC;
    -webkit-box-shadow: 0 2px 2px #CCCCCC;
    box-shadow: 0 2px 2px #CCCCCC;

    float: left;
}


#menu li
{
    float: left;
    padding: 0 0 10px 0;
    position: relative;
}

#menu a
{
    float: left;
    height: 25px;
    padding: 0 25px;
    color: Black;
    text-transform: uppercase;
    font: bold 12px/25px Arial, Helvetica;
    text-decoration: none;
    text-shadow: 0 1px 0 White;
}

#menu li:hover > a
{
    color: #fafafa;
}

#menu li:hover a
{
    text-shadow: none;
}
#menu > li:hover > a
{
    text-shadow: 0 1px 0 Black;
}




*html #menu li a:hover /* IE6 */
{
    color: #fafafa;
}

#menu li:hover > ul
{
    display: block;
}

/* Sub-menu */

#menu ul
{
    list-style: none;
    margin: 0;
    padding: 0;
    display: none;
    position: absolute;
    top: 35px;
    left: 0;
    z-index: 99999;
    background: #888;
    background: -moz-linear-gradient(#888, #222);
    background: -webkit-gradient(linear,left bottom,left top,color-stop(0, #222),color-stop(1, #888));
    background: -webkit-linear-gradient(#888, #222);
    background: -o-linear-gradient(#888, #222);
    background: -ms-linear-gradient(#888, #222);
    background: linear-gradient(#888, #222);
    -moz-border-radius: 5px;
    border-radius: 5px;
}

#menu ul li
{
    float: none;
    margin: 0;
    padding: 0;
    display: block;
    -moz-box-shadow: 0 1px 0 #222222, 0 2px 0 #777777;
    -webkit-box-shadow: 0 1px 0 #222222, 0 2px 0 #777777;
    box-shadow: 0 1px 0 #222222, 0 2px 0 #777777;
}

#menu ul li:last-child
{
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    box-shadow: none;
}

#menu ul a
{
    color:white;
    padding: 10px;
    height: auto;
    line-height: 1;
    display: block;
    white-space: nowrap;
    float: none;
    text-transform: none;
}

*html #menu ul a /* IE6 */
{
    height: 10px;
    width: 150px;
}

*:first-child+html #menu ul a /* IE7 */
{
    height: 10px;
    width: 150px;
}

#menu ul a:hover
{
    background: #D92020;
    background: -moz-linear-gradient(#DB6969,  #D92020);
    background: -webkit-gradient(linear, left top, left bottom, from(#DB6969), to(#D92020));
    background: -webkit-linear-gradient(#DB6969,  #D92020);
    background: -o-linear-gradient(#DB6969,  #D92020);
    background: -ms-linear-gradient(#DB6969,  #D92020);
    background: linear-gradient(#DB6969,  #D92020);
}

#menu ul li:first-child a
{
    -moz-border-radius: 5px 5px 0 0;
    border-radius: 5px 5px 0 0;
}

#menu ul li:first-child a:after
{
    content: '';
    position: absolute;
    left: 30px;
    top: -8px;
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-bottom: 8px solid #888;
}

#menu ul li:first-child a:hover:after
{
    border-bottom-color: #DB6969;
}

#menu ul li:last-child a
{
    -moz-border-radius: 0 0 5px 5px;
    border-radius: 0 0 5px 5px;
}

#menu li.selected a
{
    text-decoration:underline;
}


/* Clear floated elements */
#menu:after
{
    visibility: hidden;
    display: block;
    font-size: 0;
    content: " ";
    clear: both;
    height: 0;
}

* html #menu             { zoom: 1; } /* IE6 */
*:first-child+html #menu { zoom: 1; } /* IE7 */


<?php
// General form elements
?>

select, input, textarea
{
    background: #eaf8fc;
    background-image: -moz-linear-gradient(#fff, #d4e8ec);
    background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, #d4e8ec),color-stop(1, #fff));

    -moz-border-radius: 34px;
    border-radius: 34px;

    border-width: 1px;
    border-style: solid;
    border-color: #c4d9df #a4c3ca #83afb7;
    padding: 10px;
    margin: 5px auto 10px;
    overflow: hidden; /* Clear floats */
}
select, input {
    height: 34px;
}


input[type=submit] {
    font-weight:bold;
    padding:7px;
    cursor:pointer;
}
input[type=submit]:hover {
    background-image: -moz-linear-gradient(#d4e8ec,#fff);
    background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, #fff),color-stop(1, #d4e8ec));


}
select {
    padding:15px;
    height:50px;

}

textarea {
    overflow:auto; }

<?php
// Other general stuff
?>

.jslink {
    cursor:pointer;
}

a,a:link,.jslink {
    color:Black;
    text-decoration:underline;
}
a:hover,a:focus,.jslink:hover {
    text-decoration:underline;
    color:Black;
}
a:visited {
    text-decoration:underline;
    color:Darkgray;
}

fieldset {
    -moz-border-radius:10px;
    -webkit-border-radius:10px;
    border-radius:10px;
    padding:10px;
    margin:5px;
}

abbr[title], acronym[title] {
    border-bottom: 1px dotted;
}

.bold,th {
  font-weight:bold;
}

.italic {
  font-style:italic;
}

.label {
  width:150px;
}
.biglabel {
  width:200px;
}


<?php
// Usermanager
?>

#userlist .school {
  border: 2px solid black;
  margin:2px;
  padding:3px;

}

#userlist .user {
  border: 1px solid black;
  margin:5px;
  padding:3px;

}


#user {
    width:200px;
    height:64px;
    padding:10px 20px;
    float:right;
}

#user #welcome #message {
    font:11px arial;
    color:#9d9d9d;
    margin:5px 0 0 5px;
}

#user #welcome #message span.green {
    color:#6d9836;
    font-weight:bold;
}

#user #buttons {
    margin:10px -8px 0;
}

<?php
// Predefined Inputs and Tooltips
?>



.inputdec {
    display:none;
}

.tool-tip {
    color:#fff;
    width:139px;
    z-index:13000;
}

.tip-top {
    font-weight:bold;
    font-size:11px;
    margin:0;
    background:url(<?php echo $baseurl; ?>images/bubble.png) top left;
}

.tip {
    background:url(<?php echo $baseurl; ?>images/bubble.png) bottom right;
    color:White;
    padding:8px 8px 4px;
}

.tip-bottom {
    background:url(<?php echo $baseurl; ?>images/bubble.png) bottom right;
}


<?php endif; ?>

<?php if(in_array('table',$q)): ?>

// My Profile Edit Table
?>


.features-table
{
    margin: 0 auto;
    border-collapse: separate;
    border-spacing: 0;
    text-shadow: 0 1px 0 #fff;
    color: #2a2a2a;
    background: #fafafa;
    background-image: -moz-linear-gradient(top, #fff, #eaeaea, #fff); /* Firefox 3.6 */
    background-image: -webkit-gradient(linear,center bottom,center top,from(#fff),color-stop(0.5, #eaeaea),to(#fff));
}

.features-table td
{
    height: 50px;
    line-height: 50px;
    padding: 0 20px;
    border-bottom: 1px solid #cdcdcd;
    box-shadow: 0 1px 0 white;
    -moz-box-shadow: 0 1px 0 white;
    -webkit-box-shadow: 0 1px 0 white;
    white-space: nowrap;
    text-align: center;
}

.features-table td.green
{
    background: #e7f3d4;
    background: rgba(184,243,85,0.3);
}

.features-table td.red
{
    background: #DB6969;
    background: rgba(219,105,105,0.3);
}

.features-table td.yellow
{
    background: #fffacd;
    background: rgba(255,250,155,0.3);
}

.features-table td.hover
{
    background: #6B7B95;
    background: rgb(107, 123, 149,0.3);
}



/*Body*/
.features-table td
{
    text-align: center;
    font: normal 12px Verdana, Arial, Helvetica;
}

.features-table td:first-child
{
    width: auto;
    text-align: left;
}

.features-table td
{
    white-space:pre-wrap;
    background: #efefef;
    background: rgba(144,144,144,0.15);
    border-right: 1px solid white;
}

.features-table td:nth-child(1)
{
    font-weight:bold;
}


/*Header*/
.features-table thead td
{
    -moz-border-radius-topright: 10px;
    -moz-border-radius-topleft: 10px;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
    border-top: 1px solid #eaeaea;
}

.features-table thead td:first-child
{
    border-top: none;
}

/*Footer*/
.features-table tfoot td
{
    -moz-border-radius-bottomright: 10px;
    -moz-border-radius-bottomleft: 10px;
    border-bottom-right-radius: 10px;
    border-bottom-left-radius: 10px;
    border-bottom: 1px solid #dadada;
}

.features-table tfoot td:first-child
{
    border-bottom: none;
}


.features-table .statusimg {
  color:Silver;
  font-weight:bold;
  vertical-align:middle;
}


.features-table .statustxt {
  color:Silver;
  font-weight:bold;
  vertical-align:middle;
}

.note {
  position:absolute;
  border:3px outset khaki;
  background:lemonchiffon;
}

.note .heading {
  color:Silver;
  opacity:0.7;
}

.note p {
  white-space:pre-wrap;
  font-family:"Comic Sans MS",Fantasy;
  padding:5px;
}


<?php endif; ?>

<?php if(in_array('mobile',$q)): ?>
#mobilemenu {

}

body
{
    margin: 0px auto;
    padding: 0px;
    width: 100%;
    background: #fff;
    font-family: 'trebuchet MS', Arial, helvetica;

    -moz-border-radius: 0px;
    border-radius: 0px;
    -moz-box-shadow: 0 0 0px #555;
    -webkit-box-shadow: 0 0 0px #555;
    box-shadow: 0 0 0px #555;
}


#shortuserinfo {
    background-color: #DB6969;
    background-image: -moz-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, rgba(255, 255, 255, 0)),color-stop(1, rgba(255, 255, 255, 0.8)));
    background-image: -webkit-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -o-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: -ms-linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    background-image: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.8));
    box-shadow: 0 0px 0px #CCCCCC;
    color: #222222;
    float: right;
    font-size: 0.8em;
    font-weight: bold;
    margin: 0px;
    padding: 5px;
    text-shadow: 0 0px rgba(255, 255, 255, 0.8);
}


h1{
    text-align: center;
    position: relative;
    color: #fff;
    margin: 2px;
    padding: 5px 0;
    text-shadow: 0 0px rgba(0,0,0,.8);
    background: #5c5c5c;
    background-image: -moz-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -webkit-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image:  linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    -moz-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    -webkit-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    box-shadow: 0 0px 0 rgba(0,0,0,.3);
}
h2{
    text-align: center;
    position: relative;
    color: #fff;
    margin: 2px;
    padding: 5px 0;
    text-shadow: 0 0px rgba(0,0,0,.8);
    background: #5c5c5c;
    background-image: -moz-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -webkit-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -o-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image: -ms-linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    background-image:  linear-gradient(rgba(255,255,255,.3), rgba(255,255,255,0));
    -moz-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    -webkit-box-shadow: 0 2px 0 rgba(0,0,0,.3);
    box-shadow: 0 0px 0 rgba(0,0,0,.3);
}

#logo {
    color: #FFFFFF;
    font-family: Tahoma;
    font-size: 55px;
    text-shadow: 0 0 0px #DB6969, 0 0 0.2em #DB6969;
}


.features-table td
{
   white-space:pre-wrap;
    background: #fafafa;
    background-image: -moz-linear-gradient(top, #fff, #eaeaea, #fff); /* Firefox 3.6 */
    background-image: -webkit-gradient(linear,center bottom,center top,from(#fff),color-stop(0.5, #eaeaea),to(#fff));
    border-bottom: 1px solid #cdcdcd;
}

.features-table td.green
{
    background: #e7f3d4;
    background: rgba(184,243,85,0.3);
}

.features-table td.red
{
    background: #DB6969;
    background: rgba(219,105,105,0.3);
}

.features-table td.yellow
{
    background: #fffacd;
    background: rgba(255,250,205,0.3);
}


.features-table td:nth-child(1)
{
    font-weight:bold;
}

<?php endif; ?>


<?php if(in_array('print',$q)): ?>

html,body {
font-family:Sans-serif;
margin:0px;
padding:0px

}

.pageimage {
display:none;
}

h1 {
font-size:1.5em;
margin:2px;
padding:0px;

}

h2 {
display:none;
}


<?php endif; ?>

<?php ob_end_flush(); ?>