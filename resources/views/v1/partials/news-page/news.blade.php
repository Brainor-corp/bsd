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
                <span class="selected-item__name">{{ $cities->where('id', $cityId)->first()->name ?? '' }}</span>
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
        <a href="{{ route('news-single-show', ['slug' => $post->slug]) }}" class="news__item d-flex flex-column">
            <div>
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
