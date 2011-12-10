<?php
session_start();
$_SESSION['debug'] = 0;
// debug 3 é que mostra tudo.
include_once('classes.php');

//include_once('db_vars.php');
$_SESSION['config']['file'] = 'db_config.db2';

$config = new Db('config');
if ($_SESSION['debug'] >= 3) {
    echo 'Config load done.<pre>';
    print_r($_SESSION);
    echo '</pre>';
}
// depois que configurou pode carregar as ações
include_once('acao.php');

// começa o template de saída --------------------------------------------------------------------------------
$tpl = new meuTemplate('index.tpl');
$tpl->titulo = 'MsKCRUD';
// --------------------------------
// carrega a janela de add já no inicio se setado para 1
$tpl->add_btn = '0';
// --------------------------------

// menu --------------------------------------------------------------------------
$tpl->db = $_SESSION['db_vars']['db'];
$tpl->printArray($config->dbs(), 'db_loop');

$tpl->printVetor($config->tabelas(), 'tabela', 'tabelas_loop');
if ($acao == 'tblh') {
    $tpl->tabela = $_SESSION['config']['lastTb'] = $_GET['t'];
}
else
    $tpl->tabela = 'Selecione tabela';

$tpl->block('menu');


if ($acao == 'tblh') { // tabelas na horizontal ------------------------------------

    $tpl->tabela = $_SESSION['config']['lastTb'] = $_GET['t'];
    $tb = new meuPDO('configure');
    // cabecalho
    $campos = $tb->campos();
    $tpl->numCampos = count($campos);
    $tpl->printVetor($campos, 'campo', 'tabela_campos');

    // conteudo
    $dados = $tb->dados();
    $tpl->numLinhas = count($dados);
    foreach ($dados as $linha) {
        foreach ($linha as $campo => $valor) {
            $tpl->campo = $campo;
            $tpl->valor = $valor;
            $tpl->block('tabela_content_campo');
            if ($tb->idx == $campo) $tpl->idxVal = $valor;
        }
        $tpl->idx = $tb->idx;
        $tpl->block('tabela_content_linha');
    }

    // tble
    foreach ($campos as $campo => $val) {
        if ($tb->idx != $campo) {
            $tpl->campo = $val;
            $tpl->block('tble_content_linha');
        }
    }

    $tpl->titulo .= '|Tabela ' . $tpl->tabela;
}

if ($acao == 'sessao') {

    $tpl->session = '<pre>' . print_r($_SESSION, true) . '</pre>';
}

// ------------------------------------------------
$tpl->block($acao);
$tpl->show();
$_SESSION['config']['timestamp'] = date('d-m-Y:H:i:s P');
$config->save('config');
exit();
?>