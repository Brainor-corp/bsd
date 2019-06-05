<div class="modal-header">
    <h5 class="modal-title">Создать галерею</h5>
</div>
<div class="modal-body" id="imageGalleryTabContent">
</div>
<div id="insertMediaUploadMore"></div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    <button type="button" class="btn btn-success" id="createGallery" data-toggle="modal" data-target="#createGalleyModal">Создать галерею</button>
</div>

<div class="modal fade" id="createGalleyModal" tabindex="-1" role="dialog" aria-labelledby="createGalleyModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-header">
                <h5 class="modal-title">Галерея</h5>
            </div>
            <div class="modal-body">
                <div class="insert-media-wrapper">
                <div class="files-list row sortable" id="galleryDataContent">
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn  btn-success" id="insertGalleryMedia"  data-ckeditor-id="{{ $id }}">Вставить в запись</button>
            </div>
        </div>
    </div>
</div>
