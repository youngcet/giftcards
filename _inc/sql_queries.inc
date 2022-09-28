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

    define ('SELECT_ALL_STAFF', 'SELECT * from staff WHERE admin_id = ?');
    define ('SELECT_ALL_EARNINGS', 'SELECT qty, qty_sold, price as "earnings" from giftcards WHERE admin_id = ?');
    define ('SELECT_ALL_GIFTCARDS', 'SELECT COUNT(*) as "totalcards" from giftcards WHERE admin_id = ?');

    define ('SELECT_STAFF_BY_EMAIL_ADMIN', 'SELECT * FROM staff WHERE email = ? AND admin_id = ?');
    define ('INSERT_STAFF_MEMBER', 'INSERT INTO staff (admin_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');
    define ('INSERT_SELLER_MEMBER', 'INSERT INTO seller (staff_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');

    define ('SELECT_ALL_STAFF_GIFTCARDS', 'SELECT qty, qty_sold, price as "earnings" from giftcards WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_GIFTCARDS', 'SELECT * from giftcards WHERE seller_id = ?');
    define ('INSERT_GIFTCARDS_FROM_SELLER', 'INSERT INTO giftcards (seller_id, staff_id, qty) VALUES (?,?,?)');
    define ('UPDATE_GIFTCARDS_FROM_SELLER', 'UPDATE giftcards SET qty = qty + ? WHERE seller_id = ?');
    
    define ('SELECT_ALL_SELLERS', 'SELECT * from seller WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_EARNINGS', 'SELECT qty, qty_sold, price as "earnings" from giftcards WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_GIFTCARDS_TOTAL', 'SELECT qty, qty_sold, price as "earnings" from giftcards WHERE seller_id = ?');

    define ('SELECT_ALL_MY_GIFTCARDS', 'SELECT qty, qty_sold, price as "earnings" from giftcards WHERE seller_id = ?');
    define ('UPDATE_GIFTCARDS_QTY', 'UPDATE giftcards SET qty = qty - 1 WHERE seller_id = ?');
    define ('SELECT_ALL_MY_CREATED_GIFTCARDS', 'SELECT * from seller_giftcards WHERE seller_id = ?');
    define ('INSERT_GIFTCARD', 'INSERT INTO seller_giftcards (seller_id, title, description, price, status) VALUES (?,?,?,?,?)');

    define ('SELL_GIFTCARD', 'UPDATE seller_giftcards SET status = "sold" WHERE id = ?');
    define ('IS_GIFTCARD_SOLD', 'SELECT * FROM seller_giftcards WHERE status = "active" AND id = ?');
    define ('UPDATE_GIFTCARD_TO_SOLD', 'UPDATE giftcards SET price = price + ?, qty_sold = qty_sold + 1 WHERE seller_id = ?');

?>