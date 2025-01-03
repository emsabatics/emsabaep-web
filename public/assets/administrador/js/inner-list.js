$(".nest").on("click", function () {
    if ($(this).next().hasClass("show")) {
        $(this).next().removeClass("show");
        $(this).next().slideUp(300);
    } else {
        $(this).parent().parent().find("li .inner").removeClass("show");
        $(this).parent().parent().find("li .inner").slideUp(300);
        $(this).next().toggleClass("show");
        $(this).next().slideToggle(300);
    }
});
