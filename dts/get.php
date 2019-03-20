<?php

function getHome() {
    $url = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
    $url = explode('/', $url);
    $url[0] = ($url[0] == NULL ? 'home' : $url[0]);

    if (file_exists('tpl/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '.php')) {
        require 'tpl/' . $url[0] . '/' . $url[1] . '/' . $url[2] . '.php';
    } elseif (file_exists('tpl/' . $url[0] . '/' . $url[1] . '.php')) {
        require 'tpl/' . $url[0] . '/' . $url[1] . '.php';
    } elseif (file_exists('tpl/' . $url[0] . '.php')) {
        require 'tpl/' . $url[0] . '.php';
    } else {
        require 'tpl/404.php';
    }
}

function setHeader() {
    require_once('tpl/header.inc.php');
}

function setBreadCrumbs() {
    require_once('tpl/breadcrumbs.inc.php');
}

function setFooter() {
    require('tpl/footer.inc.php');
}

function setHome() {
    echo BASE;
}

?>