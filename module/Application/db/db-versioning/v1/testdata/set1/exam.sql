START TRANSACTION;

INSERT INTO `exam` (`id`, `name`, `description`, `imageurl`, `points_if_completed`, `reduce_percentage_outtime`, `course_id`, `insert_date`, `mandatory`, `progressive`, `totalitems`) VALUES (1, 'First exam of the course', 'This is the first exam session of the course', '', 100, 30, 1, '2015-07-26 13:39:00', 1, 1, 1);

COMMIT;