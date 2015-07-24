START TRANSACTION;

DELETE FROM `account_has_brand_has_page_has_widget`;
DELETE FROM `brand_has_page_has_widget`;
DELETE FROM `page_has_widget`;
DELETE FROM `widget`;
DELETE FROM `widgetsize`;
DELETE FROM `widgettype`;
DELETE FROM `account_has_menuitem`;
DELETE FROM `accountmenustatus`;
DELETE FROM `account_has_menuitem`;
DELETE FROM `menuitem`;
DELETE FROM `menustatus`;
DELETE FROM `menutype`;
DELETE FROM `page`;

COMMIT;