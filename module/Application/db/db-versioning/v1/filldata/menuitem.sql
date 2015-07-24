START TRANSACTION;

INSERT INTO `menuitem` (`id`, `name`, `iconurl`, `url`, `menustatus_id`, `page_id`, `menuitem_id`, `menutype_id`) VALUES (DEFAULT, 'Dashboard', NULL, '/dashboard_default', 1, 1, NULL, 2);
INSERT INTO `menuitem` (`id`, `name`, `iconurl`, `url`, `menustatus_id`, `page_id`, `menuitem_id`, `menutype_id`) VALUES (DEFAULT, 'Revenue', NULL, '/dashboard_revenue', 1, 2, NULL, 2);

COMMIT;