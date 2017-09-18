# What is it?



# INSTALL

## Create tables:
```
CREATE TABLE `banner_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` char(32) CHARACTER SET latin1 DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `owner` varchar(16) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `banner_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_banner` int(11) DEFAULT NULL,
  `dayvisit` date DEFAULT NULL,
  `ipv4` int(10) unsigned DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date+ipv4` (`dayvisit`,`ipv4`),
  KEY `ipv4` (`ipv4`),
  KEY `id_banner` (`id_banner`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
```

## Insert initial data

```
INSERT
INTO `banner_list` (`alias`, `url`, `owner`, `created` )
VALUES ('d41d8cd98f00b204e9800998ecf8427e',	'NULL',	'root',	NOW());
```

# Pages

banner.php?id=N
banner-stats.php?id=N


# Stat

Количество хитов на определенную дату:
SELECT SUM(hits) FROM banner_hits WHERE id_banner = ...
Количество уникальных хитов на определенную дату:
SELECT COUNT(ipv4) FROM banner_hits WHERE id_banner = ...

Список баннеров, имеющих хиты

SELECT banner_list.id, banner_list.url, banner_list.owner, SUM(banner_hits.hits), COUNT(banner_hits.ipv4)
FROM banner_hits, banner_list
WHERE
banner_list.id = banner_hits.id_banner
GROUP BY banner_list.id

Список баннеров вообще

SELECT
BL.id AS id,
BL.url AS url,
BL.owner AS banner_owner,
SUM(BH.hits) AS hits_all,
COUNT(BH.ipv4) AS hits_uniq

FROM banner_hits BH
RIGHT JOIN banner_list BL
ON BL.id = BH.id_banner

GROUP BY BL.id





