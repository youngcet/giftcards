<?php

    include '_inc/sql_queries.inc';

    spl_autoload_register (function ($class_name) {
        // load classes with namespaces in lib
        if (strpos ($class_name, '\\'))
        {
            $class_name = str_replace ("\\", DIRECTORY_SEPARATOR, $class_name);
            include 'lib/'.$class_name . '.php';
        }
        else
        {
            // load sql classes
            include 'lib/MySQL/'.$class_name . '.php';
        }
    });

?>