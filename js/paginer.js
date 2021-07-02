$(function () {
    var totalrowshidden;
    var rows2display = 50;
    var rem = 0;
    var rowCount = 0;
    var forCntr;
    var forCntr1;
    var MaxCntr = 0
    $('[id="show"]').click(function () {
        rowCount = $('table tr').length;

        MaxCntr = forStarter + rows2display;

        if (forStarter <= $('table tr').length) {

            for (var i = forStarter; i < MaxCntr; i++) {
                $('tr:nth-child(' + i + ')').show(200);
            }

            forStarter = forStarter + rows2display

        }
        else {
            $('[id="show"]').hide();
        }



    });



    $(document).ready(function () {
        var rowCount = $('table tr').length;


        for (var i = $('table tr').length; i > rows2display; i--) {
            rem = rem + 1
            $('tr:nth-child(' + i + ')').hide(200);

        }
        forCntr = $('table tr').length - rem;
        forStarter = forCntr + 1

    });

});