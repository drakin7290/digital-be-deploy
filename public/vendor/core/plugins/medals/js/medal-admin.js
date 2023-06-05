$(document).ready((function () { $(document).on("click", "#is_foreign_link_icon", (function (e) { $(e.currentTarget).is(":checked") ? $(".icon-image").closest(".widget.meta-boxes").removeClass("hidden").fadeIn() : $("input[type=password]").closest(".form-group").addClass("hidden").fadeOut() })) })); $(document).ready((function () {
    $(".icon-image-url").closest(".widget.meta-boxes").addClass("hidden");
    $(document).on("click", "#is_foreign_link_icon", (function (e) {
        if ($(e.currentTarget).is(":checked")) {
            $(".icon-image-media").closest(".widget.meta-boxes").addClass("hidden").fadeOut();
            $(".icon-image-url").closest(".widget.meta-boxes").removeClass("hidden").fadeIn();
        } else {
            $(".icon-image-media").closest(".widget.meta-boxes").removeClass("hidden").fadeIn();
            $(".icon-image-url").closest(".widget.meta-boxes").addClass("hidden").fadeOut();
            console.log($("input", $(".icon-image-url").closest(".widget.meta-boxes")));
            $("input", $(".icon-image-url").closest(".widget.meta-boxes")).val("");
        }

    }))
}));