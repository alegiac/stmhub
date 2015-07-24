START TRANSACTION;

INSERT INTO `page` (`id`, `code`, `template`,`structure`) VALUES (1, 'dashboard_default', 'dashboard','{"title":"TITOLO","subtitle":"SOTTOTITOLO"}');
INSERT INTO `page` (`id`, `code`, `template`,`structure`) VALUES (2, 'dashboard_revenue', 'dashboard','{"title":"TITOLO","subtitle":"SOTTOTITOLO"}');

COMMIT;