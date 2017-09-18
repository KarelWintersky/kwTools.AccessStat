<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 3:51
 */
 
$action = $_GET['action'] ?? die('Incorrect action');

require_once 'config.php';

switch ($action) {
    case 'get-banners-list': {
        // получить список баннеров таблицей
        break;
    }
    case 'add': {
        if ( strtolower( $_POST['password'] ?? NULL ) !== $GLOBAL_SETTINGS['global']['password']) {
            die('Incorrect password');
        }




        // добавить баннер в базу
        break;
    }
    default: {
        die('Incorrect action');
        break;
    }
} // switch
