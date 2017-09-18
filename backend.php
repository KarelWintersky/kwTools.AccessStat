<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 3:51
 */
 
$action = $_GET['action'] ?? 'DIE';

switch ($action) {
    case 'get-banners-list': {
        // получить список баннеров таблицей
        break;
    }
    case 'add-banner': {
        // добавить баннер в базу
        break;
    }
} // switch
