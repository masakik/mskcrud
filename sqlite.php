<?php
class meuPDO extends PDO
{
    function __construct($str)
    {
        try { //create or open the database
            parent::__construct($str);
        }
        catch (Exception $e) {
            die($error);
        }
    }
}

//echo SQLite3::version();
//echo pdo_drivers();

$database = new meuPDO('sqlite:myDatabase.sqlite');

//echo 'Versão sqlite: ' . sqlite_libversion();
//echo 'Versão php: ' . phpversion();


$q = "SELECT name FROM sqlite_master WHERE type = 'table';";
//print_r($database->query($q)->fetch());exit;
if ($database->query($q)->fetch() == '') {
    echo "Criando tabelas ...";
    $q = 'CREATE TABLE Movies ' .
         '(Title TEXT, Director TEXT, Year INTEGER)';
    $database->exec($q);
    $query =
            'INSERT INTO Movies (Title, Director, Year) ' .
            'VALUES ("The Dark Knight", "Christopher Nolan", 2008); ' .

            'INSERT INTO Movies (Title, Director, Year) ' .
            'VALUES ("Cloverfield", "Matt Reeves", 2008); ' .

            'INSERT INTO Movies (Title, Director, YEAR) ' .
            'VALUES ("Beverly Hills Chihuahua", "Raja Gosnell", 2008)';
    $database->exec($query);
    echo 'Ok.';
}

//read data from database
$q = "SELECT * FROM Movies";
foreach ($database->query($q) as $row) {
    print("Title: {$row['Title']} <br />" .
          "Director: {$row['Director']} <br />" .
          "Year: {$row['Year']} <br /><br />");
}



?>