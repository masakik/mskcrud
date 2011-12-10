<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tv
 * Date: 24/08/11
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */

if (isset($_GET['acao'])) $acao = $_GET['acao']; else $acao = 'ajuda';

if ($acao == 'tble_post') {
    $tb = new meuPDO();
    $post_acao = $_POST['acao'];
    unset($_POST['acao']);
    switch ($post_acao) {
        case 'edit':
            $tb->updateById($_POST);
            $_SESSION['msg'] = 'Dados atualizados com sucesso!';
            break;
        case 'excl':
            $tb->deleteById($_POST);
            $_SESSION['msg'] = 'Dados excluídos com sucesso!';
            break;
        case 'adic':
            $tb->insert($_POST);
            $_SESSION['msg'] = 'Dados adicionados com sucesso!';
            break;
        default:
            $_SESSION['msg'] = 'Ação incorreta. Nada feito!';
    }

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

if ($acao == 'limparSessao') {
    $_SESSION = array();
    $_SESSION['msg'] = 'Sessão limpa.';
    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

if ($acao == 'chDb') {
    $id = $_GET['id'];
    if ($id == 'novo') {
        // cadastra um novo db
        echo "novo db";
        exit;
    }
    $config->setDb($id);
    header('Location:' . $_SERVER['PHP_SELF']);
    exit;
}
?>