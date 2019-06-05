<hr>
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#insertMediaModal">Медиа</button>
<hr>
{{--<div class="modal fade" id="insertMediaModal" tabindex="-1" role="dialog" aria-labelledby="insertMediaModal" aria-hidden="true">--}}
    {{--<div class="modal-dialog modal-lg" role="document">--}}
        {{--<div class="modal-content">--}}
            {{--<button type="button" class="close mt-3 mr-3 text-right" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">&times;</span>--}}
            {{--</button>--}}


        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}


<div class="modal" id="insertMediaModal" tabindex="-1" role="dialog" aria-labelledby="insertMediaModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Вставить медиа</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <!-- Nav tabs -->
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs insert-media-tab-toggles">
                    <li><a href="#uploadFilesTab" data-toggle="tab" class="nav-link active text-dark">Загрузка файлов</a></li>
                    <li>
                        <a href="#singleImageTab" class="image-list-request nav-link text-dark" data-toggle="tab" data-wrapper-key="singleImageTabContent" data-file-type="image"  data-request-count="0">Изображение</a>
                    </li>
                    <li><a href="#imageGalleryTab" class="image-list-request nav-link text-dark" data-toggle="tab" data-wrapper-key="imageGalleryTabContent" data-file-type="image"  data-request-count="0">Галерея</a></li>
                    <li><a href="#thumbImageTab" id="thumbImageTabToggle" class="image-list-request nav-link text-dark" data-toggle="tab" data-wrapper-key="thumbImageTabContent" data-file-type="image"  data-request-count="0">Миниатюра</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="uploadFilesTab">
                        @include('zeusAdmin::SectionBuilder.Form.Fields.InsertMedia.InsertMediaTabs.uploadFiles',['id' => $id])
                    </div>

                    <div class="tab-pane" id="singleImageTab">
                        @include('zeusAdmin::SectionBuilder.Form.Fields.InsertMedia.InsertMediaTabs.singleImage',['id' => $id])
                    </div>

                    <div class="tab-pane" id="imageGalleryTab">
                        @include('zeusAdmin::SectionBuilder.Form.Fields.InsertMedia.InsertMediaTabs.gallery',['id' => $id])
                    </div>

                    <div class="tab-pane" id="thumbImageTab">
                        @include('zeusAdmin::SectionBuilder.Form.Fields.InsertMedia.InsertMediaTabs.thumbImage',['id' => $id])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="insertMediaFileDetailsModal" tabindex="-1" role="dialog" aria-labelledby="insertMediaFileDetailsModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="insertMediaFileDetailsModalContent">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="file-image-wrapper"></div>
                    </div>
                    <div class="col-xs-6">
                        <div class="file-description-wrapper">
                            <span>Url:<span class="file-description file-description-url"></span></span><br>
                            <span>Название:<span class="file-description file-description-name"></span></span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>