START TRANSACTION;

INSERT INTO `page_has_widget` (`page_id`, `widget_id`,`widgetsize_id`,`position`) VALUES (1,1,1,1);
INSERT INTO `page_has_widget` (`page_id`, `widget_id`,`widgetsize_id`,`position`) VALUES (1,2,3,3);

INSERT INTO `page_has_widget` (`page_id`, `widget_id`,`widgetsize_id`,`position`) VALUES (2,1,1,1);
INSERT INTO `page_has_widget` (`page_id`, `widget_id`,`widgetsize_id`,`position`) VALUES (2,2,3,3);

COMMIT;