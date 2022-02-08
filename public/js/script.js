/* ::::::::::::::::::::::::::::::::::::::::::::::: -- Nav Bar -- ::::::::::::::::::::::::::::::::::::::::::::::: */
var triggered = false;
$('.navTrigger').click(function() {
    $(this).toggleClass('active');
    if (triggered == false) {
        $("#mainListDiv").addClass("show_list");
        $("#mainListDiv").fadeIn(500);
        triggered = true;
    } else {
        $("#mainListDiv").fadeOut(500, function() {
            $("#mainListDiv").removeClass("show_list");
            triggered = false;
        });
    }
});
var triggered_menu = false;

$('.menu').click(function() {
    if (triggered_menu == false) {
        $("#navRight").removeClass("navRightHide");
        $("#navRight").addClass("navRightShow");
        triggered_menu = true;
    } else {
        $("#navRight").removeClass("navRightShow");
        $("#navRight").addClass("navRightHide");
        triggered_menu = false;
    }
});