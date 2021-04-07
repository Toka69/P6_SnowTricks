    jQuery(document).ready(function() {
        $("input#user_file").attr("onchange", "previewPhoto(this)");
    });

    function previewPhoto(input) {
        var file = input.files[0];

        if (file.size > 2000000) {
            alert("Your file is too large. 2MB max");

            return;
        }

        if (file) {
            var reader = new FileReader();

            reader.onload = function () {
                var $html = $(
                    "<img class=\"rounded-circle profile\" src=\"" + reader.result + "\" alt=\"photo\"/>"
                );

                if ($("img.profile").length !== 0) {
                    $(this).remove();
                }
                $("div.photo").before($html);
            };
            reader.readAsDataURL(file);
        }
    }
