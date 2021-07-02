<script src="js/demo/test.js"></script>
<script src="js/bootstrap-sortable.js"></script>
<script src="js/select2.js"></script>
<script>
    $('#newetab').select2({
        language: "fr",
        theme: 'bootstrap4',
        class: 'form-control',
        dropdownAutoWidth: true,
        language: {
            noResults: function(params) {
                return "Aucun résulat";
            }
        }
    });

    function setSelect2ContainerWidth() {
        $(".select2-container").each(function() {
            var $this = $(this),
                inputGroup = $this.parents(".input-group"),
                inputGroupContainerWidth,
                inputGroupAddonWidth = 0;

            if (inputGroup.length) {
                inputGroupContainerWidth = inputGroup.parents("[class^='col-']").width() || inputGroup.parents(".form-group").width();

                $this.parents(".input-group").find(".input-group-addon, .input-group-btn").each(function() {
                    inputGroupAddonWidth += $(this).outerWidth();
                });

                $this.css({
                    width: '96.4%'
                });
            }
        });
    }

    window.onresize = function(event) {
        setSelect2ContainerWidth();
    }

    setSelect2ContainerWidth();
</script>
<script>
document.getElementById("newportable").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
});

document.getElementById("newportablepro").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
});
</script>
<script>
document.getElementById("newtel_contact").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
});

document.getElementById("newtelpro_contact").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
});
</script>
<script>

document.getElementById("newportable_contact").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
});

document.getElementById("newportablepro_contact").addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
});
</script>


<footer class="footer text-muted">
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <p class="float-right">
                    <a href="#">Remonter</a><br>
                </p>
                <p class="margin_20">Copyright © 2021 MFR St Egrève<br>
                    <a href="https://www.google.fr/maps/place/Maison+de+la+maintenance+-+MFR+St-Egr%C3%A8ve/@45.230491,5.6806222,17z/data=!4m8!1m2!3m1!2sMaison+de+la+maintenance+-+MFR+St-Egr%C3%A8ve!3m4!1s0x0:0x1bcc9e91c2c75f05!8m2!3d45.2304716!4d5.6828011">
                        2 bis avenue Général de Gaulle - BP 333 - 38523 Saint-Egrève Cedex</a><br>
                    Tél. : <a href="tel:0438023950">04 38 02 39 50</a> - Mail : <a href="mailto:mfr.st-egreve@mfr.asso.fr">mfr.st-egreve@mfr.asso.fr</a>
                </p>
                <p>Un problème ? <a href="mailto:sefagucluu@gmail.com">Me contacter</a> ou lire <a href="guide.php">le guide d'utilisation</a>.</p>
            </div>
            <div class="col-md-1">
            </div>
        </div>
    </div>
</footer>