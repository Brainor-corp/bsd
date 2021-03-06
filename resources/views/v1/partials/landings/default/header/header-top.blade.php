<nav class="header-navbar navbar navbar-expand-xl @if($isMainPage) navbar-dark @else navbar-light justify-content-between @endif bg-transparent p-0">
    <a href="{{ route('index') }}"><img src="{{ asset('/images/img/logo.png') }}" class="logo" alt="Доставка грузов"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="container header-menu-container">
            <div class="row justify-content-between align-items-start">
                <div class="col-lg-auto text-lg-left text-center py-4 py-lg-0 col-12">
                    <div class="">
                        <div class="city-dropdown dropdown show d-inline-block mr-lg-0 mr-3">
                            <a class="dropdown-toggle city_choice"
                               href="#"
                               role="button"
                               data-toggle="modal"
                               data-target="#selection-city">{{ $city->name }}</a>
                        </div>
                        <div class="phones d-inline-block">
                            @if(isset($closestTerminal->phone))
                                @php
                                    $phones = preg_split("/(;|,)/", str_replace(' ', '', $closestTerminal->phone));
                                @endphp
                                @if(!empty($phones[0]))
                                    <a href="tel:{{ $phones[0] }}">{{ $phones[0] }}</a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div>
                        <ul class="nav_top m-0 list-inline">
                            @php
                                $headerMenu = \Zeus\Admin\Cms\Helpers\MenuHelper::getMenuTreeBySlug('shapka-sayta');
                            @endphp
                            @include('v1.partials.header.header-menu', ['nodeTree' => $headerMenu])
                        </ul>
                    </div>
                </div>
                <div class="col-lg-auto text-lg-left text-center py-4 py-lg-0 col-12">
                    <ul class="list-inline nav-icons-list mb-0">
                        <li class="list-inline-item mr-1 position-relative" data-toggle="tooltip" data-placement="bottom" title="Заявка">
                            <a href="{{ route('calculator-show', ['id' => null, 'type' => 'order']) }}">
                                <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
                                <g>
                                    <g id="Icons">
                                        <path d="M3.4,24C1.5,24,0,22.5,0,20.6V5.9C0,4,1.5,2.5,3.4,2.5h6.5c0.5,0,0.9,0.4,0.9,0.9s-0.4,0.9-0.9,0.9H3.4
                                            C2.5,4.3,1.8,5,1.8,5.9v14.8c0,0.9,0.7,1.6,1.6,1.6h17.3c0.8,0,1.5-0.7,1.5-1.6V10.4c0-0.5,0.4-0.9,0.9-0.9S24,9.9,24,10.4v10.2
                                            c0,1.8-1.4,3.3-3.3,3.4H3.4z"/>
                                        <path d="M14.5,12.9C14.5,12.9,14.5,12.9,14.5,12.9H12c-0.5,0-0.9-0.4-0.9-0.9V9.5c0-0.6,0.2-1.1,0.6-1.5l7.1-7.1c0,0,0,0,0,0
                                            c1.2-1.2,3.1-1.2,4.3,0c1.2,1.2,1.2,3.1,0,4.3L16,12.3C15.6,12.7,15,12.9,14.5,12.9z M12.9,12L12.9,12L12.9,12z M12.9,11.1h1.5
                                            c0,0,0,0,0,0c0.1,0,0.2,0,0.2-0.1l7.1-7.1c0.5-0.5,0.5-1.2,0-1.7c-0.5-0.5-1.2-0.5-1.7,0L13,9.3c-0.1,0.1-0.1,0.1-0.1,0.2V11.1z"
                                        />
                                        <path d="M7.1,12.9H4.6c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9h2.5C7.6,11.1,8,11.5,8,12S7.6,12.9,7.1,12.9z"/>
                                        <path d="M19.4,17.9H4.6c-0.5,0-0.9-0.4-0.9-0.9c0-0.5,0.4-0.9,0.9-0.9h14.8c0.5,0,0.9,0.4,0.9,0.9C20.3,17.4,19.9,17.9,19.4,17.9z
                                            "/>
                                        <path d="M21.1,6.8c-0.2,0-0.5-0.1-0.7-0.3l-3-3c-0.4-0.4-0.4-0.9,0-1.3s0.9-0.4,1.3,0l3,3c0.4,0.4,0.4,0.9,0,1.3
                                            C21.6,6.7,21.4,6.8,21.1,6.8z"/>
                                    </g>
                                </g>
                                </svg>
                            </a>
                        </li>
                        <li class="list-inline-item mr-1 position-relative" data-toggle="tooltip" data-placement="bottom" title="Калькулятор"><a href="{{ route('calculator-show') }}"><svg viewBox="-35 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m427 0h-412c-8.285156 0-15 6.714844-15 15v482c0 8.285156 6.714844 15 15 15h412c8.285156 0 15-6.714844 15-15v-482c0-8.285156-6.714844-15-15-15zm-15 482h-382v-452h382zm0 0"/><path d="m75 218h292c8.285156 0 15-6.714844 15-15v-128c0-8.285156-6.714844-15-15-15h-292c-8.285156 0-15 6.714844-15 15v128c0 8.285156 6.714844 15 15 15zm15-128h262v98h-262zm0 0"/><path d="m367 240h-58c-8.285156 0-15 6.714844-15 15v183c0 8.285156 6.714844 15 15 15h58c8.285156 0 15-6.714844 15-15v-183c0-8.285156-6.714844-15-15-15zm-15 183h-28v-153h28zm0 0"/><path d="m75 452h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15zm15-60h30v30h-30zm0 0"/><path d="m255 362h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15zm-15 60h-30v-30h30zm0 0"/><path d="m75 332h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15zm15-60h30v30h-30zm0 0"/><path d="m255 242h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15zm-15 60h-30v-30h30zm0 0"/></svg></a></li>
                        <li class="list-inline-item mr-1 position-relative" data-toggle="tooltip" data-placement="bottom" title="Проверить груз"><a href="{{ route('shipment-search') }}"><svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m492.52 118.3l-226.09-114.56-0.094-0.047c-10.067-5.012-22.029-4.9-32.002 0.3l-96.969 51.464c-0.788 0.334-1.545 0.739-2.27 1.205l-116.2 61.672c-11.656 6.103-18.896 18.061-18.896 31.222v212.88c0 13.161 7.24 25.118 18.896 31.221l215.34 114.29 0.097 0.051c5.086 2.652 10.691 3.981 16.297 3.981 5.385 0 10.772-1.226 15.704-3.682l226.18-114.6c12.016-6.009 19.478-18.081 19.478-31.519v-212.36c0-13.439-7.462-25.512-19.478-31.52zm-244.28-87.731c1.469-0.754 3.223-0.769 4.705-0.042l211.63 107.23-82.364 41.005-206.9-109.49 72.929-38.706zm-12.813 444.06l-202.51-107.48-0.097-0.051c-1.741-0.909-2.824-2.692-2.824-4.656v-199.35l205.44 107.12v204.42zm15.153-230.34l-204.3-106.54 97.024-51.493 205.88 108.94-98.594 49.085zm231.43 117.88c1e-3 2.007-1.115 3.809-2.911 4.703l-213.68 108.27v-204.74l98.386-48.982v51.355c0 8.281 6.714 14.995 14.995 14.995s14.995-6.714 14.995-14.995v-66.286l88.219-43.92v199.6z"/></svg></a></li>
                        <li class="list-inline-item mr-1 position-relative" data-toggle="tooltip" data-placement="bottom" title="Скидки"><div class="notification-el">{{ $promotionsCount }}</div><a href="{{ route('promotion-list-show') }}"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m484.26 215.41c-6-4.9336-15.07-12.391-16.105-16.262-1.1602-4.3477 2.918-15.281 5.8945-23.262 5.7656-15.473 12.305-33.008 3.6719-47.926-8.7383-15.102-27.355-18.203-43.777-20.938-7.8242-1.3047-19.652-3.2734-22.672-6.2969-3.0234-3.0234-4.9922-14.848-6.2969-22.672-2.7344-16.422-5.8359-35.039-20.938-43.777-14.918-8.6328-32.453-2.0938-47.922 3.6719-7.9844 2.9766-18.922 7.0508-23.266 5.8945-3.8711-1.0352-11.328-10.105-16.262-16.105-10.691-13.004-22.809-27.738-40.59-27.738s-29.898 14.734-40.59 27.738c-4.9336 6-12.391 15.07-16.262 16.105-4.3477 1.1602-15.281-2.918-23.262-5.8945-15.473-5.7656-33.008-12.305-47.926-3.6719-15.102 8.7383-18.203 27.355-20.938 43.777-1.3047 7.8242-3.2734 19.652-6.2969 22.672-3.0234 3.0234-14.848 4.9922-22.672 6.2969-16.422 2.7344-35.039 5.8359-43.777 20.938-8.6328 14.918-2.0938 32.453 3.6719 47.922 2.9766 7.9844 7.0547 18.918 5.8945 23.266-1.0352 3.8711-10.105 11.328-16.105 16.262-13.004 10.691-27.738 22.809-27.738 40.59s14.734 29.898 27.738 40.59c6 4.9336 15.07 12.391 16.105 16.262 1.1602 4.3477-2.918 15.281-5.8945 23.262-5.7656 15.473-12.305 33.008-3.6719 47.926 8.7383 15.102 27.355 18.203 43.777 20.938 7.8242 1.3047 19.652 3.2734 22.672 6.2969 3.0234 3.0234 4.9922 14.848 6.2969 22.672 2.7344 16.422 5.8359 35.039 20.938 43.777 14.918 8.6289 32.453 2.0938 47.922-3.6719 7.9844-2.9766 18.918-7.0547 23.266-5.8945 3.8711 1.0352 11.328 10.105 16.262 16.105 10.691 13.004 22.809 27.738 40.59 27.738s29.898-14.734 40.59-27.738c4.9336-6 12.391-15.07 16.262-16.105 4.3477-1.1562 15.281 2.918 23.262 5.8945 15.473 5.7656 33.008 12.305 47.926 3.6719 15.102-8.7383 18.203-27.355 20.938-43.777 1.3047-7.8242 3.2734-19.652 6.2969-22.672 3.0234-3.0234 14.848-4.9922 22.672-6.2969 16.422-2.7344 35.039-5.8359 43.777-20.938 8.6328-14.918 2.0938-32.453-3.6719-47.922-2.9766-7.9844-7.0547-18.918-5.8945-23.266 1.0352-3.8711 10.105-11.328 16.105-16.262 13.004-10.691 27.738-22.809 27.738-40.59s-14.734-29.898-27.738-40.59zm-18.762 58.363c-11.012 9.0547-22.395 18.418-25.879 31.453-3.5977 13.453 1.6641 27.562 6.75 41.207 2.3789 6.3867 7.332 19.672 5.7812 22.816-1.7617 3.043-16.684 5.5273-23.059 6.5898-14.254 2.375-28.992 4.8281-38.707 14.547-9.7188 9.7188-12.176 24.453-14.547 38.707-1.0625 6.375-3.5469 21.301-6.5859 23.059-0.011718 0.003906-1.4648 0.62891-6.1836-0.36328-4.832-1.0156-10.566-3.1523-16.637-5.418-13.645-5.0859-27.754-10.348-41.211-6.75-13.031 3.4844-22.395 14.871-31.449 25.883-4.5273 5.5039-13.945 16.957-17.773 16.957s-13.246-11.453-17.773-16.961c-9.0547-11.012-18.418-22.395-31.453-25.879-3.0938-0.82812-6.2227-1.1875-9.3711-1.1875-10.547 0-21.332 4.0195-31.836 7.9375-6.3828 2.3789-19.668 7.3359-22.816 5.7812-3.043-1.7617-5.5273-16.684-6.5898-23.059-2.3711-14.254-4.8281-28.992-14.547-38.707-9.7148-9.7188-24.453-12.176-38.707-14.547-6.375-1.0625-21.301-3.5469-23.055-6.5859-0.007812-0.011718-0.63281-1.4648 0.35938-6.1836 1.0156-4.832 3.1562-10.566 5.418-16.637 5.0859-13.645 10.348-27.754 6.75-41.211-3.4844-13.031-14.871-22.395-25.879-31.449-5.5078-4.5273-16.961-13.945-16.961-17.773s11.453-13.246 16.961-17.773c11.012-9.0547 22.395-18.418 25.879-31.453 3.5977-13.453-1.6641-27.562-6.75-41.207-2.3789-6.3867-7.332-19.672-5.7812-22.816 1.7617-3.043 16.684-5.5273 23.059-6.5898 14.254-2.375 28.992-4.8281 38.707-14.547 9.7188-9.7188 12.176-24.453 14.547-38.707 1.0625-6.375 3.5469-21.301 6.5859-23.059 0.011718-0.003906 1.4648-0.62891 6.1836 0.36328 4.832 1.0156 10.566 3.1523 16.637 5.418 13.645 5.0859 27.754 10.344 41.211 6.75 13.031-3.4844 22.395-14.871 31.449-25.883 4.5273-5.5039 13.945-16.957 17.773-16.957s13.246 11.453 17.773 16.961c9.0547 11.012 18.418 22.395 31.453 25.879 13.457 3.5977 27.562-1.6641 41.207-6.75 6.3867-2.3789 19.672-7.332 22.816-5.7812 3.043 1.7617 5.5273 16.684 6.5898 23.059 2.375 14.254 4.8281 28.992 14.547 38.707 9.7188 9.7188 24.453 12.176 38.707 14.547 6.375 1.0625 21.301 3.5469 23.059 6.5859 0.003906 0.011718 0.62891 1.4648-0.36328 6.1836-1.0156 4.832-3.1523 10.566-5.418 16.637-5.0859 13.645-10.348 27.754-6.75 41.211 3.4844 13.031 14.871 22.395 25.883 31.449 5.5039 4.5273 16.957 13.945 16.957 17.773s-11.453 13.246-16.961 17.773z"/><path d="m344.77 167.23c-5.7656-5.7656-15.121-5.7656-20.887 0l-156.65 156.65c-5.7656 5.7656-5.7656 15.117 0 20.887 2.8828 2.8828 6.6641 4.3242 10.445 4.3242 3.7773 0 7.5586-1.4414 10.441-4.3242l156.65-156.65c5.7656-5.7656 5.7656-15.117 0-20.887z"/><path d="m195.08 237.54c23.41 0 42.461-19.051 42.461-42.461 0-23.414-19.051-42.461-42.461-42.461-23.414 0-42.461 19.047-42.461 42.461 0 23.41 19.047 42.461 42.461 42.461zm0-55.387c7.125 0 12.922 5.8008 12.922 12.926s-5.7969 12.922-12.922 12.922-12.926-5.7969-12.926-12.922 5.8008-12.926 12.926-12.926z"/><path d="m316.92 274.46c-23.41 0-42.461 19.051-42.461 42.461 0 23.414 19.051 42.461 42.461 42.461 23.414 0 42.461-19.047 42.461-42.461 0-23.41-19.047-42.461-42.461-42.461zm0 55.387c-7.125 0-12.922-5.8008-12.922-12.926s5.7969-12.922 12.922-12.922 12.926 5.7969 12.926 12.922-5.8008 12.926-12.926 12.926z"/></svg></a></li>
                        <li class="list-inline-item mr-1 position-relative" data-toggle="tooltip" data-placement="bottom" title="Новые события"><div class="notification-el">{{ $eventCount }}</div><a href="{{ route('event-list') }}"><svg enable-background="new 0 0 454.136 454.136" version="1.1" viewBox="0 0 454.136 454.136" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m391.02 321.95c-27.089-27.089-42.308-63.83-42.308-102.14v-35.833c0-57-39.206-104.84-92.121-118.03v-36.418c0-16.306-13.22-29.526-29.527-29.526s-29.526 13.22-29.526 29.526v36.417c-52.915 13.195-92.121 61.031-92.121 118.03v35.833c0 38.31-15.219 75.05-42.308 102.14-11.907 11.907-15.469 29.814-9.025 45.372 6.444 15.557 21.625 25.701 38.464 25.701h73.4c0 33.754 27.363 61.116 61.116 61.116s61.116-27.363 61.116-61.116h73.4c16.839 0 32.02-10.143 38.464-25.701 6.445-15.557 2.883-33.464-9.024-45.371zm-163.96 102.19c-17.158 0-31.116-13.959-31.116-31.116h62.232c0 17.157-13.958 31.116-31.116 31.116zm145.26-68.298c-1.807 4.363-6.026 7.181-10.748 7.181h-269.03c-4.722 0-8.941-2.819-10.748-7.181s-0.817-9.339 2.522-12.678c32.949-32.949 51.094-76.757 51.094-123.35v-35.833c0-50.535 41.113-91.648 91.648-91.648s91.648 41.113 91.648 91.648v35.833c0 46.596 18.146 90.404 51.095 123.35 3.339 3.339 4.328 8.315 2.521 12.678z"/></svg></a></li>
                    </ul>
                </div>
                <div class="col-lg-auto text-lg-left text-center py-4 py-lg-0 col-12">
                    <div class="header__top_userpick d-inline-block">Л</div>
                    <div class="dropdown d-inline-block">
                        <a
                                class="dropdown-toggle header__myaccount_link {{ (\Illuminate\Support\Facades\Auth::check() && !\Illuminate\Support\Facades\Auth::user()->verified) ? 'cn' : '' }}"
                                href="#" role="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            @if(\Illuminate\Support\Facades\Auth::check())
                                Кабинет {{ \Illuminate\Support\Facades\Auth::user()->surname_initials }}
                            @else
                                Личный кабинет
                            @endif
                        </a>
                        @if(\Illuminate\Support\Facades\Auth::check())
                            <div class="dropdown-menu dropdown-menu__personal-account">
                                @if(\Illuminate\Support\Facades\Auth::user()->verified)
                                    <div class="d-flex dropdown-menu__row justify-content-center">
                                        <a href="{{ route('profile-data-show') }}" class="link-style">Кабинет</a>
                                    </div>
                                    <div class="d-flex dropdown-menu__row justify-content-center">
                                        <a href="{{ route('orders-list') }}" class="link-style text-center">Мои грузы</a>
                                    </div>
                                    <div class="d-flex dropdown-menu__row justify-content-center">
                                        <a href="{{ route('counterparty-list') }}" class="link-style">Мои контрагенты</a>
                                    </div>
                                    <div class="d-flex dropdown-menu__row justify-content-center">
                                        <a href="{{ route('logout') }}" class="link-style">Выйти</a>
                                    </div>
                                @else
                                    <form action="{{ route('phone-confirmation') }}" method="post">
                                        @csrf
                                        @if(session('success'))
                                            <div class="row dropdown-menu__row justify-content-center">
                                                <span class="text-success">{{session('success')}}</span>
                                            </div>
                                        @endif
                                        <div class="row dropdown-menu__row justify-content-center">
                                            <span class="dropdown-menu__title">Подтверждение регистрации</span>
                                        </div>
                                        <div class="d-flex dropdown-menu__row">
                                            <input name="code" class="form-control sms-code-mask {{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Код подтверждения" required>
                                        </div>
                                        @if ($errors->has('code'))
                                            <div class="d-flex dropdown-menu__row">
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong class="white-space-normal">{{ $errors->first('code') }}</strong>
                                            </span>
                                            </div>
                                        @endif
                                        <div class="row dropdown-menu__row justify-content-center">
                                        <span class="annotation-text">
                                            Код отправлен на номер +{{ \Illuminate\Support\Facades\Auth::user()->phone }}
                                            <a href="{{ route('profile-data-show') }}">(ред.)</a>
                                        </span>
                                        </div>
                                        <div class="d-flex">
                                            <a href="{{ route('resend-phone-confirm-code') }}" class="link-style">Запросить код еще раз</a>
                                        </div>
                                        <div class="d-flex dropdown-menu__row">
                                            <button type="submit" class="btn btn-block btn-danger">Подтвердить регистрацию</button>
                                        </div>
                                        <div class="separator-hr"></div>
                                        <div class="d-flex dropdown-menu__row justify-content-center">
                                            <a href="##" class="link-style">Вернуться</a>
                                        </div>
                                        <div class="d-flex dropdown-menu__row justify-content-center">
                                            <a href="{{ route('logout') }}" class="link-style">Выйти</a>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="dropdown-menu dropdown-menu__personal-account">
                                <div class="d-flex dropdown-menu__row justify-content-center">
                                    <a href="{{ route('login') }}" class="link-style">Войти</a>
                                </div>
                                {{--<div class="separator-hr"></div>--}}
                                <div class="d-flex dropdown-menu__row justify-content-center">
                                    <a href="{{ route('register') }}" class="link-style">Зарегистрироваться</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="row">
    <div class="col-12 header__main_left">
        <h4 class="whiteTxtColor d-lg-none d-block mb-4 m-phone">
            <i class="fa fa-phone"></i>
            @if(isset($closestTerminal->phone))
                @php
                    $phones = preg_split("/(;|,)/", str_replace(' ', '', $closestTerminal->phone));
                @endphp
                @if(!empty($phones[0]))
                    <a href="tel:{{ $phones[0] }}" class="text-white">{{ $phones[0] }}</a>
                @endif
            @endif
        </h4>
    </div>
</div>
