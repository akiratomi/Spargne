$("#inputSearchName").on("input", function() {
    var valueSearch1 = $("#inputSearchName").val().toUpperCase();
    var valueSearch2 = $("#inputSearchUid").val().toUpperCase();
    $(".researchElementLine").each(function(index) {
        var valueElement1 = $(this).children('.researchElement1').text().toUpperCase();
        var valueElement2 = $(this).children('.researchElement2').text().toUpperCase();
        if (valueElement1.includes(valueSearch1) && valueElement2.includes(valueSearch2)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

$("#inputSearchUid").on("input", function() {
    var valueSearch1 = $("#inputSearchName").val().toUpperCase();
    var valueSearch2 = $("#inputSearchUid").val().toUpperCase();
    $(".researchElementLine").each(function(index) {
        var valueElement1 = $(this).children('.researchElement1').text().toUpperCase();
        var valueElement2 = $(this).children('.researchElement2').text().toUpperCase();
        if (valueElement2.includes(valueSearch2) && valueElement1.includes(valueSearch1)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});