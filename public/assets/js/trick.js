    //Manage Add a photo
    jQuery(document).ready(function() {
        var $newLinkLi = $('<li></li>');
        var $collectionHolder = $('ul.photos');
        $collectionHolder.append($newLinkLi);
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype.replace(/__name__/g, index);
        newForm = newForm.replace('class="custom-file"', 'class="custom-file hidden"');
        newForm = newForm.replace('type="file"', 'type="file" onchange="previewPhoto1(this)"');
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<li class="mb-1"></li>').append(newForm);
        $newLinkLi.before($newFormLi);
    });

    function previewPhoto1(input){
        var file = input.files[0];

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
            var $html = $(
                '<div id="collection-photo {{ photo.vars.id }}" class="trick-media col-md-3">' +
                    '<div id="photoCard" class="card mb-2">' +
                        '<img id="previewImg" src="' + reader.result + '" alt=\'photo\' class=\'img-fluid littlePhoto\' style=\'height: 150px\'>' +
                        '<div class="d-flex flex-row-reverse mr-1">' +
                            '<div class="m-1 p-1 rounded bg-light d-flex flex-row">' +
                                '<div class="hidden">' +

                                '</div>' +
                                '<button type="button" class="delete btn">' +
                                    '<i class="fas fa-trash-alt"></i>' +
                                '</button>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
            ($('div.bigPhoto')).before($html);
            }

            reader.readAsDataURL(file);
        }
    }

    //Manage Add a video
    var $addVideoLink = $('<a href="#" class="btn btn-primary mt-1"><i class="far fa-file-video"></i> Add a Video</a>');
    var $newVideoLinkLi = $('<li></li>').append($addVideoLink);

    jQuery(document).ready(function() {
        var $videoCollectionHolder = $('ul.videos');
        var $nbVideo = $("[id=videoCard]").length;
        $videoCollectionHolder.append($newVideoLinkLi);
        $videoCollectionHolder.data('index', $nbVideo + 1);

        $addVideoLink.on('click', function(e) {
            e.preventDefault();
            addVideoForm($videoCollectionHolder, $newVideoLinkLi);
            bsCustomFileInput.init();
        });
    });

    function addVideoForm($videoCollectionHolder, $newVideoLinkLi) {
        var prototype = $videoCollectionHolder.data('prototype');
        var index = $videoCollectionHolder.data('index');
        var newVideoForm = prototype.replace(/__name__/g, index);
        $videoCollectionHolder.data('index', index + 1);
        var $newVideoFormLi = $('<li class="card mb-1"></li>').append(newVideoForm);
        $newVideoFormLi.append('<a href="#" class="remove-video mb-1 btn btn-danger">Delete</a>');
        $newVideoLinkLi.before($newVideoFormLi);

        $('.remove-video').click(function(e) {
            e.preventDefault();
            $(this).parent().remove();

            return false;
        });
    }

    //Manage delete a media
    $(document).ready(function() {
        $('button[class="delete btn"]').click(function(){
            console.log($(this).closest("div.trick-media").remove());
        });
    });

    //PreviewFile
    function previewPhoto(input){
        var file = input.files[0];

        if(file){
            var reader = new FileReader();

            if(input.id === "trick_fileCover"){
                reader.onload = function(){
                    $("#previewImg-cover").attr("style", "background-image: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url('" + reader.result + "')");
                }
            }
            else {
                reader.onload = function () {
                    $("#previewImg-" + input.id.replace("_file", "")).attr("src", reader.result);
                }
            }

            reader.readAsDataURL(file);
        }
    }

    //Show input file video and valid button
    $(document).ready(function() {
        $('.editVideo').click(function () {
            $(this).closest("div.selectors").find("div.videoInput").show();
            $(this).closest("div.selectors").find("div.edit-buttons1").hide();

        });
    });

    //previewVideo
    function previewVideo(input){
        var id = "collection-video " + input.id.replace("_location", "");
        var str = input.value;

        // Youtube
        if(~str.indexOf("youtu.be")){
            var tag = str.replace("https://youtu.be/", "https://www.youtube.com/embed/");
        }
        if(~str.indexOf("youtube.com/embed/")){
            var tag = $(str).attr("src");
        }

        // Dailymotion
        if(~str.indexOf("dai.ly")){
            var tag = str.replace("https://dai.ly/", "https://www.dailymotion.com/embed/video/");
        }
        if(~str.indexOf("dailymotion.com/embed")){
            var iframe = $(str).html();
            var tag = $(iframe).attr("src");
            if(~tag.indexOf("autoplay")){tag = tag.replace("?autoplay=1", "");}
        }
        if(~str.indexOf("dailymotion.com/video/")){
            var tag = str.replace("dailymotion.com/video", "dailymotion.com/embed/video");
        }

        // result
        $('div[id="'+ id + '"] iframe').attr("src", tag);
        $(input).parent().parent().hide();
        $(input).parent().parent().parent().find("div.edit-buttons1").show();
    }
