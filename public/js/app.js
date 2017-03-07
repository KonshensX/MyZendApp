
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
            aspectRatio: 1 / 1,
            crop: function(e) {
                x = e.detail.x;
                y = e.detail.y;
                width = e.detail.width;
                height = e.detail.height;
            }
        });
    }, 10);


}
