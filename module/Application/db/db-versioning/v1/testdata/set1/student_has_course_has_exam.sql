START TRANSACTION;

INSERT INTO `student_has_course_has_exam` (`id`, `student_has_course_id`, `exam_id`, `insert_date`, `start_date`, `end_date`, `completed`, `points`, `answer`, `token`) 
VALUES (1, 1, 1, '2015-07-27 00:30:00', '2015-07-27 00:30:00', '2015-07-29 00:30:00', 0, 0, NULL, '12j21921dj90d9h39f3');

COMMIT;