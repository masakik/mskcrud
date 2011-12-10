$(function() {
//hover states on the static widgets
    $('.ui-icon').hover(
            function() {
                $(this).addClass('ui-state-hover');
            },
            function() {
                $(this).removeClass('ui-state-hover');
            }
            );

    $('.jogadores_div table tr').hover(// nao está funcionando pois no css colocou cor no td
            function() {
                $(this).addClass('ui-state-hover');
            },
            function() {
                $(this).removeClass('ui-state-hover');
            }
            );


    $('.jogadores_div .main')
            .addClass('tablesorter')
            .tablesorter({debug: false,
                             widgets: ['zebra'],
                             sortList: [
                                 [3,0],
                                 [2,0]
                             ],
                             headers: {
                                 0: {sorter:false},
                                 4: {sorter:false},
                                 5: {sorter:false}
                             }
                         })
            .tablesorterFilter({filterContainer: $("#filter-box"),
                                   filterClearContainer: $("#filter-clear-button"),
                                   filterColumns: [1,2],
                                   filterCaseSensitive: false})
            ;

    $('#add_j_btn').click(function() {
        $('.add_j_div').slideToggle();
        $('.add_j_div input:first').focus();
    });

    $('.remove_j_btn').click(function() {
   //     var id = $(this).closest('tr').attr('name');
        if (confirm('Tem certeza que deseja excluir?'))
            return true; else return false;

    });
    $('.edit_j_btn').click(function() {
        var id = $(this).closest('tr').attr('name');
        alert(id);
    });
});