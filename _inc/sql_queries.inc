<?php

    // admin sql queries
    define ('SELECT_ADMIN_USER_BY_EMAIL_PWD', 'SELECT * FROM admin WHERE email = ? AND pwd = ?');
    define ('SELECT_ADMIN_USER_BY_ID', 'SELECT * FROM admin WHERE id = ?');
    define ('SELECT_ADMIN_USER_BY_EMAIL', 'SELECT * FROM admin WHERE email = ?');
    define ('INSERT_ADMIN_USER', 'INSERT INTO admin (fname, lname, email, pwd) VALUES (?,?,?,?)');

    define ('SELECT_ALL_STAFF', 'SELECT * from staff WHERE admin_id = ?');
    define ('SELECT_ALL_EARNINGS', 'SELECT SUM(price) as earnings from giftcards WHERE admin_id = ?');
    define ('SELECT_ALL_GIFTCARDS', 'SELECT COUNT(*) as totalcards from giftcards WHERE admin_id = ?');

    define ('SELECT_STAFF_BY_EMAIL_ADMIN', 'SELECT * FROM staff WHERE email = ? AND admin_id = ?');
    define ('INSERT_STAFF_MEMBER', 'INSERT INTO staff (admin_id, fname, lname, email, pwd) VALUES (?,?,?,?,?)');
?>