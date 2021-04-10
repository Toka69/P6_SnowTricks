$(function () {

    $(".littlePhoto").click(function () {
        var littlePhoto = $(this).attr("src");
        var bigPhoto = littlePhoto.replace("littlePhoto", "bigPhoto");
        $(".bigPhoto").html("<img src='" + bigPhoto + "'>");
        $(".bigPhoto").fadeIn("slow").css("display", "flex");
    });

    $(".bigPhoto").click(function () {
        $(".bigPhoto").fadeOut("fast");
    });

});
