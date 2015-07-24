BEGIN ;

INSERT INTO `widget` VALUES (1,'avgrevbook',2,'{\"title\": \"Average revenue\",\"text\": \"Bookings\",\"config\":{\"events\":{\"reload\":[\"ev.general.filter.change.checkout.date\",\"ev.general.filter.change.checkin.date\",\"ev.general.filter.change.opened.date\"]}}}','{}','{}');
INSERT INTO `widget` VALUES (2,'totrevxpaxseg',1,'{\"title\": \"Total Revenue x Pax Segment\",\"config\":{\"events\":{\"reload\":[\"ev.general.filter.change.checkout.date\",\"ev.general.filter.change.checkin.date\",\"ev.general.filter.change.opened.date\"]}}}','{\"options\":\"stacked_bars\"}','{}'),(3,'totrevxresmon3ys',1,'{\"title\": \"Total Revenue x Reservation Month - 3 Years Statement\",\"config\":{\"events\":{\"reload\":[\"ev.general.filter.change.checkout.date\",\"ev.general.filter.change.checkin.date\",\"ev.general.filter.change.opened.date\"]}}}','{\"options\":\"stacked_bars\"}','{}');

COMMIT;
