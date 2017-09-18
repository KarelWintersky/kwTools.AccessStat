<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 2:47
 */
function getRealIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { $ip = $_SERVER['HTTP_CLIENT_IP'];}
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }
    else { $ip = $_SERVER['REMOTE_ADDR']; }
    return $ip;
}

$alias = strtolower( $_GET['alias'] ?? NULL );
if (!ctype_alnum($alias)) {
    // передан не хэш, а что попало.
    http_response_code(404);
    die('Incorrect banner id recieved.');
}

require_once 'config.php';

// Проверяем, существует ли такой баннер?
$query = "
SELECT id FROM banner_list WHERE alias = :alias
";

$sth = $dbh->prepare($query);
$sth->execute(array( 'alias' => $alias ));

$id_banner= $sth->fetch('id');

if (!$id_banner) {
    http_response_code(404);
    die('Banner not exists.');
}

// обновляем данные по баннеру
$query = "
INSERT INTO banner_hits (id_banner, dayvisit, ipv4, hits)
VALUES(:id_banner, CURDATE(), INET_ATON(:ipv4), 1)
ON DUPLICATE KEY UPDATE hits = hits+1";

$sth = $dbh->prepare($query);
try {
    $sth->execute( array(
        'ipv4'          => getRealIP(),
        'id_banner'     => $id_banner
    ));
} catch (\PDOException $e) {
    die($e->getMessage());
}
$dbh = null;

// рисуем прозрачный гиф 1*1 пиксель
header('Content-Type: image/gif');
die("\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x90\x00\x00\xff\x00\x00\x00\x00\x00\x21\xf9\x04\x05\x10\x00\x00\x00\x2c\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x04\x01\x00\x3b");

