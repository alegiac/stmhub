START TRANSACTION;

INSERT INTO `client` (`id`,`businessname`, `address`, `zip`, `city`, `area`, `country`, `phone`, `email`, `fax`, `vatnumber`, `fiscode`, `insert_date`, `activationstatus_id`, `clientconfiguration_id`) VALUES (2,'test businessname', 'test address road', 'LW1 3C2', 'London', 'Greater London', 'Uk', '+44 111 1111111','a.giacomella@test.com', NULL, 'AA0000001', NULL, '2015-07-25 23:50:00', 1, 1);

COMMIT;