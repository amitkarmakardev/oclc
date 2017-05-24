<?php

//Set up mySQL username & password
$mysql_user = 'root';
$mysql_pw = 'secret';
$mysql_host = 'localhost';
$mysql_db = 'oclc';
$table_name = 'book_details';

function insertToDB($book_details)
{
    global $mysql_host, $mysql_user, $mysql_pw, $mysql_db, $table_name;

    try {
        $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_db", $mysql_user, $mysql_pw);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $name_part = '';
        $value_part = '';

        foreach ($book_details as $key => $value) {
            $name_part = $name_part . $key . ",";
            $value_part = $value_part . "'" . $value . "',";
        }

        $name_part = trim($name_part, ",");
        $value_part = trim($value_part, ",");
        $sql = "INSERT INTO {$table_name} (" . $name_part . ") SELECT * FROM (SELECT {$value_part}) AS tmp WHERE NOT EXISTS ( SELECT isbn10 FROM {$table_name} WHERE isbn10 = '{$book_details['isbn10']}' ) LIMIT 1;";
        // $sql = "INSERT INTO {$table_name} (" . $name_part . ") VALUES (" . $value_part . ") WHERE NOT EXISTS (Select isbn10 from {$table_name} where isbn10 = '{$book_details['isbn10']}')";
        $conn->exec($sql);

        echo "New record created successfully";
    } catch (PDOException $e) {
        echo $sql . PHP_EOL . $e->getMessage();
    }

    $conn = null;
}
