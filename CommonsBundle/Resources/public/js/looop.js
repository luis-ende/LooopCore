// from: http://www.fetchdesigns.com/blog/jquery-unordered-list-to-select/

$(document).ready(function () {
    $("ul.faux-select").each(function () {
        var list = $(this),
            div = $(document.createElement('div')).insertBefore($(this).hide());
            //select = $(document.createElement('select')).insertBefore($(this).hide());
            select = $(document.createElement('select'));
        $(div.append(select));
        $('>li', this).each(function () {
            var ahref = $(this).children('a'),
                    target = ahref.attr('target'),
                    option = $(document.createElement('option'))
                    .appendTo(select)
                    .val(ahref.attr('href'))
                    .html(ahref.html())
                    .click(function () {
                        if (option.val().length === 0)
                            return;
                        if (target === '_blank') {
                            window.open(ahref.attr('href'));
                        } else {
                            window.location.href = ahref.attr('href');
                        }
                    });
            if ((ahref.attr('class') === 'active')) {
                option.attr('selected', 'selected');
            }
        });
        var listAttributes = list.attr('class');
        div.addClass(listAttributes);
    });
});

/*$(document).ready(function () {
    $('#looop_bar_filters a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });
});*/