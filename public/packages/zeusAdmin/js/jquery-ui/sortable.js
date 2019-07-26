function initSortable() {
    $( ".sortable" ).sortable({
        connectWith:'.sortable',
        placeholder: "ui-state-highlight",
        forcePlaceholderSize: true,
        update: function( event, ui ) {//стартует и при изменении уровня списка и при изменении положения в списке
            if (this === ui.item.parent()[0]){//стартуем 1 раз
                var id = ui.item.attr("id");
                var parent_id = $(this).parent().data("parentId");
                var prev_id = $(ui.item).prev('li').attr('id');
                var next_id = $(ui.item).next('li').attr('id');
                console.log(id +'-'+ parent_id +'-'+ prev_id+'-'+ next_id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/' + window.adminUrl + '/api/tree-elements/menu/reorder',
                    data: {id:id,parent_id:parent_id,prev_id:prev_id,next_id:next_id},
                    cache: false,
                    beforeSend: function() {
                        // document.getElementById('ajax-loading-gif').style.display = 'block';
                    },
                    success: function(html){
                        // document.getElementById('ajax-loading-gif').style.display = 'none';
                        console.log('success')
                    },
                    error: function (ajaxContext) {
                        // document.getElementById('ajax-loading-gif').style.display = 'none';
                        $('#ajax_error_modal_text').html(ajaxContext.responseText);
                        $('#ajax_error_modal').modal('show')
                    }
                })
            }
        }
    });
    $( ".sortable" ).disableSelection();
}

$(document).ready(function () {
    initSortable();

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(document).on('click', '.add_tree_element', function (e) {
        e.preventDefault();
        let treeType = $(this).data('treeType'),
            treeNeighbor = $(this).data('treeNeighbor'),
            treeParent = $(this).data('treeParent');

        console.log(treeParent);

        $('#tree_neighbor_input').attr('value', treeNeighbor).val(treeNeighbor);
        $('#tree_type_input').attr('value', treeType).val(treeType);
        $('#tree_parent_input').attr('value', treeParent).val(treeParent);

        $('#add_tree_element_modal').modal('show');
    });

    $(document).on('click', '.delete_tree_element', function (e) {
        console.log('.delete_tree_element');

        e.preventDefault();
        let treeParent = $(this).data('treeElement');

        $('#tree_element_input').attr('value', treeParent).val(treeParent);

        $('#delete_tree_element_modal').modal('show');
    });

    $(document).on('click', '.tree-element-delete-btn', function (e) {
        e.preventDefault();
        button = $(this);
        button.attr('disabled', true);
        let form = $(this).parent().parent(),
            method = form.attr('method'),
            action = form.attr('action'),
            formData = form.serialize()
        ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: method,
            url: '/' + window.adminUrl + action,
            data: formData,
            cache: false,
            beforeSend: function() {
                // document.getElementById('ajax-loading-gif').style.display = 'block';
            },
            success: function(html){
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('.sortable-wrapper').html(html);
                button.removeAttr('disabled');
                initSortable();
                $('#delete_tree_element_modal').modal('hide');
            },
            error: function (ajaxContext) {
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('#ajax_error_modal_text').html(ajaxContext.responseText);
                $('#ajax_error_modal').modal('show')
            }
        });
    });

    $(document).on('click', '.new-tree-element-save-btn', function (e) {
        e.preventDefault();
        button = $(this);
        button.attr('disabled', true);
        let form = $(this).parent().parent(),
            method = form.attr('method'),
            action = form.attr('action'),
            formData = form.serialize();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: method,
            url: '/' + window.adminUrl + action,
            data: formData,
            cache: false,
            beforeSend: function() {
                // document.getElementById('ajax-loading-gif').style.display = 'block';
            },
            success: function(html){
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('.sortable-wrapper').html(html);
                button.removeAttr('disabled');
                initSortable();
                $('#add_tree_element_modal').modal('hide');
            },
            error: function (ajaxContext) {
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('#ajax_error_modal_text').html(ajaxContext.responseText);
                $('#ajax_error_modal').modal('show')
            }
        });
    });

    $(document).on('click', '.edit_tree_element', function (e) {
        e.preventDefault();
        let title = $(this).data('elementTitle'),
            slug = $(this).data('elementSlug'),
            url = $(this).data('elementUrl'),
            description = $(this).data('elementDescription'),
            id = $(this).data('elementId')
        ;

        $('#tree-element-id-edit').attr('value', id).val(id);
        $('#tree-element-title-edit').attr('value', title).val(title);
        $('#tree-element-slug-edit').attr('value', slug).val(slug);
        $('#tree-element-url-edit').attr('value', url).val(url);
        $('#tree-element-description-edit').attr('value', description).val(description);

        $('#edit_tree_element_modal').modal('show');
    });

    $(document).on('click', '.tree-element-edit-btn', function (e) {
        e.preventDefault();
        let form = $(this).parent().parent(),
            method = form.attr('method'),
            action = form.attr('action'),
            formData = form.serialize()
        ;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: method,
            url: '/' + window.adminUrl + action,
            data: formData,
            cache: false,
            beforeSend: function() {
                // document.getElementById('ajax-loading-gif').style.display = 'block';
            },
            success: function(html){
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('.sortable-wrapper').html(html);
                initSortable();
                $('#edit_tree_element_modal').modal('hide');
            },
            error: function (ajaxContext) {
                // document.getElementById('ajax-loading-gif').style.display = 'none';
                $('#ajax_error_modal_text').html(ajaxContext.responseText);
                $('#ajax_error_modal').modal('show')
            }
        });
    });
});