<div class="modal-header">
    <h5 class="modal-title">Загрузка файлов</h5>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <p>Перетащите нужные файлы на окошно ниже</p>
        </div>
        <div action="/{{ config('bradmin.admin_url') }}/cms/files/upload" class="dropzone col-12" id="cms-dropzone"></div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
</div>