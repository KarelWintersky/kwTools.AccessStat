<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 2:50
 */
$id = strtolower( $_GET['id'] ?? NULL );
if (!ctype_digit($id)) {
    // передан не хэш, а что попало. Рисуем просто
    http_response_code(404);
    die('Incorrect banner id recieved.');
}

require_once 'config.php';

$query = "
SELECT dayvisit, ipv4 AS ipv4raw, INET_NTOA(ipv4) AS ipv4, hits FROM banner_hits
WHERE id_banner = :id_banner
GROUP BY dayvisit, ipv4
";

$sth = $dbh->prepare($query);
try {
    $sth->execute( array(
        'id_banner' =>  $id
    ) );
} catch (\PDOException $e) {
    die($e->getMessage());
}

$info = $sth->fetchAll();

$dbh = null;

?>
<html>
<head>
    <title>Banner stats</title>
</head>
<body>
<table border="1" width="40%">
    <thead>
    <tr>
        <th>Дата: </th>
        <th>IPv4:</th>
        <th>Hits:</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($info) {
        foreach ($info as $row) {
            echo <<<TR_ROWS
    <tr>
        <td>{$row['dayvisit']}</td>
        <td>{$row['ipv4']}</td>
        <td>{$row['hits']}</td>
    </tr>
TR_ROWS;
        }
    } else {
        echo <<<TR_NOROWS
    <tr>
        <td colspan="3">No data. </td>
    </tr>
TR_NOROWS;

    }


    ?>
    </tbody>
</table>
</body>
</html>
