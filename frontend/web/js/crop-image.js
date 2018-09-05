function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            if (!$('img').is('.quize-logo-img')) {
                $('#quize-logo').find('label').after('<div class="image-wrapper"><img class="quize-logo-img img-thumbnail" src=""></div>');
            }

            $('#quize-logo').find('img').attr('src', e.target.result);
            $('.quize-logo-img').rcrop();
        };

        reader.readAsDataURL(input.files[0]);
    }
}

jQuery(document).ready(function() {


});