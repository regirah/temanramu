<?php
session_start();
require("config/koneksi.php");

$basePath = '/temanramu';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace($basePath, '', $uri);

switch ($path) {
    case '/':
        include 'landingpage.php';
        break;

    case '/tentang':
        include 'landingpage.php';
        break;
        
    case '/detailtanheb':
        include 'detailtanheb.php';
        break;

    case '/galeritanheb':
        include 'galeritanheb.php';
        break;

    case '/produkherbal':
        include 'produkherbal.php';
        break;
        case '/detailpherbal':
        include 'detailpherbal.php';
        break;

    case '/loginPengguna':
        include 'loginPengguna.php';
        break;

    case '/daftarAkunPengguna':
        include 'daftarAkunPengguna.php';
        break;

    case '/profilPengguna':
        include 'profilPengguna.php';
        break;

    default:
        echo "404 - Page not found.";
        break;
}
