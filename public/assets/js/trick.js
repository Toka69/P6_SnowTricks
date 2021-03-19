    //Manage Add a photo
    var $addphotoLink = $('<a href="#" class="btn btn-primary mt-1"><i class="far fa-file-image"></i> Add a Photo</a>');
    var $newLinkLi = $('<li></li>').append($addphotoLink);

    jQuery(document).ready(function() {
        var $collectionHolder = $('ul.photos');
        var $nbPhoto = $("[id=photoCard]").length;
        $collectionHolder.append($newLinkLi);
        $collectionHolder.data('index', $nbPhoto+1);

        $addphotoLink.on('click', function(e) {
            e.preventDefault();
            addphotoForm($collectionHolder, $newLinkLi);
            bsCustomFileInput.init();
        });
    });

    function addphotoForm($collectionHolder, $newLinkLi) {
        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype.replace(/__name__/g, index);
        $collectionHolder.data('index', index + 1);
        var $newFormLi = $('<li class="card mb-1"></li>').append(newForm);
        $newFormLi.append('<a href="#" class="remove-photo mb-1 btn btn-danger">Delete</a>');
        $newLinkLi.before($newFormLi);

        $('.remove-photo').click(function(e) {
            e.preventDefault();
            $(this).parent().remove();

            return false;
        });
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
                    console.log(reader.result);
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
