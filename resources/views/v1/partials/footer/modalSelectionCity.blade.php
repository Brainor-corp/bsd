<div class="modal" id="selection-city" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Выберите город</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="relative">
                            <input type="text" class="form-control city-search" id="changeCityGlobal" data-redirect="{{ route('change-city') }}" placeholder="Введите название города"/>
                            <i class="fa fa-search fa-icon"></i>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="styles-list__title">Популярные города:</div>
                        <ul class="styles-list">
                            @foreach($popularCities as $popularCity)
                                <li>
                                    <a href="{{ route('change-city', ['city_id' => $popularCity->id]) }}" class="text-dark">
                                        {{ $popularCity->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>