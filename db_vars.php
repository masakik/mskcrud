<?php

if (strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== FALSE) {
    define("HOST", "localhost");
    define("USER", "root");
    define("PWD", "");
    define("nome_db", "torneio_db");
}

?>