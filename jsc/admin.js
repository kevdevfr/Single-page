$(document).ready(function() {

    /* CTA image deletion */
    $('.thumb button').each(function() {
        $(this).click(function(e) {
            var thumb = $(this).parents('.thumb');
            e.preventDefault();
            if (confirm("Voulez-vous vraiment supprimer l\'image ?")) {
                $.ajax({
                    url: '../php/ajax.pic.php',
                    type: 'POST',
                    data: {
                        action: 'del',
                        name: thumb.prev('input[type=hidden]').attr('value')
                    },
                    success: function(msg) {
                        thumb.prev('input[type=hidden]').attr('value', '');
                        thumb.html('');
                        $('input[name=' + thumb.prev('input[type=hidden]').attr('name') + ']').remove();
                        thumb.closest('form').submit();
                    },
                    error: function(msg) {
                        console.log(msg);
                    }
                });

            }
        });
    });

    /* Preview uploaded picture */
    $('input[type=file]').unbind();
    $('input[type=file]').not('#file_upload').each(function() {
        var picture = $(this);
        picture.change(function() {
            var fichier = this.files[0];
            var nom = fichier.name;
            var ext = fichier.name.split('.').pop().toLowerCase();
            /* var formData = new FormData(picture.closest('form')[0]); */
            var formData = new FormData();
            console.log(fichier.name);
            formData.append(fichier.name, fichier, fichier.name);

            picture.next('.thumb').html('<progress></progress>');

            function progressHandlingFunction(e) {
                if (e.lengthComputable) {
                    $('progress').attr({
                        value: e.loaded,
                        max: e.total
                    });
                }
            }

            $.ajax({
                url: '../php/ajax.pic.php',
                type: 'POST',
                xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                    }
                    return myXhr;
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(msg) {
                  console.log(msg);
                    picture.next('.thumb').next('.error').css('display', 'none');
                    if (msg.split('$')[1])
                        picture.next('.thumb').html('<img src="' + msg.split('$')[1] + '">');
                    else {
                        picture.next('.thumb').html('');
                        picture.next('.thumb').next('.error').css('display', 'inline-block');
                    }
                },
                error: function(msg) {
                    console.log('error' + JSON.stringify(msg));
                }
            });

        });
    });

});
