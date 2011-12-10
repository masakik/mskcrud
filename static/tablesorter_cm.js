$.tablesorter.addWidget({
    id: "jColumnManager",
    format: function(element) {
        var m = '<ul id="menu-for-' + $(element).attr('id') + '" rel="' + $(element).attr('id') + '" class="contextMenu">';
        $('th', $(element)).each(function(n, e) {
            var t = $(e).attr('title') ? $(e).attr('title') : $(e).html();
            $(e).attr('rel', n);
            m += '<li rel="' + n + '"><a href="#column-' + n + '" rel="' + n + '">' + t + '</a></li>';
            $('tr', $(element)).each(function(j, r) {
                $('td', $(r)).each(function(i, f) {
                    $(f).attr('rel', i);
                });
            });
        });
        m += '</ul>';
        $(element).after(m);
        $('th', $(element)).contextMenu({
                menu: 'menu-for-' + $(element).attr('id')
            },
            function(action, el, pos) {
                var enlace = $('a[href="#' + action + '"]');
                if (enlace.css('text-decoration') == 'line-through') {
                    $('a[href="#' + action + '"]').css({'text-decoration':'none'});
                } else {
                    $('a[href="#' + action + '"]').css({'text-decoration':'line-through'});
                }
                $('th[rel="' + $('a[href="#' + action + '"]').attr('rel') + '"]').toggle();
                $('td[rel="' + $('a[href="#' + action + '"]').attr('rel') + '"]').toggle();
            });
    }
});
