START TRANSACTION;

INSERT INTO `answer` (`id`, `value`, `points`, `correct`, `expectedpos`) VALUES (1,'Volant', 50, 0, NULL);
INSERT INTO `answer` (`id`, `value`, `points`, `correct`, `expectedpos`) VALUES (2,'Flounce', 100, 0, NULL);
INSERT INTO `answer` (`id`, `value`, `points`, `correct`, `expectedpos`) VALUES (3,'Ball', 0, 0, NULL);

INSERT INTO `answer` (`id`, `value`, `points`, `correct`, `expectedpos`) VALUES (4,'Vero', 0, 0, NULL);
COMMIT;