<div class="modal fade insert-media-wrapper" id="insertMediaModal" tabindex="-1" role="dialog" aria-labelledby="insertMediaModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs insert-media-tab-toggles">
                <li class="active"><a href="#uploadFilesTab" data-toggle="tab">Загрузка файлов</a></li>
                <li>
                    <a href="#singleImageTab" class="image-list-request" data-toggle="tab" data-wrapper-key="singleImageTabContent" data-file-type="image"  data-request-count="0">Изображение</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="uploadFilesTab">
                    @include('cms.admin.insertMedia.insertMediaTabs.uploadFiles')
                </div>
                <div class="tab-pane" id="singleImageTab">
                    @include('cms.admin.insertMedia.insertMediaTabs.singleImage')
                </div>
            </div>
        </div>
    </div>
</div>