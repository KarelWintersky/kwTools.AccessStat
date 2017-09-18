<?php
/**
 * User: Arris
 * Date: 18.09.2017, time: 3:13
 */

// get banners list
require_once 'config.php';

$q_banners_stat = "
SELECT
BL.id AS id,
BL.url AS url,
BL.alias AS alias,
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
    <form action="backend.php?action=add" method="POST">
        <ul>
            <li>Page URL: <input type="text" size="80" name="url"></li>
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
        <th>Banner link</th>
        <th>Page URL</th>
        <th>owner</th>
        <th>Total hits</th>
        <th>Unique hits</th>
        <th>Details...</th>
    </tr>
    </thead>
    <tbody>
<?php
if ($all_banners) {
    foreach ($all_banners as $banner) {
        $banner['alias'] = $GLOBAL_SETTINGS['global']['site_href'] . $banner['alias'];
    }
    $all_banners[0]['alias'] = '';
    $all_banners[0]['url'] = $GLOBAL_SETTINGS['global']['site_href'];

    foreach ($all_banners as $banner) {
        echo <<<TR_BANNER
    <tr>
        <td>{$banner['id']}</td>
        <td><code>{$banner['alias']}</code></td>
        <td>{$banner['url']}</td>
        <td>{$banner['banner_owner']}</td>
        <td>{$banner['hits_all']}</td>
        <td>{$banner['hits_uniq']}</td>
        <td><a href="stats.php?id={$banner['id']}">Details &gt;&gt;&gt;</a></td>
    </tr>
TR_BANNER;
    }
} else {
    echo <<<TR_EMPTY
    <tr>
        <td colspan="7">No data.</td>
    </tr>
TR_EMPTY;

}

?>
    </tbody>
</table>

</body>
</html>
 
