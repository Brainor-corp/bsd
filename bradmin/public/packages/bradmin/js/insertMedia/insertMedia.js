$(document).ready(function(){

    $(document).on('click', '.image-list-request', function (e) {
        var wrapperId = $(this).data('wrapperKey');
        var fileType = $(this).data('fileType');
        var requestCount = $(this).data('requestCount');
        var quantity = 20;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/' + window.adminUrl + '/api/media_library/insert_media/image_list',
            data: {wrapperId:wrapperId,fileType:fileType,requestCount:requestCount,quantity:quantity},
            cache: false,
            beforeSend: function() {
                // document.getElementById('ajax-loading-gif').style.display = 'block';
            },
            success: function(html){
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('#'+wrapperId).html(html);
            }
        });
    });

    $(document).on('click', '.image-list-more', function (e) {
        var wrapperId = $(this).data('wrapperKey');
        var fileType = $(this).data('fileType');
        var requestCount = $(this).data('requestCount');
        var requestCountForBtn =  requestCount;
        var quantity = 20;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/' + window.adminUrl + '/api/media_library/insert_media/image_list',
            data: {wrapperId:wrapperId,fileType:fileType,requestCount:requestCount,quantity:quantity},
            cache: false,
            beforeSend: function() {
                // document.getElementById('ajax-loading-gif').style.display = 'block';
            },
            success: function(html){
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('#image-list-more-last-'+wrapperId).before(html);
                var requestCount = parseInt(requestCountForBtn) + 1;
                $('#image-list-more').data('requestCount', requestCount);

            }
        });
        $( "#selectable" ).selectable();
        $( function() {
            $( "#selectable" ).selectable();
        } );
    });

    $(document).on('click', '#insertSingleMedia', function (e) {
        var elements = document.querySelectorAll('.insert-media-element.ui-selected img');
        var insertString = '';
        var ckeditorId = $(this).data('ckeditorId');
        function elementsOutput(element, index, array) {
            var lightBoxData = 'data-lightbox="'+element.getAttribute("data-insert-media-id")+'"';
            if(element.getAttribute("data-insert-media-title").length > 0){
                lightBoxData = lightBoxData + ' data-title="'+element.getAttribute("data-insert-media-title")+'"'
            }
            if(element.getAttribute("data-insert-media-alt").length > 0){
                lightBoxData = lightBoxData + ' data-alt="'+element.getAttribute("data-insert-media-alt")+'"'
            }
            insertString = insertString+
                '<p>'+
                '<a href="'+element.getAttribute("data-insert-media-url")+'" '+lightBoxData+'>'+
                '<img src="'+element.getAttribute("data-insert-media-url")+'"'+'/>'+
                '</a>'+
                '</p>';
        }
        elements.forEach(elementsOutput);

        var editor = CKEDITOR.instances[ckeditorId];
        // Check the active editing mode.
        if ( editor.mode == 'wysiwyg' )
        {
            // Insert HTML code.
            // https://docs.ckeditor.com/ckeditor4/docs/#!/api/CKEDITOR.editor-method-insertHtml
            editor.insertHtml( insertString );
        }
        else{alert( 'You must be in WYSIWYG mode!' );}

        // $('#insertMediaModal').modal('toggle');
        $( ".modal-backdrop.fade.show" ).remove();
    });

    $(document).on('click', '#createGallery', function (e) {
        var elements = document.querySelectorAll('.insert-media-element.ui-selected img');
        var insertString = '';
        function elementsOutput(element, index, array) {

            insertString = insertString+
                '<div class="insert-media-element ui-state-default">'+
                '<div class="insert-media-file-wrapper">'+
                '<img src="'+element.getAttribute("data-insert-media-base_url")+'-200x200.'+element.getAttribute("data-insert-media-extension")+'"'+
                ' data-insert-media-id="'+element.getAttribute("data-insert-media-id")+'"'+
                ' data-insert-media-url="'+element.getAttribute("data-insert-media-url")+'"'+
                ' data-insert-media-base_url="'+element.getAttribute("data-insert-media-base_url")+'"'+
                ' data-insert-media-extension="'+element.getAttribute("data-insert-media-extension")+'"'+
                ' data-insert-media-title="'+element.getAttribute("data-insert-media-title")+'"'+
                ' data-insert-media-alt="'+element.getAttribute("data-insert-media-alt")+'"'+
                '>'+
                '</div>'+
                '</div>';
        }
        elements.forEach(elementsOutput);

        $('#galleryDataContent').html(insertString);

        $( function() {
            $( ".sortable" ).sortable();
            $( ".sortable" ).disableSelection();
        } );
    });

    $(document).on('click', '#insertGalleryMedia', function (e) {
        var ckeditorId = $(this).data('ckeditorId');
        var elements = document.querySelectorAll('#galleryDataContent .insert-media-element img');
        console.log(elements);
        var insertString = '';
        insertString = insertString+
            '<div class="gallary">'
        var galleryId = Date.now();
        function elementsOutput(element, index, array) {
            var lightBoxData = 'data-lightbox="'+galleryId+'"';
            if(element.getAttribute("data-insert-media-title").length > 0){
                lightBoxData = lightBoxData + ' data-title="'+element.getAttribute("data-insert-media-title")+'"'
            }
            if(element.getAttribute("data-insert-media-alt").length > 0){
                lightBoxData = lightBoxData + ' data-alt="'+element.getAttribute("data-insert-media-alt")+'"'
            }
            insertString = insertString+
                '<div class="gallary-item">'+
                '<a href="'+element.getAttribute("data-insert-media-url")+'" '+lightBoxData+'>'+
                '<img src="'+element.getAttribute("data-insert-media-base_url")+'-200x200.'+element.getAttribute("data-insert-media-extension")+'"'+'/>'+
                '</a>'+
                '</div>';
        }
        elements.forEach(elementsOutput);
        insertString = insertString+
            '</div>'
        var editor = CKEDITOR.instances[ckeditorId];

        // Check the active editing mode.
        if ( editor.mode == 'wysiwyg' )
        {
            // Insert HTML code.
            // https://docs.ckeditor.com/ckeditor4/docs/#!/api/CKEDITOR.editor-method-insertHtml
            editor.insertHtml( insertString );
        }
        else{alert( 'You must be in WYSIWYG mode!' );}

        // $('#createGalleyModal').modal('toggle');
        //
        // $('#insertMediaModal').modal('toggle');

        $( ".modal-backdrop.fade.show" ).remove();
    });


    $(document).on('click', '.set-thumb', function (e) {
        // $('#insertMediaModal').modal('show');
        $(function () {
            $('.insert-media-tab-toggles #thumbImageTabToggle').tab('show')
        })
    });

    $(document).on('click', '#insertThumbImage', function (e) {
        var elements = document.querySelectorAll('.insert-media-element.ui-selected img');
        $('#input_thumb').val(elements[0].getAttribute("data-insert-media-url"));
        $('#input_thumb').attr('value', elements[0].getAttribute("data-insert-media-url"));
        $('#thumb').html('<img src='+elements[0].getAttribute("data-insert-media-url")+' class="img-fluid" alt="thumb" />');

        // $('#insertMediaModal').modal('hide');
    });

    function basename(path) {
        return path.split('/').reverse()[0];
    }
    $(document).on('dblclick', '.insert-media-file-wrapper', function (e) {
        var element = document.querySelector('.insert-media-element.ui-selected img');
        console.log(element.getAttribute("data-insert-media-url"));
        $('#insertMediaFileDetailsModal .file-image-wrapper').html('<img src='+element.getAttribute("src")+'/>');
        $('#insertMediaFileDetailsModal .file-description-url').html(element.getAttribute("data-insert-media-url"));
        $('#insertMediaFileDetailsModal .file-description-name').html(basename(element.getAttribute("data-insert-media-url")));

        // $('#insertMediaFileDetailsModal').modal('show');
    });
});