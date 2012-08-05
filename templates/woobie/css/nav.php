<?php
// Define for denying call of seperate files
define("IN_POSTPONE", true);
header('Content-type: text/css');
include '../../../subdir.php';
include '../../../inc/common.inc.php';
?>
/*
 * author:      SkyIrc development team <skyirc@skyirc.net>
 * copyright:   Copyright (c) SkyIrc
 * license:     http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */


nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

nav ul > li {
    list-style: none;
    margin: 0;
    padding: 0;
}

nav li > a {
    display: block;
}

nav ul > li {
    float: left;
}

nav {
    font-family: Ubuntu, sans-serif;
    font-size: 12pt;
    color: #000;
    border-bottom: 1px solid rgb(230,230,230);
    padding-left: 6px;
    padding-right: 6px;
    background: rgb(250,250,250);
}

nav a, nav a:hover {
    padding: 4px 5px;
    color: #000;
    text-decoration: none;
    transition: color 0.3s;
    -moz-transition: color 0.3s;
    -webkit-transition: color 0.3s;
    -o-transition: color 0.3s;
    text-shadow: none;
}

nav a:hover {
    color: #000;
}

nav ul > li {
    margin-right: 5px;
}

nav ul > li {
    border-left: 1px solid transparent;
    border-right: 1px solid transparent;
    padding: 0;
    margin: 0px 5px 0px 0px;
    transition: border-left 0.3s, border-right 0.3s;
    -moz-transition: border-left 0.3s, border-right 0.3s;
    -webkit-transition: border-left 0.3s, border-right 0.3s;
    -o-transition: border-left 0.3s, border-right 0.3s;
}

nav ul > li:hover {
    border-left: 1px solid rgb(230,230,230);
    border-right: 1px solid rgb(230,230,230);
}
nav ul > li.active {
    background: #fff url('<?=SUBDIR?>/templates/<?=$template->name?>/images/nav-active_bg.png') repeat-x bottom left;
    color: #000;
    border-left: 1px solid rgb(230,230,230);
    border-right: 1px solid rgb(230,230,230);
    padding-bottom: 1px;
    margin-bottom: -1px;
}
nav ul > li.active > a {
    color: #000;
}