<?php
class meuPDO extends PDO
{
    function __construct($str)
    {
        try { //create or open the database
            parent::__construct($str);
        }
        catch (Exception $e) {
            die('Sqlite database create: ' . $error);
        }
        $q = "SELECT name FROM sqlite_master WHERE type = 'table';";
        if ($this->query($q)->fetch() == '') {
            //echo "Criando tabelas ...";
            $q = "CREATE TABLE servidores (id int PRIMARY KEY, http_host varchar(50), engine varchar(50), servidor varchar(50), porta int, usuario varchar(20), senha varchar(50), db varchar(50));";
            $this->exec($q);
        }
    }

    function databases()
    {
        $q = sprintf("select * from servidores where http_host='%s';", $_SERVER['HTTP_HOST']);
        $l = $this->query($q)->fetchAll(PDO::FETCH_ASSOC);
        return $l;
    }

    function insertDb()
    {
        $q = sprintf("insert into servidores (id, http_host, engine, servidor, porta, usuario, senha, db) values (NULL,'%s','%s','%s','%s','%s','%s');",
                     $_SERVER['HTTP_HOST'], 'mysql', 'localhost', '', 'root', '', 'torneio_db');
        $stmt = $this->exec($q);
    }
}

$db = new meuPDO('sqlite:db_config.db3');

$d = $db->databases();
//$db->insertDb();
print_r($d);
exit;


$q = sprintf("select * from servidores where http_host='%s' limit 2;", $_SERVER['HTTP_HOST']);
$l = $database->query($q)->fetchAll(PDO::FETCH_ASSOC);
if (count($l) == 0) { // aqui deve chamar form para cadastrar dados
    $q = sprintf("insert into servidores (id, http_host, servidor, porta, usuario, senha, db) values (NULL,'%s','%s','%s','%s','%s','%s');",
                 $_SERVER['HTTP_HOST'], 'localhost', '', 'root', '', 'torneio_db');
    $stmt = $database->exec($q);
}
if (count($l) > 1) { // deve entrar numa rotiha para gerenciar bases
    echo "mais de um registro";
}
print_r($l);
exit;
//read data from database
$q = "SELECT * FROM databases";
//$res = $database->query($q);
//var_dump($res);exit;
foreach ($database->query($q) as $row) {
    echo $row['http_host'] . "<br />";
    echo $row['servidor'] . "<br />";
    /*    foreach ($row as $key=>$val)
echo "$key => $val<br />";*/
    echo "ok";
}



?>