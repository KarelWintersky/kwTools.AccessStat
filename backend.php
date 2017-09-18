<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 3:51
 */
 
$action = $_GET['action']; // ?? die('Incorrect action');
if ($action === NULL) die('Incorrect action');

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
        $query = "
INSERT
INTO `banner_list` (`alias`, `url`, `owner`, `created` )
VALUES (:alias,	:url, :owner, NOW());
        ";
        $sth = $dbh->prepare($query);
        try {
            $sth->execute( array(
                'alias'     =>  md5($_POST['owner'] . '/@/' . $_POST['url']),
                'owner'     =>  $_POST['owner'],
                'url'       =>  $_POST['url']
            ));
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
        $dbh = null;
        if (headers_sent() === false) die(header('Location: ' . $_SERVER['HTTP_REFERER']));

        // добавить баннер в базу
        break;
    }
    default: {
        die('Incorrect action');
        break;
    }
} // switch
