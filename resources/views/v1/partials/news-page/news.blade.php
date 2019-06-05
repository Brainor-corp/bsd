{{--<form id="filter-form" action="">--}}
    {{--<div id="news-page__filter-block" class="sort__block d-flex align-items-center">--}}
        {{--<span class="sort__label margin-item">Фильтровать по:</span>--}}
        {{--<div class="input-group d-flex margin-item">--}}
            {{--<div class="input-group__item relative">--}}
                {{--<select name="categories[]" id="category_select"--}}
                        {{--class="filter-select" title="Категории" data-value="{{ json_encode($request->categories) }}"--}}
                        {{--data-selected-text-format="static" data-style="custom-select"--}}
                        {{--multiple>--}}
                    {{--@foreach($newsTerms as $term)--}}
                        {{--<option @if(isset($request->categories) && in_array($term->id, $request->categories)) selected @endif value="{{ $term->id }}">{{ $term->title }}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="input-group__item relative">--}}
                {{--<input class="custom-select" type="text" autocomplete="off" name="daterange"--}}
                       {{--value="{{ $request->daterange }}" placeholder="Дата"/>--}}
            {{--</div>--}}
            {{--<div class="input-group__item relative">--}}
                {{--<i class="dropdown-toggle fa-icon"></i>--}}
                {{--<select name="cities[]" id="city_select"--}}
                        {{--class="filter-select" title="Город" data-value="{{ json_encode($request->cities) }}"--}}
                        {{--data-selected-text-format="static" data-style="custom-select"--}}
                        {{--multiple>--}}
                    {{--@foreach($cityTags as $city)--}}
                        {{--<option @if(isset($request->cities) && in_array($city->id, $request->cities)) selected @endif value="{{ $city->id }}">{{ $city->title }}</option>--}}
                    {{--@endforeach--}}
                {{--</select>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</form>--}}
<div id="tag-label-wrapper" class="selected-list flex-wrap d-flex">
    @if(isset($request->daterange))
        <div class="selected-item d-flex align-items-center margin-item">
            <span class="selected-item__name">{{ $request->daterange }}</span>
            <a class="delete-tag" href="#" data-type="date_select">
                <i class="fa fa-close"></i>
            </a>
        </div>
    @endif
    @if(isset($request->cities))
        @foreach($request->cities as $cityId)
            <div class="selected-item d-flex align-items-center margin-item">
                <span class="selected-item__name">{{ $cityTags->where('id', $cityId)->first()->title }}</span>
                <a class="delete-tag" href="#" data-type="city_select" data-value="{{ $cityId }}">
                    <i class="fa fa-close"></i>
                </a>
            </div>
        @endforeach
    @endif
    @if(isset($request->categories))
        @foreach($request->categories as $categoryId)
            <div class="selected-item d-flex align-items-center margin-item">
                <span class="selected-item__name">{{ $newsTerms->where('id', $categoryId)->first()->title }}</span>
                <a class="delete-tag" href="#" data-type="category_select" data-value="{{ $categoryId }}">
                    <i class="fa fa-close"></i>
                </a>
            </div>
        @endforeach
    @endif
</div>
<div class="news__block">
    @forelse($posts as $post)
        <a href="{{ route('news-single-show', ['slug' => $post->slug]) }}" class="news__item d-flex flex-column">            <div>
                <span class="news__title">{{ $post->title }}</span>
            </div>
            <span class="news__content">{{ $post->description }}</span>
            <span class="news__info d-flex align-items-center">
                <span class="news__info-date">{{ \Jenssegers\Date\Date::createFromFormat('Y-m-d H:i:s', $post->published_at)->format('d F Y') }}</span>
                <span class="news__info-category">
                    @foreach($post->terms->where('type', 'category') as $category)
                        {{ $category->title . ($loop->last ? '' : ', ')}}
                    @endforeach
                </span>
            </span>
        </a>
    @empty
        <span>Записи отсутствуют</span>
    @endforelse
    {{ $posts->appends($request->input())->links('v1.partials.pagination.pagination') }}
</div>
