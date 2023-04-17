<?php

    define ('CHECK_IF_EMAIL_EXISTS', 'SELECT 1 FROM ( SELECT email FROM admin UNION ALL SELECT email FROM staff UNION ALL SELECT email from SELLER ) a WHERE email = ?');
    define ('CHECK_USER', 'SELECT userid,email,fname,lname,role FROM ( SELECT id as "userid",email,pwd,fname,lname,role FROM admin UNION ALL SELECT id as "userid", email,pwd,fname,lname,role FROM staff UNION ALL SELECT id as "userid", email,pwd,fname,lname,role from SELLER ) a WHERE email = ? AND pwd = ?');
    define ('UPDATE_STAFF_PWD', 'UPDATE staff SET pwd = ? WHERE id = ?');
    define ('UPDATE_ADMIN_PWD', 'UPDATE admin SET pwd = ? WHERE id = ?');
    define ('UPDATE_SELLER_PWD', 'UPDATE seller SET pwd = ? WHERE id = ?');

    // admin sql queries
    define ('SELECT_ADMIN_USER_BY_EMAIL_PWD', 'SELECT * FROM admin WHERE email = ? AND pwd = ?');
    define ('SELECT_ADMIN_USER_BY_ID', 'SELECT * FROM admin WHERE id = ?');
    define ('SELECT_ADMIN_USER_BY_EMAIL', 'SELECT * FROM admin WHERE email = ?');
    define ('INSERT_ADMIN_USER', 'INSERT INTO admin (fname, lname, email, pwd) VALUES (?,?,?,?)');
    define ('UPDATE_ADMIN_PROFILE_IMG', 'UPDATE admin SET profile_img = ? WHERE id = ?');

    define ('SELECT_ALL_STAFF', 'SELECT * from staff WHERE admin_id = ?');
    define ('SELECT_ALL_EARNINGS', 'SELECT SUM(qty) as qty, SUM(qty_sold) as qty_sold, SUM(price*qty_sold) as "earnings" from giftcards WHERE admin_id = ?');
    define ('SELECT_ALL_GIFTCARDS', 'SELECT COUNT(*) as "totalcards" from giftcards WHERE admin_id = ?');
    define ('UPDATE_STAFF_PROFILE_IMG', 'UPDATE staff SET profile_img = ? WHERE id = ?');
    define ('SELECT_STAFF', 'SELECT * FROM staff WHERE id = ?');

    define ('SELECT_STAFF_BY_EMAIL_ADMIN', 'SELECT * FROM staff WHERE email = ? AND admin_id = ?');
    define ('INSERT_STAFF_MEMBER', 'INSERT INTO staff (admin_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');
    define ('INSERT_SELLER_MEMBER', 'INSERT INTO seller (staff_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');

    define ('SELECT_ALL_STAFF_GIFTCARDS', 'SELECT SUM(qty) as qty, SUM(qty_sold) as qty_sold, SUM(price*qty_sold) as "earnings" FROM giftcards WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_GIFTCARDS', 'SELECT * from giftcards WHERE seller_id = ?');
    define ('INSERT_GIFTCARDS_FROM_SELLER', 'INSERT INTO giftcards (seller_id, staff_id, qty) VALUES (?,?,?)');
    define ('UPDATE_GIFTCARDS_FROM_SELLER', 'UPDATE giftcards SET qty = qty + ? WHERE seller_id = ?');
    define ('SELECT_ALL_CARDS_FOR_GUESTS', 'SELECT * FROM giftcards WHERE status != "unfreeze"');
    
    define ('SELECT_ALL_SELLERS', 'SELECT * from seller WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_EARNINGS', 'SELECT SUM(qty) as qty, SUM(qty_sold) as qty_sold, SUM(price*qty_sold) as "earnings" from giftcards WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_GIFTCARDS_TOTAL', 'SELECT qty, qty_sold, price as "earnings" from giftcards WHERE seller_id = ?');
    define ('UPDATE_SELLER_PROFILE_IMG', 'UPDATE seller SET profile_img = ? WHERE id = ?');

    define ('SELECT_ALL_MY_GIFTCARDS', 'SELECT SUM(qty) as qty, SUM(qty_sold) as qty_sold, SUM(price*qty_sold) as "earnings" from giftcards WHERE seller_id = ?');
    define ('UPDATE_GIFTCARDS_QTY', 'UPDATE giftcards SET qty = qty - 1 WHERE seller_id = ?');
    define ('SELECT_ALL_MY_CREATED_GIFTCARDS', 'SELECT * from giftcards WHERE seller_id = ? AND status != "unfreeze"');
    define ('INSERT_GIFTCARD', 'INSERT INTO seller_giftcards (seller_id, title, description, price, status) VALUES (?,?,?,?,?)');

    define ('SELL_GIFTCARD', 'UPDATE giftcards SET qty_sold = ? WHERE id = ?');
    define ('REDEEM_MAIN_GIFTCARD', 'UPDATE giftcards SET qty_sold = qty_sold + ?, qty = qty - ? WHERE card_number = ? AND seller_id = ?');
    define ('SELECT_GIFTCARD', 'SELECT * FROM giftcards WHERE id = ?');
    define ('SELECT_GIFTCARD_BY_CARDNO', 'SELECT * FROM giftcards WHERE card_number = ?');
    define ('IS_GIFTCARD_SOLD', 'SELECT * FROM seller_giftcards WHERE status = "active" AND id = ?');
    define ('UPDATE_GIFTCARD_TO_SOLD', 'UPDATE giftcards SET price = price + ?, qty_sold = qty_sold + 1 WHERE seller_id = ?');

    define ('SELECT_ALL_GIFTCARDS_BY_STAFFID', 'SELECT * from giftcards WHERE staff_id = ?');
    define ('INSERT_STAFF_GIFTCARD', 'INSERT INTO giftcards (staff_id, seller_id, title, description, price, qty, status, color, card_number, expiry_date) VALUES (?,?,?,?,?,?,?,?,?,?)');
    define ('SELECT_SELLER', 'SELECT * FROM seller WHERE id = ?');
    define ('UPDATE_GITFTCARD_ASSIGNEE', 'UPDATE giftcards SET seller_id = ? WHERE id = ?');
    define ('SELECT_GIRDCARD_BY_CARDNO', 'SELECT card_number FROM giftcards WHERE card_number = ?');
    define ('SELECT_MAIN_GIRDCARD_BY_CARDNO', 'SELECT * FROM giftcards WHERE seller_id = ? AND card_number = ?');
    define ('UPDATE_MAIN_GITFTCARD_STATUS', 'UPDATE giftcards SET status = ? WHERE card_number = ?');
    define ('UPDATE_MAIN_GIFTCARD_CARDNO', 'UPDATE giftcards SET card_number = ? WHERE id = ? AND seller_id = ?');

    define ('INSERT_SELLER_GIFTCARD', 'INSERT INTO seller_giftcards (card_id, seller_id, card_number, title, description, price, status, expiry_date) VALUES (?,?,?,?,?,?,?,?)');
    define ('SELECT_SELLER_GIFTCARDS', 'SELECT * FROM seller_giftcards WHERE card_id = ? AND seller_id = ?');
    define ('SELECT_NON_REDEEMED_CARDS', 'SELECT * FROM seller_giftcards WHERE card_id = ? AND seller_id = ? AND status = "active"');
    define ('CHECK_IF_GIFTCARD_NUMBER_EXISTS', 'SELECT id FROM seller_giftcards WHERE card_id = ? AND card_number = ? AND seller_id = ?');
    define ('UPDATE_GIFTCARD_CARDNO', 'UPDATE seller_giftcards SET card_number = ? WHERE id = ? AND seller_id = ?');
    define ('UPDATE_SELLER_MAIN_GIFTCARDNO', 'UPDATE seller_giftcards SET card_id = ? WHERE card_id = ? AND seller_id = ?');
    define ('REDEEM_CARD', 'UPDATE seller_giftcards SET status = "redeemed" WHERE id = ? AND seller_id = ?');
    define ('UPDATE_GITFTCARD_STATUS', 'UPDATE seller_giftcards SET status = ? WHERE card_id = ? AND seller_id = ?');

    define ('INSERT_NOTIFICATION', 'INSERT INTO notifications (user_id, title, text, profile_img) VALUES (?,?,?,?)');
    define ('INSERT_REQUEST_NOTIFICATION', 'INSERT INTO notifications (user_id, title, text, email, card_number, profile_img) VALUES (?,?,?,?,?,?)');
    define ('SELECT_NOTIFICATIONS', 'SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 order by created desc limit 10');
    define ('SELECT_NOTIFICATIONS_BY_ID', 'SELECT * FROM notifications WHERE id = ?');
    define ('SELECT_READ_NOTIFICATIONS', 'SELECT * FROM notifications WHERE user_id = ? AND is_read = 1 order by created desc limit 10');
    define ('DELETE_NOTIFICATION', 'DELETE FROM notifications WHERE user_id = ?'); 
    define ('MARK_AS_READ', 'UPDATE notifications SET is_read = 1 WHERE id = ?');
    define ('MARK_All_AS_READ', 'UPDATE notifications SET is_read = 1 WHERE user_id = ?');

    define ('INSERT_REQUEST', 'INSERT INTO guestrequests (seller_id, card_number, guestname, email, data) VALUES (?,?,?,?,?)');
    define ('SELECT_GUEST_CARDS', 'SELECT * FROM guestrequests WHERE seller_id = ?');

    define ('DELETE_STAFF', 'DELETE FROM staff WHERE id = ?'); 
    define ('DELETE_SELLER', 'DELETE FROM seller WHERE id = ?');
    define ('DELETE_GIFTCARD', 'DELETE FROM giftcards WHERE card_number = ?');
    define ('DELETE_SELLER_GIFTCARDS', 'DELETE FROM seller_giftcards WHERE card_id = ?');

?>