let sCountry = document.getElementById('sCountry');
let sRegion = document.getElementById('sRegion');
let sDepartment = document.getElementById('sDepartment');
let sCity = document.getElementById('sCity');
let sAddress = document.getElementById('sAddress');

$('#sRegion').hide()
$('#sDepartment').hide()
$('#sCity').hide()
$('#sAddress').hide()



$(document).ready(function() {
    let option = document.createElement('option');
    option.innerText = 'France';
    option.value = 'France';
    $("#sCountrySelect").append(option);
    $("#sRegion").show("slow")

    function ajaxRegion() {
        var request = $.ajax({
            url: "https://geo.api.gouv.fr/regions",
            method: "GET",
            dataType: "json",
            beforeSend: function(xhr) {
                xhr.overrideMimeType("application/json; charset=utf-8");
            }
        });

        request.done(function(msg) {
            $.each(msg, function(index, e) {
                let option = document.createElement('option')
                option.innerText = e.nom
                option.value = e.code
                $("#sRegionSelect").append(option)
            });
        });
        // Fonction qui se lance lorsque l’accès au web service provoque une erreur 
        request.fail(function(jqXHR, textStatus) {
            alert('erreur');
        });
    }
    ajaxRegion()

    $("#sRegionSelect").change(function() {

        var request = $.ajax({
            url: "https://geo.api.gouv.fr/regions/" + $(this).val() + "/departements",
            method: "GET",
            dataType: "json",
            beforeSend: function(xhr) {
                xhr.overrideMimeType("application/json; charset=utf-8");
            }
        });

        request.done(function(msg) {
            $("#sDepartmentSelect").html(" ")

            $.each(msg, function(index, e) {

                let option = document.createElement('option')
                option.innerText = e.nom + " " + e.code
                option.value = e.code
                $("#sDepartmentSelect").append(option)

            });
            $("#sDepartment").show("slow")
        });
        // Fonction qui se lance lorsque l’accès au web service provoque une erreur 
        request.fail(function(jqXHR, textStatus) {
            alert('erreur');
        });
    })

    $("#sDepartmentSelect").change(function() {

        var request = $.ajax({
            url: "https://geo.api.gouv.fr/departements/" + $(this).val() + "/communes",
            method: "GET",
            dataType: "json",
            beforeSend: function(xhr) {
                xhr.overrideMimeType("application/json; charset=utf-8");
            }
        });

        request.done(function(msg) {
            console.log(msg);

            $("#sCitySelect").html(" ")

            $.each(msg, function(index, e) {

                let option = document.createElement('option')
                option.innerText = e.code + " " + e.nom
                option.value = e.code + "  " + e.nom
                $("#sCitySelect").append(option)

            });
            $("#sCity").show("slow")
        });
        // Fonction qui se lance lorsque l’accès au web service provoque une erreur 
        request.fail(function(jqXHR, textStatus) {
            alert('erreur');
        });
    })

    $("#sCitySelect").change(function() {
        $("#sAddress").show("slow")
    })
});