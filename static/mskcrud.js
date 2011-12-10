$(document).ready(function () {
    // -----------------------------------------------------TABLE SORTER FILTER -----------------------------
    $('.tabela table')
        .addClass('tablesorter')
        .tablesorter({
            widgets: ['jColumnManager','zebra'],
            headers: {0: {sorter:false}} // elimina o sort da colunas de controle
        })
        .tablesorterFilter({filterContainer: $("#filter-box"),
            filterClearContainer: $("#filter-clear-button"),
            filterColumns: [1,2,3,4,5,6,7,8,9,10],
            filterCaseSensitive: false})
        ;
    $('#filter-box').focus(function() {
        filtro = $(this).val();
        $(this).val('');
    });

    $('#filter-box').blur(function() {
        if ($(this).val() == '') $(this).val(filtro);
    });

    // ------------------------------------------------------ DIALOG -------------------------------------
    $('.tble').dialog(
        {autoOpen: false},
        {position:'center'},
        {minWidth:500},
        {minHeight:200}
    );

    $('#tble_close').click(function() {
        $('.tble').dialog('close');
    });
    //-----------------------------------------------------------BOTOES -----------------------------
    var td = new Array();
    $('#cud td').each(function(i) {
        td[i] = $(this).html();
    });

    $('#add_btn').click(function() { //----------------------BOTAO ADICIONAR -----------------------------
        resetCud('adic');
        $('#cud input[name="acao"]').val('adic');
        $('.tble').dialog('open');
        return false;
    });
    if ({add_btn})
        $('#add_btn').click();

    $('.cud_excl_btn').click(function() {//--------------------BOTAO REMOVER -----------------------------
        var id = $('#cud input[name="{idx}"]').val();
        excluir(id);
        return false;
    });

    $('.excl_btn').click(function() {
        var id = $(this).closest('tr').attr('name');
        excluir(id);
        return false;
    });

    $('.cud_edit_btn').click(function() {//----------------------BOTAO EDITAR -----------------------------
        var id = $('#cud input[name="{idx}"]').val();
        editar(id);
        return false;
    });

    $('.edit_btn').click(function() {
        var id = $(this).closest('tr').attr('name');
        editar(id);
        $('.tble').dialog('open');
        return false;
    });

    $('.visu_btn').click(function() {//----------------------BOTAO VISUALIZAR -----------------------------
        var id = $(this).closest('tr').attr('name');
        visualizar(id);
        $('.tble').dialog('open');
        return false;
    });

    $('.cud_copiar_btn').click(function() {//----------------------BOTAO COPIAR -----------------------------
        var id = $('#cud input[name="{idx}"]').val();
        editar(id);
        $('#cud input[name="acao"]').val('adic'); // aqui sobrescreve a acao copiar para adicionar
        return false;
    });


    // ------------- FUNCOES ------------------------------------------------------------------------------
    function editar(id) {
        resetCud('edit');
        var tr = '.tabela tr[name="' + id + '"]';
        $(tr).find('td').each(function() {
            if ($(this).attr('class') != undefined) {
                var campo = $(this).attr('class');
                var valor = $(this).html();
                var target = '.tble input[name="' + campo + '"]';
                $(target).val(valor);
                $('#cud input[name="acao"]').val('edit');
            }
        });
    }

    function excluir(id) {
        if (confirm('Tem certeza que deseja excluir ?')) {
            resetCud('rem');
            $('#cud input[name="acao"]').val('excl');
            $('#cud input[name="{idx}"]').val(id);
            $('#cud').submit();
            return true;
        }
        else return false;
    }

    function visualizar(id) {
        resetCud('visu');
        var tr = '.tabela tr[name="' + id + '"]';
        $(tr).find('td').each(function() {
            if ($(this).attr('class') != undefined) {
                var campo = $(this).attr('class');
                var valor = $(this).html();
                $('.tble table td.' + campo).html(valor);
                if (campo == '{idx}') $('#cud input[name="{idx}"]').val(valor);
            }
        });
    }

    function resetCud(botao) {
        // first hide all buttons
        $('#cud input[id!="tble_close"]').hide();
        $('#cud tr td:nth-child(2)').html('');

        if (botao == 'visu') {
            // then show what is relevant
            $('#cud input[name="ant"]').show();
            $('#cud input[name="prox"]').show();
            $('#cud input[name="edit"]').show();
            $('#cud input[name="excl"]').show();
            $('#cud input[name="copiar"]').show();
        }
        if (botao == 'edit' || botao == 'adic') {
            $('#cud td').each(function(i) {
                $(this).html(td[i]);
            });
            $('#cud input[type="submit"]').show();
        }
    }

    $('#prox').click(function() {//----------------------BOTAO PROXIMO -----------------------------
        var id = $('#cud input[name="{idx}"]').val();
        var nextId = $('.tabela tbody tr[name="' + id + '"]').next('tr').attr('name');
        if (nextId == undefined) nextId = $('.tabela tbody tr').first('tr').attr('name');
        visualizar(nextId);
        return false;
    });
    $('#ant').click(function() {//----------------------BOTAO ANTERIOR -----------------------------
        var id = $('#cud input[name="{idx}"]').val();
        var nextId = $('.tabela tbody tr[name="' + id + '"]').prev('tr').attr('name');
        if (nextId == undefined) nextId = $('.tabela tbody tr').last('tr').attr('name');
        visualizar(nextId);
        return false;
    });
    // -----------------------------------------------------submit do formulario
    $('#cud').submit(function() { // aqui submete somente os campos que foram alterados, além do idx
        var idx = $('#cud input[name="{idx}"]').val();
        if ($('#cud input[name="acao"]').val() == 'edit') {
            $('.tble').dialog('close');
            $('tr[name="' + idx + '"]').find('td').each(function() {
                var campo = $(this).attr('class');
                var valorOri = $(this).html();
                var target = '.tble input[name=' + campo + ']';
                if ($(target).val() == valorOri && valorOri != idx) $(target).remove();
            });
            if (vazio()) return false;
        }
        if ($('#cud input[name="acao"]').val() == 'adic') {
            if (vazio()) return false;
        }
        function vazio() {
            var vazio = 0;
            $('#cud td input').each(function() {
                if ($(this).val() != '')
                    vazio = vazio + 1;
            });
            if (vazio == 0) return true;
            else return false;
        }
    });

    // esconder/mostrar coluna do índice

    $('#idx_btn').click(function() {
        $('.tabela td[class*="{idx}"]').toggleClass('escondido');
        $('.tabela th:nth-child(2)').toggleClass('escondido');
        var alt = $(this).attr('alt');
        var value = $(this).attr('value');
        $(this).attr('alt', value).attr('value', alt);
        countCampos();
        // todo: tem de fazer um ajuste no filtro para filtrar pelas colunas corretas e não interferir com o ocultar
    });
    //$('#idx_btn').click();
    // esconder colunas
    $('#show_btn').click(function() {
        $('.tabela td:nth-child(2),th:nth-child(2)').toggle();
        $(this).attr('value', 'Esconder Índice').attr('id', 'hide_btn');
        countCampos();
    });

    function countCampos() {
        var numCampos = $('.tabela tr:first').find('th:visible').length;
        $('#numCols').html(numCampos - 1);
    }


    //   $('.tabela, .tble').css('display', 'inherit');
    // aqui dá pau no zebra do tablesorter
}); // fim jquery
