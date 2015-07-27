START TRANSACTION;

INSERT INTO `item` (`id`, `question`, `itemtype_id`, `maxtries`, `maxsecs`, `context`, `item_id`) VALUES (1, 'La corretta traduzione di "balza" e\'...', 1, 2, 200, 'Il termine corretto è flounce, anche se volant, di derivazione francese, è comunque accettato.', NULL);
INSERT INTO `item` (`id`, `question`, `itemtype_id`, `maxtries`, `maxsecs`, `context`, `item_id`) VALUES (2, 'Il termine "collar" indica esclusivamente il collare per cani', 2, 1, 200, 'FALSO: Collar, nell\'ambito fashion, indica il colletto, il bavero, negli indumenti.',NULL);
INSERT INTO `item` (`id`, `question`, `itemtype_id`, `maxtries`, `maxsecs`, `context`, `item_id`) VALUES (3, 'Metti in ordine i seguenti vocaboli partendo dall’indumento che si indossa più in alto e finendo con quello che si indossa più in basso:', 3, 1, 200, 'L\'ordine giusto è Balaclava (passamontagna) in testa, Glove (guanti), Carter (giarrettiera)',NULL);

COMMIT;