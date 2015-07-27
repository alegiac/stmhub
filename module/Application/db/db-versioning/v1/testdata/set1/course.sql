START TRANSACTION;

INSERT INTO `course` (`id`, `name`, `description`, `periodicityweek`, `weekday_id`, `durationweek`, `insert_date`, `activationstatus_id`, `emailtemplateurl`, `totalexams`) VALUES (1, 'Test course', 'This is the test course', 2, 1, 8, '2015-07-25 23:50:00', 1, '',4);

COMMIT;