<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Resize Before Upload</title>
</head>
<body>
	<h1>Upload File to Resize</h1>
	<form id="uploadImageForm" enctype="multipart/form-data" action="file.php" method="POST">
		<input name="imagefile" type="file"   />
		<input type="hidden" id="image_data" name="imageblob"  />
	    <button type="submit">submit</button>
	</form>
<img src="" id="imageAfterResize">

</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $('input[name=imagefile]').on('change',function(){

        var url = $(this).val();
        console.log(url);

        uploadPhotos(url);
    });

	function uploadPhotos(url){
    // Read in file
    var file = event.target.files[0];
    // Ensure it's an image
    if(file.type.match(/image.*/)) {
        console.log('An image has been loaded');

        // Load the image
        var reader = new FileReader();
        reader.onload = function (readerEvent) {
            var image = new Image();
            image.onload = function (imageEvent) {

                // Resize the image
                var canvas = document.createElement('canvas'),
                    max_size = 300,// TODO : pull max size from a site config
                    width = image.width,
                    height = image.height;
                if (width > height) {
                    if (width > max_size) {
                        height *= max_size / width;
                        width = max_size;
                    }
                } else {
                    if (height > max_size) {
                        width *= max_size / height;
                        height = max_size;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                var dataUrl = canvas.toDataURL('image/jpeg');
                var resizedImage = dataURLToBlob(dataUrl);
                $.event.trigger({
                    type: "imageResized",
                    blob: resizedImage,
                    url: dataUrl
                });
                console.log(canvas);
            }
            image.src = readerEvent.target.result;
        }
        reader.readAsDataURL(file);
    }
};

/* Utility function to convert a canvas to a BLOB */
var dataURLToBlob = function(dataURL) {
    var BASE64_MARKER = ';base64,';
    if (dataURL.indexOf(BASE64_MARKER) == -1) {
        var parts = dataURL.split(',');
        var contentType = parts[0].split(':')[1];
        var raw = parts[1];

        return new Blob([raw], {type: contentType});
    }

    var parts = dataURL.split(BASE64_MARKER);
    var contentType = parts[0].split(':')[1];
    var raw = window.atob(parts[1]);
    var rawLength = raw.length;

    var uInt8Array = new Uint8Array(rawLength);

    for (var i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }
   console.log(rawLength);
    return new Blob([uInt8Array], {type: contentType});
}
/* End Utility function to convert a canvas to a BLOB      */

/* Handle image resized events */
$(document).on("imageResized", function (event) {
    $("#image_data").val(event.url);
    // var data = new FormData($("form[id*='uploadImageForm']")[0]);

    // if (event.blob && event.url) {
    //     data.append('image_data', event.blob);
    //     //var file = new File(event.blob, 'xyz', {type: 'image/jpeg', lastModified: Date.now()});
    //     //console.log(event.blob);
    //      $('img#imageAfterResize').attr('src', event.url);
    //     $.ajax({
    //         url: event.url,
    //         data: data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         type: 'POST',
    //         success: function(data){
    //            //console.log(data);

    //         }
    //     });
    // }
});
</script>