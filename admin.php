<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 3:13
 */

// get banners list
require_once 'config.php';

$stdout = '';

$dsl = "mysql:host={$settings['hostname']};port={$settings['port']};dbname={$settings['database']}";

// подключаемся к БД
try {
    $dbh = new \PDO($dsl, $settings['username'], $settings['password']);
    $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    die ($e);
}

$q_banners_stat = "
SELECT
BL.id AS id,
BL.url AS url,
BL.owner AS banner_owner,
IFNULL(SUM(BH.hits),0) AS hits_all,
COUNT(BH.ipv4) AS hits_uniq

FROM banner_hits BH
RIGHT JOIN banner_list BL
ON BL.id = BH.id_banner

GROUP BY BL.id
";

$sth = $dbh->query($q_banners_stat);

$all_banners = $sth->fetchAll();

?>

<html>
<head>
    <title>Banner admin</title>
</head>
<body>
    <form action="backend.php?action=add">
        <ul>
            <li>URL: <input type="text" size="80" name="url"></li>
            <li>Owner: <input type="text" size="80" name="owner"></li>
            <li>Password: <input type="text" size="80" name="password"></li>
            <li><input type="submit" value="Добавить"></li>
        </ul>
    </form>
<hr>
<table border="1">
    <thead>
    <tr>
        <th>id</th>
        <th>url</th>
        <th>owner</th>
        <th>Total hits</th>
        <th>Unique hits</th>
        <th>Details...</th>
    </tr>
    </thead>
    <tbody>
<?php
foreach ($all_banners as $banner) {
    echo <<<TR_BANNER
    <tr>
        <td>{$banner['id']}</td>
        <td>{$banner['url']}</td>
        <td>{$banner['banner_owner']}</td>
        <td>{$banner['hits_all']}</td>
        <td>{$banner['hits_uniq']}</td>
        <td><a href="stats.php?id={$banner['id']}">Details &gt;&gt;&gt;</a></td>
    </tr>
TR_BANNER;
}
?>
    </tbody>
</table>

</body>
</html>
 
