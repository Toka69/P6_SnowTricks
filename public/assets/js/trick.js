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

    //Show input file video
    $(document).ready(function() {
        $('.editVideo').click(function () {
            console.log($(this).parent().find("div.videoInput").show());
        });
    });