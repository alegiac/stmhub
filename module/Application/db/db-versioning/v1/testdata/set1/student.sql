START TRANSACTION;

INSERT INTO `student` (`id`, `firstname`, `lastname`, `email`, `passwordsha1`, `identifier`, `activationstatus_id`) 
	VALUES (1,'ALESSANDRO', 'GIACOMELLA', 'a.giacomella@gmail.com', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '12341234', 1);

COMMIT;