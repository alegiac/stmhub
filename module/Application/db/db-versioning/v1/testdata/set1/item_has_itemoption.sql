START TRANSACTION;

INSERT INTO `item_has_itemoption` (`item_id`, `itemoption_id`) VALUES (1,1);
INSERT INTO `item_has_itemoption` (`item_id`, `itemoption_id`) VALUES (1,2);
INSERT INTO `item_has_itemoption` (`item_id`, `itemoption_id`) VALUES (1,3);

COMMIT;