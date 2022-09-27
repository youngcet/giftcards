<?php

    define ('CHECK_IF_EMAIL_EXISTS', 'SELECT 1 FROM ( SELECT email FROM admin UNION ALL SELECT email FROM staff UNION ALL SELECT email from SELLER ) a WHERE email = ?');
    define ('CHECK_USER', 'SELECT userid,email,fname,lname,role FROM ( SELECT id as "userid",email,pwd,fname,lname,role FROM admin UNION ALL SELECT admin_id as "userid", email,pwd,fname,lname,role FROM staff UNION ALL SELECT staff_id as "userid", email,pwd,fname,lname,role from SELLER ) a WHERE email = ? AND pwd = ?');
    
    // admin sql queries
    define ('SELECT_ADMIN_USER_BY_EMAIL_PWD', 'SELECT * FROM admin WHERE email = ? AND pwd = ?');
    define ('SELECT_ADMIN_USER_BY_ID', 'SELECT * FROM admin WHERE id = ?');
    define ('SELECT_ADMIN_USER_BY_EMAIL', 'SELECT * FROM admin WHERE email = ?');
    define ('INSERT_ADMIN_USER', 'INSERT INTO admin (fname, lname, email, pwd) VALUES (?,?,?,?)');

    define ('SELECT_ALL_STAFF', 'SELECT * from staff WHERE admin_id = ?');
    define ('SELECT_ALL_EARNINGS', 'SELECT SUM(price) as "earnings" from giftcards WHERE admin_id = ?');
    define ('SELECT_ALL_GIFTCARDS', 'SELECT COUNT(*) as "totalcards" from giftcards WHERE admin_id = ?');

    define ('SELECT_STAFF_BY_EMAIL_ADMIN', 'SELECT * FROM staff WHERE email = ? AND admin_id = ?');
    define ('INSERT_STAFF_MEMBER', 'INSERT INTO staff (admin_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');
    define ('INSERT_SELLER_MEMBER', 'INSERT INTO seller (staff_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');

    define ('SELECT_ALL_STAFF_GIFTCARDS', 'SELECT qty, qty_sold from giftcards WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_GIFTCARDS', 'SELECT * from giftcards WHERE seller_id = ?');
    define ('INSERT_GIFTCARDS_FROM_SELLER', 'INSERT INTO giftcards (seller_id, staff_id, qty) VALUES (?,?,?)');
    define ('UPDATE_GIFTCARDS_FROM_SELLER', 'UPDATE giftcards SET qty = qty + ? WHERE seller_id = ?');
    

    define ('SELECT_ALL_SELLERS', 'SELECT * from seller WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_EARNINGS', 'SELECT SUM(price) as "earnings" from giftcards WHERE staff_id = ?');
    define ('SELECT_ALL_SELLER_GIFTCARDS_TOTAL', 'SELECT qty from giftcards WHERE seller_id = ?');

?>