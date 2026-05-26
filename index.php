<?php
    header('Content-Type: application/json; charset=UTF-8');

    $dsn = "mysql:host=localhost;dbname=stepra_db;charset=utf8";
    $user = 'root';
    $password = '1201';

    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'select * from users where 1';

    $stmt = $dbh -> prepare($sql);
    $stmt -> execute();

    while(true) {
        $rec = $stmt -> fetch(PDO::FETCH_ASSOC);

        if ($rec === false) {
            break;
        }
        $arr[] = $rec;
    }

    print json_encode($arr, JSON_PRETTY_PRINT);

?>
