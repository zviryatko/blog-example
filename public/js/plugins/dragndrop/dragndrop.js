/**
 * @version: dev
 * @author: MohamedAlaa http://bootsnipp.com/MohamedAlaa
 * @see http://bootsnipp.com/snippets/featured/bootstrap-drag-and-drop-upload
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        $('form').each(function () {
            var uploadForm = $(this);
            if (uploadForm.find('input[type=file].js-upload-files').length) {
                uploadForm.addClass('dragndrop-processed');

                uploadForm.on('submit', function (e) {
                    var uploadFiles = $('input[type=file].js-upload-files').files;
                    //e.preventDefault();

                    startUpload(uploadFiles)
                });

                uploadForm.on('drop', function (e) {
                    e.preventDefault();
                    $(this).removeClass('drop');

                    startUpload(e.originalEvent.dataTransfer.files)
                });

                uploadForm.on('dragover', function () {
                    $(this).addClass('drop');
                    return false;
                });

                uploadForm.on('dragleave', function () {
                    $(this).removeClass('drop');
                    return false;
                });

                var startUpload = function (files) {
                    console.log(files)
                };
            }
        });
    });
})(jQuery);