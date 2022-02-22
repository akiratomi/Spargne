/* ::::::::::::::::::::::::::::::::::::::::::::::: -- Nav Bar -- ::::::::::::::::::::::::::::::::::::::::::::::: */
$(".navAccount_ul").children().each((index, element) => {
    $('#' + $(element).html()).hide();
})
$("#Home").show();

$(".navAccount_ul").children().click(function() {
    $(".navAccount_ul").children().each((index, element) => {
        $(element).removeClass("navAccount_selected");
        $('#' + $(element).html()).hide();
    });
    $(this).addClass("navAccount_selected");
    $('#' + $(this).html()).show();
})

$(".bodyBeneficiary").children().each(function() {
    $(this).hide();
})

let beneficiary = false

$(".headBeneficiary").click(function() {
    if (beneficiary == false) {
        $(".bodyBeneficiary").children().each(function() {
            $(this).show("fast");
        })
        beneficiary = true;
        $("#iconOpen").html('<svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#FFFF"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/></svg>')
    } else {
        $(".bodyBeneficiary").children().each(function() {
            $(this).hide("fast");
        })
        beneficiary = false;
        $("#iconOpen").html('<svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 0 24 24" width="50px" fill="#FFFF"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>')
    }

})

let nb_chiffre = 0;
let other = 0;
let o = true;
$("#amount").on("input", function() {
    nb_chiffre++;
    if ($("#amount").val() < 1000000) {
        if (nb_chiffre == 1) {
            if ($("#amount").val() == 0) {
                nb_chiffre--;
            }
            $("#amount").val($("#amount").val() / 100);
            console.log(nb_chiffre);
        } else if (nb_chiffre >= 2) {
            $("#amount").val(($("#amount").val() * 10).toFixed(2));
            console.log(nb_chiffre);
        }

    } else {
        $("#amount").val(1000000, 00);
    }

});

$("#amount").on("keydown", function(event) {
    if (event.which == 8) {
        $("#amount").val(0);
        nb_chiffre = -1;
    }
});

$("#accountList").hide();
$("#beneficiaryList").hide();

$(".fromList").click(function() {
    $(".nameAccount").html($(this).find(".nom").html() + "<br>" + $(this).find(".num").html());
    $(".balanceAccount").html($(this).find(".bal").html() + "<br>" + $(this).find(".owned").html());
    $(".numFrom").val($(this).find(".num").html());
    $("#accountList").fadeOut(100);

    accountList = false;
})

$(".toOwnList").click(function() {
    $(".nameBeneficiary").html($(this).find(".nom").html() + "<br>" + $(this).find(".num").html());
    $(".balanceBeneficiary").html($(this).find(".bal").html() + "<br>" + $(this).find(".owned").html());
    $(".numBeneficiary").html("");
    $(".ibanBeneficiary").html("");
    $(".numTo").val($(this).find(".num").html());
    $(".typeTo").val("1");

    $("#beneficiaryList").fadeOut(100);
    beneficiaryList = false;
})

$(".toBeneficiaryList").click(function() {
    $(".nameBeneficiary").html($(this).find(".nom").html() + "<br>" + $(this).find(".owned").html());
    $(".ibanBeneficiary").html($(this).find(".iban").html());
    $(".numBeneficiary").html("");
    $(".balanceBeneficiary").html("");
    $(".numTo").val($(this).find(".iban").html());
    $(".typeTo").val("2");

    $("#beneficiaryList").fadeOut(100);
    beneficiaryList = false;
})

let accountList = false;
let beneficiaryList = false;

$(".accountSelected").click(function() {
    if (beneficiaryList) {
        if (accountList) {
            $("#accountList").fadeOut(100);
            $("#beneficiaryList").fadeOut(100);
            accountList = false;
            beneficiaryList = false;
        } else {
            $("#accountList").fadeIn(100);
            $("#beneficiaryList").fadeOut(100);
            accountList = true;
            beneficiaryList = false;
        }
    } else {
        if (accountList) {
            $("#accountList").fadeOut(100);
            accountList = false;
        } else {
            $("#accountList").fadeIn(100);
            accountList = true;
        }
    }

})

$(".beneficairySelected").click(function() {
    if (accountList) {
        if (beneficiaryList) {
            $("#beneficiaryList").fadeOut(100);
            $("#accountList").fadeOut(100);
            beneficiaryList = false;
            accountList = false;

        } else {
            $("#beneficiaryList").fadeIn(100);
            $("#accountList").fadeOut(100);
            beneficiaryList = true;
            accountList = false;

        }
    } else {
        if (beneficiaryList) {
            $("#beneficiaryList").fadeOut(100);
            beneficiaryList = false;
        } else {
            $("#beneficiaryList").fadeIn(100);
            beneficiaryList = true;
        }
    }
})

$(".nav_to_ul").children().each((index, element) => {
    $('#' + $(element).html()).hide();
})
$("#Owns").show();

$(".nav_to_ul").children().click(function() {
    $(".nav_to_ul").children().each((index, element) => {
        $(element).removeClass("nav_to_selected");
        $('#' + $(element).html()).hide();
    });
    $(this).addClass("nav_to_selected");
    $('#' + $(this).html()).show();
})