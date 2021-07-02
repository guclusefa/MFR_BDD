function echangerDiv() {
    if (document.getElementById('Div1')) {

        if (document.getElementById('Div1').style.display == 'none') {
            document.getElementById('Div1').style.display = 'block';
            document.getElementById('Div2').style.display = 'none';
        }
        else {
            document.getElementById('Div1').style.display = 'none';
            document.getElementById('Div2').style.display = 'block';
        }
    }
}


function plageAuto() {
    document.getElementById("newdateRappelTime").value = '09:00'
    document.getElementById("newdate2RappelTime").value = '18:00'
}

function timeAuto(id1, id2) {
    time1 = document.getElementById(id1).value
    heures = time1.substring(0, 2)
    heures = Number(heures) + 2
    minutes = time1.substring(3, 5)
    time2 = heures + ":" + minutes
    if (time2.length != 5) {
        time2 = 0 + time2
    }
    document.getElementById(id2).value = time2
}


$(document).ready(function () {

    var sd = "<div class='input-group-prepend remove-add-more'><div class='input-group-text btn btn-danger remove-add-more'>X</div></div>";
    var max_fields = 5; //maximum input boxes allowed
    var wrapper = $(".partners"); //Fields wrapper
    var add_button = $(".add-more"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function (e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            var partnerClone = $('.partner').first().clone();
            $(partnerClone).append(sd);
            $(wrapper).append(partnerClone);
        }
        $('div.input-group-prepend').replaceWith(sd);
    });

    $(wrapper).on("click", ".remove-add-more", function (e) { //user click on remove text
        e.preventDefault();
        $(this).parent('.partner').remove();
        $(this).remove();
        x--;
    });
});



$(function () {
    $('#hide-me4').hide();
    $('#newcontact').change(function () {
        if ($('#newcontact').val() == '-1') {
            $('#hide-me4').show();
        } else {
            $('#hide-me4').hide();
        }
    });
});

//afficher textarea contenu si reponse oui
/* $(function () {
    $('#hide-me3').show();
    $('#newreponse').change(function () {
        if ($('#newreponse').val() == '0') {
            $('#hide-me3').hide();
        } else {
            $('#hide-me3').show();
        }
    });
});
 */

$(function () {
    $('#hide-me').hide();
    $('#newinteresse').change(function () {
        if ($('#newinteresse').val() == '0') {
            $('#hide-me').show();
        } else {
            $('#hide-me').hide();
        }
    });
});

$(function () {
    $('#hide-me2').hide();
    $('#newactif').change(function () {
        if ($('#newactif').val() == '0') {
            $('#hide-me2').show();
        } else {
            $('#hide-me2').hide();
        }
    });
});

$(function () {
    $('#hide-me5').hide();
    $('#newactif').change(function () {
        if ($('#newactif').val() == '2') {
            $('#hide-me5').show();
        } else {
            $('#hide-me5').hide();
        }
    });
});


$(function () {
    $(document).on('click', '.btn-add', function (e) {
        e.preventDefault();

        var dynaForm = $('.dynamic-wrap'),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(dynaForm);

        newEntry.find('input').val('');
        dynaForm.find('.entry:not(:last) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span>&times</span>');
    }).on('click', '.btn-remove', function (e) {
        $(this).parents('.entry:first').remove();

        e.preventDefault();
        return false;
    });
});


document.getElementById("newtel").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
})

document.getElementById("newtelpro").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
})

document.getElementById("newtelperso").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
})

