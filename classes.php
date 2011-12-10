<?php
include_once('Template.class.php');

class meuTemplate extends Template
{
    public function __construct($filename, $accurate = false)
    {
        parent::__construct($filename, $accurate);

        // inclui js
        $this->addFile('mskCrudJs', 'static/mskcrud.js');
        if (isset($_SESSION['msg'])) {
            $this->msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        else $this->msg = '&nbsp;';
    }

    function printVetor($vetor, $campo, $bloco)
    {
        foreach ($vetor as $v) {
            $this->$campo = $v;
            $this->block($bloco);
        }
    }

    function printArray($array, $bloco)
    {
        foreach ($array as $row) {
            //  print_r($row);
            foreach ($row as $f => $v) {
                $this->$f = $v;
            }
            $this->block($bloco);
        }
    }
}


class meuPDO
{
    function __construct($configure = '')
    {
        $this->engine = new $_SESSION['db_vars']['engine']();
        if ($configure = 'configure') {
            $this->configure();
        }
    }

    function configure()
    {
        unset($_SESSION['tbl']);
        $_SESSION['tbl']['name'] = $_SESSION['config']['lastTb'];
        $_SESSION['tbl']['cols'] = '';
        $_SESSION['tbl']['rows'] = '';
        $_SESSION['tbl']['idx'] = '';
    }

    function campos()
    {
        $ans = $this->engine->campos();
        $this->ordem = $this->engine->ordem; // inicialmente ordena pelo 1o campo da tabela
        $this->idx = $this->engine->idx;
        return $ans;
    }

    function dados()
    {
        return $this->engine->dados();
    }

    function insert($dados)
    {
        return $this->engine->insert($dados);
    }

    function deleteById($dados)
    {
        return $this->engine->deleteById($dados);
    }

    function updateById($dados)
    {
        return $this->engine->updateById($dados);
    }
}

class mysql
{
    function __construct()
    {
        $db = $_SESSION['db_vars'];
        $link = mysql_connect($db['host'], $db['usuario'], $db['senha']);
        if (!$link) die('Could not connect: ' . mysql_error());
        if (!mysql_select_db($db['db'])) {
            die('DB não selecionado!');
        }
        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
        //$this->db = $db['db'];
    }

    function select($q)
    {
        $res = mysql_query($q);
        $this->nr = mysql_num_rows($res);
        if ($this->nr > 0) {
            for ($i = 0; $i < $this->nr; $i++) {
                $l = mysql_fetch_assoc($res);
                foreach ($l as $key => $field) {
                    $ans[$i][$key] = $field;
                }
            }
        }
        return $ans;
    }

    function tabelas()
    {
        $res = mysql_query('show tables from ' . $_SESSION['db_vars']['db']);
        $ans = false;
        if (mysql_num_rows($res) > 0) {
            while ($lin = mysql_fetch_row($res)) {
                $ans[] = $lin[0];
            }
        }
        return $ans;
    }

    function campos()
    {
        $res = mysql_query('show fields from ' . $_SESSION['config']['lastTb']);
        if (mysql_num_rows($res) > 0) {
            while ($lin = mysql_fetch_row($res)) {
                $ans[] = $lin[0];
            }
        }
        $this->ordem = $ans[0]; // inicialmente ordena pelo 1o campo da tabela
        $this->idx = $ans[0];
        return $ans;
    }

    function dados()
    {
        $res = mysql_query('select * from `' . $_SESSION['config']['lastTb'] . '`;');
        $ans = false;
        if (mysql_num_rows($res) > 0) {
            for ($i = 0; $i < mysql_num_rows($res); $i++) {
                $lin = mysql_fetch_assoc($res);
                foreach ($lin as $key => $val)
                    $ans[$i][$key] = $val;
            }
        }
        return $ans;
    }

    function insert($dados)
    {
        $tbl = $dados['tbl'];
        unset($dados['tbl']);
        $idx = $dados['idx'];
        unset($dados['idx']);
        unset($dados[$idx]);

        $campo = $valor = '';
        foreach ($dados as $c => $v) {
            $campo .= '`' . $c . '`,';
            $valor .= '\'' . $v . '\',';
        }
        $campo = substr($campo, 0, -1);
        $valor = substr($valor, 0, -1);
        $q = sprintf('insert into `%s` (`%s`, %s) values (\'NULL\', %s);', $tbl, $idx, $campo, $valor);
        if (!mysql_query($q)) die('Insert ' . mysql_error());
        return true;
    }

    function deleteById($dados)
    {
        $tbl = $dados['tbl'];
        unset($dados['tbl']);
        $idx = $dados['idx'];
        unset($dados['idx']);
        $idx_val = $dados[$idx];
        unset($dados[$idx]);

        $q = sprintf('delete from `%s` where `%s`=\'%s\';', $tbl, $idx, $idx_val);
        if (!mysql_query($q)) die('Delete ' . mysql_error());
        return true;
    }

    function updateById($dados)
    {
        $tbl = $dados['tbl'];
        unset($dados['tbl']);
        $idx = $dados['idx'];
        unset($dados['idx']);
        $idx_val = $dados[$idx];
        unset($dados[$idx]);

        $campo = '';
        foreach ($dados as $c => $v)
            $campo .= '`' . $c . '`=\'' . $v . '\',';
        $campo = substr($campo, 0, -1);

        $q = sprintf('update `%s` set %s where `%s`=\'%s\';', $tbl, $campo, $idx, $idx_val);
        if (!mysql_query($q)) die('Update ' . mysql_error());
        return true;
    }
}

class sqlite
{
    function __construct()
    {

    }
}

class Db
{
    function __construct($what)
    {
        if ((!isset($_SESSION['config']['firstRun']) or $_SESSION['config']['firstRun'] == true) and $what == 'config') {
            $this->testServerConfig();
            $this->openConfig();
            $this->load('config');
            $this->load('lastDb');
            $_SESSION['config']['firstRun'] = 0;
        }
    }

    private function testServerConfig()
    {
        // if (!isset($_SESSION['config']['firstRun']) or $_SESSION['config']['firstRun'] == true) {
        // aqui verifica o servidor sobre compatibilidade

        // depois de completado não roda até a proxima sessão
        $_SESSION['config']['firstRun'] = 0;
        // }
    }

    private function openConfig()
    {
        if ($_SESSION['debug'] >= 3) echo 'Config file on openConfig: ' . $_SESSION['config']['file'];
        $db = new SQLiteDatabase($_SESSION['config']['file'], 0666, $error);
        if (!$db) die('Erro sqlite: ' . $error);

        // cria tabelas se necessario
        $q = $db->query("SELECT name FROM sqlite_master WHERE type='table' and name='servidores';");
        if ($q->numRows() == 0) {
            // para sqlite, http_host será o nome do arquivo. Cada arquivo tem 1 banco de dados
            $q = 'CREATE TABLE servidores (
                  id INTEGER PRIMARY KEY,
                  servidor varchar(50),
                  host varchar(50),
                  porta int,
                  usuario varchar(20),
                  senha varchar(50),
                  db varchar(50),
                  engine varchar(20)
                  );';
            $db->queryExec($q, $error);
            $q = "CREATE TABLE config (id integer primary key,dados varchar(500))";
            $db->queryExec($q, $error);
            $db->queryExec(sprintf("insert into config (id,dados) values (1,'%s');", serialize($_SESSION['config'])), $error);
            exit;
        }
    }

// salva as configurações da sessão no banco
    function save($what)
    {
        if ($what == 'config') {
            $db = new SQLiteDatabase($_SESSION['config']['file'], 0666, $error);
            $db->queryExec(sprintf("update config set dados='%s' where id=1", serialize($_SESSION['config'])));
        }
    }

    function load($what)
    {
        $db = new SQLiteDatabase($_SESSION['config']['file'], 0666, $error);
        if ($what == 'config') {
            $q = $db->unbufferedQuery("select dados from config where id=1");
            $_SESSION['config'] = unserialize($q->fetchSingle());
        }
        if ($what == 'lastDb') {
            $q = $db->unbufferedQuery(sprintf("select * from servidores where servidor='%s' and id=%u",
                                              $_SERVER['HTTP_HOST'], $_SESSION['config']['lastDb']));
            $ans = $q->fetchAll(SQLITE_ASSOC);
            if ($ans == array()) {
                echo 'bd vazio';
                exit;
            }
            $_SESSION['db_vars'] = $ans[0];
        }
    }

    function dbs()
    {
        $db = new SQLiteDatabase($_SESSION['config']['file'], 0666, $error);
        $q = $db->unbufferedQuery(sprintf("select id,db,host from servidores where servidor='%s'", $_SERVER['HTTP_HOST']));
        $ans = $q->fetchAll(SQLITE_ASSOC);
        //    print_r($ans);exit;
        return $ans;
    }

    function tabelas()
    {
        $engine = new $_SESSION['db_vars']['engine']();
        return $engine->tabelas();
    }

    function saveDb()
    {
        $_SESSION['db_vars']['id'] = 1;
        $_SESSION['db_vars']['engine'] = 'mysql';
        $_SESSION['db_vars']['servidor'] = $_SERVER['HTTP_HOST'];
        $_SESSION['db_vars']['porta'] = '';
        $_SESSION['db_vars']['host'] = 'localhost';
        $_SESSION['db_vars']['usuario'] = 'root';
        $_SESSION['db_vars']['senha'] = '';
        $_SESSION['db_vars']['db'] = 'torneio_db';

        $db = new SQLiteDatabase($_SESSION['config']['file'], 0666, $error);
        $q = sprintf("insert into servidores (id, servidor, host, porta, usuario, senha, db, engine)
                      values (null, '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                     $_SESSION['db_vars']['servidor'],
                     $_SESSION['db_vars']['host'],
                     $_SESSION['db_vars']['porta'],
                     $_SESSION['db_vars']['usuario'],
                     $_SESSION['db_vars']['senha'],
                     $_SESSION['db_vars']['db'],
                     $_SESSION['db_vars']['engine']);
        $db->queryExec($q);
    }

    function setDb($id)
    {
        $db = new SQLiteDatabase($_SESSION['config']['file'], 0666, $error);
        $q = $db->unbufferedQuery(sprintf("select * from servidores where servidor='%s' and id=%u",
                                          $_SERVER['HTTP_HOST'], $id));
        $ans = $q->fetchAll(SQLITE_ASSOC);
        $_SESSION['db_vars'] = $ans[0];
        $_SESSION['config'][lastDb] = $id;
    }
}

?>