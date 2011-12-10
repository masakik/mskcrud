<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>{titulo}</title>
    <link rel="stylesheet" href="static/960gs/reset.css">
    <link rel="stylesheet" href="static/960gs/text.css">
    <link rel="stylesheet" href="static/960gs/960.css">
    <script src="static/jquery-1.6.1.min.js" type="text/javascript"></script>
    <script src="static/jquery-ui-1.8.13.custom.min.js" type="text/javascript"></script>
    <link href="static/jquery_ui/smoothness/jquery-ui-1.8.13.custom.css" rel="stylesheet">
    <script src="static/tablesorter.js" type="text/javascript"></script>
    <script src="static/tablesorter_filter.js" type="text/javascript"></script>
    <script src="static/tablesorter_cm.js" type="text/javascript"></script>
    <link href="static/tablesorter.css" rel="stylesheet">
    <link href="static/tablesorter_blueskin/style.css" rel="stylesheet">
    <script src="static/context_menu/jquery.contextMenu.js" type="text/javascript"></script>
    <link href="static/context_menu/jquery.contextMenu.css" rel="stylesheet">

    <link rel="stylesheet" href="static/top_menu.css">
    <script src="static/top_menu.js" type="text/javascript"></script>

    <link href="static/style.css" rel="stylesheet">
    <script type="text/javascript">
        {mskCrudJs}
    </script>
</head>
<body>
<div class="container_12">
    <div class="grid_12">
        <!-- BEGIN menu -->
        <div class="tabelas">
            <ul id="nav">
                <li>
                    <a href="#" title="Recarrega os dados">{db} =></a>
                    <ul>
                        <!-- BEGIN db_loop -->
                        <li><a href="?acao=chDb&id={id}" title="{host}">{db}</a></li>
                        <!-- END db_loop -->
                        <li><a title="Configura um novo banco de dados" href="?acao=chDb&id=novo">Novo db ...</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">{tabela}</a>
                    <ul>
                        <!-- BEGIN tabelas_loop -->
                        <li><a href="?acao=tblh&t={tabela}">{tabela}</a></li>
                        <!-- END tabelas_loop -->
                    </ul>
                </li>
                <li>
                    <a href="#">Views</a>
                    <ul>
                        <li>Sub1</li>
                        <li>Sub2</li>
                    </ul>
                </li>
                <li>
                    <a href="#">Configurações</a>
                </li>
                <li>
                    <a href="#"></a>
                </li>
                <li>
                    <a href="?acao=ajuda">Ajuda</a>
                    <ul>
                        <li><a href="?acao=sessao">Mostra sessão</a></li>
                        <li>Sub2</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
        <!-- END menu -->
        <div class="msg">{msg}</div>
        <!-- BEGIN tblh -->
        <div class="tabela">
            <div class="filter">
                <input class="float-left" name="filter" id="filter-box" value="Filtrar" maxlength="30" size="20" type="text"/>
                <input class="float-left" id="filter-clear-button" type="submit" value="Limpar"/>
                Mostrando <span id="numCols">{numCampos}</span> colunas, <span id="numRows">{numLinhas}</span> linhas.
            </div>
            <table>
                <thead>
                <tr>
                    <th><input id="add_btn" type="button" title="Adicionar nova linha" value="+"/></th>
                    <!-- BEGIN tabela_campos -->
                    <th>{campo}</th>
                    <!-- END tabela_campos -->
                </tr>
                </thead>
                <tbody>
                <!-- BEGIN tabela_content_linha -->
                <tr name="{idxVal}">
                    <td style="text-align:center; width: 50px">
                        <a class="edit_btn" href="#" title="Editar">E</a> |
                        <a class="visu_btn" href="#" title="Visualizar">V</a> |
                        <a class="excl_btn" href="#" title="Excluir">X</a>
                    </td>
                    <!-- BEGIN tabela_content_campo -->
                    <td class="{campo}">{valor}</td>
                    <!-- END tabela_content_campo -->
                </tr>
                <!-- END tabela_content_linha -->
                </tbody>
            </table>
        </div>
        <div class="tble" title="Adicionar/Visualizar/Alterar">
            <form id="cud" class="alterar" action="?acao=tble_post" method="post">
                <table width="100%">
                    <tbody>
                    <!-- BEGIN tble_content_linha -->
                    <tr>
                        <td>{campo}</td>
                        <td class="{campo}"><input type="text" name="{campo}" value=""/></td>
                    </tr>
                    <!-- END tble_content_linha -->
                    </tbody>
                </table>
                <input type="hidden" name="{idx}" value=""/>
                <input type="hidden" name="idx" value="{idx}"/>
                <input type="hidden" name="tbl" value="{tabela}"/>
                <input type="hidden" name="acao" value=""/>
                <input id="tble_close" type="button" value="Cancelar"/>
                <input id="ant" type="button" name="ant" value="<< Anterior"/>
                <input id="prox" type="button" name="prox" value="Próximo >>"/>
                <input class="cud_edit_btn" type="button" name="edit" title="Editar" value="E"/>
                <input class="cud_excl_btn" type="button" name="excl" title="Excluir" value="X"/>
                <input class="cud_copiar_btn" type="button" name="copiar" title="Copiar" value="C"/>
                <input type="submit" value="OK"/>
            </form>
        </div>
        <!-- END tblh -->

        <!-- BEGIN ajuda -->
        <h3>Menu de ajuda</h3>
        <!-- END ajuda -->

        <!-- BEGIN sessao -->
        <div class="sessao">
            <h3>Variáveis da sessão => <a href="?acao=limparSessao">Limpar</a></h3>

            <div>
            {session}
            </div>
        </div>
        <!-- END sessao -->
    </div>
</div>
</body>
</html>
