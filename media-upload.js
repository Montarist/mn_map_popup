jQuery(document).ready(function ($) {
    var frame;

    $("#upload_photo_button").on("click", function (event) {
        event.preventDefault();

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: "Select or Upload Photo",
            button: {
                text: "Use this photo",
            },
            multiple: false,
        });

        frame.on("select", function () {
            var attachment = frame.state().get("selection").first().toJSON();
            $("#photo_url").val(attachment.url);
            $("#photo_preview").attr("src", attachment.url).show();
        });

        frame.open();
    });
});
