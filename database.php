<?php

//Set up mySQL username & password
$mysql_user = 'root';
$mysql_pw = 'secret';
$mysql_host = 'localhost';
$mysql_db = 'ggbooks';
$table_name = 'book_details';


function getConnection()
{
    global $mysql_host, $mysql_user, $mysql_pw, $mysql_db, $table_name;

    try {
        $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_user, $mysql_pw);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $sql . PHP_EOL . $e->getMessage();
    }

    return $conn;
}

function executeQuery($conn, $sql)
{
    try {
        $result = $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . PHP_EOL . $e->getMessage();
    }
    $conn = null;
    return $result;
}

function insertToDB($book_details)
{
    global $table_name;
    $sql='';
    $name_part = '';
    $value_part = '';
    $conn = getConnection();
    
    foreach ($book_details as $key => $value) {
        $name_part = $name_part . $key . ",";
        $value_part = $value_part . "'" . $value . "',";
    }
    $name_part = trim($name_part, ",");
    $value_part = trim($value_part, ",");
    $sql = "INSERT INTO {$table_name} (" . $name_part . ") SELECT * FROM (SELECT {$value_part}) AS tmp WHERE NOT EXISTS ( SELECT isbn10 FROM {$table_name} WHERE isbn10 = '{$book_details['isbn10']}' ) LIMIT 1;";
    $result = executeQuery($conn, $sql);
}
