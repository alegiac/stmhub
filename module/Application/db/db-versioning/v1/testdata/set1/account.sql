START TRANSACTION;

INSERT INTO `account` (`id`, `client_id`, `username`, `passwordsha1`, `insert_date`, `last_access`, `activationstatus_id`) VALUES (1, 1, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','2015-07-25 23:59:59', '2015-07-26 00:00:00', 1);

COMMIT;