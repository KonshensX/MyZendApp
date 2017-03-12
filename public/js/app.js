function previewImage() {
    var preview = document.querySelector('.preview');
    var file    = document.querySelector('input[type=file]').files[0];
    var reader  = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);

    }
    setInterval(function(){
        var image = document.getElementById('image');
        var cropper = new Cropper(image, {
            aspectRatio: 300 / 180,
            crop: function(e) {
                x = e.detail.x;
                y = e.detail.y;
                width = e.detail.width;
                height = e.detail.height;
            }
        });
    }, 50);
}

//JQuery functions being called down bellow
$(document).ready(function () {
    $('#alertbox').hide();
    $('#upload-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this); //This is useless
        var url = $(this).attr('action');

        var some = $('#image-input')[0].files;
        var data = new FormData(some);
        data.append('post_id', $('#id').attr('value'));
        data.append('file-0', some[0]);
        data.append('x', x);
        data.append('y', y);
        data.append('width', width);
        data.append('height', height);
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            contentType: false,
            processData: false,
        })
        .done(function () {
            $('#alertbox').show();
        });

    });


    $('#myForm').on('submit', function (e) {
        e.preventDefault();
        console.log("s");
    });
});
