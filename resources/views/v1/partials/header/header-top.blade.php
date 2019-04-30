<div class="row header__top">
    <div class="d-flex flex-wrap flex-xl-row col-xl-12 @if(!$isMainPage) justify-content-between @endif">
        <div class="col-12 d-flex col-xl flex-column flex-sm-row flex-md-column flex-xl-row justify-content-sm-between align-items-xl-center">
            <div class="col-sm-6 col-md-12 logo_main text-center text-sm-left">
                <a href="{{ route('index') }}"><img src="{{ asset('/images/img/logo.png') }}" alt="Доставка грузов"></a>
            </div>
            <div class="col-sm-6 col-md-12 col-xl d-flex d-xl-flex flex-column flex-sm-row flex-md-column align-items-sm-center align-items-md-start header__fullnav">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-sm-start">
                    <div class="city-dropdown dropdown show">
                        <a class="dropdown-toggle city_choice"
                           href="#"
                           role="button"
                           data-toggle="modal"
                           data-target="#selection-city">{{ $city->name }}</a>
                    </div>
                    <div class="phones d-flex flex-column flex-md-row justify-content-center">
                        <a href="tel:8 (812) 644-67-77">8 (812) 644-67-77</a>
                        <a href="tel:8 (800) 000-00-00">8 (800) 000-00-00</a>
                    </div>
                </div>
                <nav role="navigation" class="nav navbar-expand-sm">
                    <button
                            class="navbar-toggle"
                            type="button"
                            data-toggle="collapse"
                            data-target="#main_nav"
                            aria-controls="main_nav">
                        @if($isMainPage)
                            <span class="fa fa-bars"></span>
                        @else
                            <span class="fa fa-bars margin-item"></span>
                            <span class="margin-item">Меню</span>
                        @endif
                    </button>
                    <div id="main_nav" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav d-flex nav_top m-0">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Услуги</a>
                                <div role="menu" class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/uslugi/mezh-terminalnaya-perevozka') }}">Меж-терминальная перевозка</a>
                                    <a class="dropdown-item" href="{{ url('/uslugi/aviaperevozka') }}">Авиаперевозка</a>
                                    <a class="dropdown-item" href="{{ url('/uslugi/dostavka-dokumentov') }}">Доставка документов</a>
                                    <a class="dropdown-item" href="{{ url('/uslugi/dostavka-v-gipermarkety') }}">Доставка в гипермаркеты</a>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Клиентам</a>
                                <div role="menu" class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/klientam/dopolnitelnye-uslugi') }}">Дополнительные услуги</a>
                                    <a class="dropdown-item" href="{{ url('/klientam/faq') }}">FAQ</a>
                                    <a class="dropdown-item" href="{{ route('event-list') }}">Лента событий</a>
                                    <a class="dropdown-item" href="{{ url('/klientam/napravleniya') }}">Направления</a>
                                    <a class="dropdown-item" href="{{ route('report-list') }}">Отчеты</a>
                                    <a class="dropdown-item" href="{{ route('reviews') }}">Отзывы</a>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">О компании</a>
                                <div role="menu" class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/o-kompanii') }}">О компании</a>
                                    <a class="dropdown-item" href="{{ route('news-list-show') }}">Новости</a>
                                    <a class="dropdown-item" href="{{ route('promotion-list-show') }}">Акции</a>
                                    <a class="dropdown-item" href="{{ url('/o-kompanii/reklamodatelyam') }}">Рекламодателям</a>
                                    <a class="dropdown-item" href="{{ url('/adres-terminalov') }}">Адреса терминалов</a>
                                    <a class="dropdown-item" href="{{ url('/o-kompanii/dokumenty-i-sertifikaty') }}">Документы и сертификаты</a>
                                    <a class="dropdown-item" href="{{ url('/partnery') }}">Партнеры</a>
                                    <a class="dropdown-item" href="#">Сотрудники</a>
                                </div>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Контакты</a>
                                <div role="menu" class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('/adres-terminalov') }}">Адрес терминалов</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div class="col-12 col-md-8 col-xl d-flex flex-column flex-sm-row header__top_right">
            <ul class="col-sm-6 nav-icons-list d-flex flex-row justify-content-center justify-content-sm-end align-items-sm-center">
                <li class="relative" data-toggle="tooltip" data-placement="bottom" title="Калькулятор"><a href="{{ route('calculator-show') }}"><svg viewBox="-35 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m427 0h-412c-8.285156 0-15 6.714844-15 15v482c0 8.285156 6.714844 15 15 15h412c8.285156 0 15-6.714844 15-15v-482c0-8.285156-6.714844-15-15-15zm-15 482h-382v-452h382zm0 0"/><path d="m75 218h292c8.285156 0 15-6.714844 15-15v-128c0-8.285156-6.714844-15-15-15h-292c-8.285156 0-15 6.714844-15 15v128c0 8.285156 6.714844 15 15 15zm15-128h262v98h-262zm0 0"/><path d="m367 240h-58c-8.285156 0-15 6.714844-15 15v183c0 8.285156 6.714844 15 15 15h58c8.285156 0 15-6.714844 15-15v-183c0-8.285156-6.714844-15-15-15zm-15 183h-28v-153h28zm0 0"/><path d="m75 452h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15zm15-60h30v30h-30zm0 0"/><path d="m255 362h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15zm-15 60h-30v-30h30zm0 0"/><path d="m75 332h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15zm15-60h30v30h-30zm0 0"/><path d="m255 242h-60c-8.285156 0-15 6.714844-15 15v60c0 8.285156 6.714844 15 15 15h60c8.285156 0 15-6.714844 15-15v-60c0-8.285156-6.714844-15-15-15zm-15 60h-30v-30h30zm0 0"/></svg></a></li>
                <li class="relative" data-toggle="tooltip" data-placement="bottom" title="Перевозка"><a href="#"><svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m492.52 118.3l-226.09-114.56-0.094-0.047c-10.067-5.012-22.029-4.9-32.002 0.3l-96.969 51.464c-0.788 0.334-1.545 0.739-2.27 1.205l-116.2 61.672c-11.656 6.103-18.896 18.061-18.896 31.222v212.88c0 13.161 7.24 25.118 18.896 31.221l215.34 114.29 0.097 0.051c5.086 2.652 10.691 3.981 16.297 3.981 5.385 0 10.772-1.226 15.704-3.682l226.18-114.6c12.016-6.009 19.478-18.081 19.478-31.519v-212.36c0-13.439-7.462-25.512-19.478-31.52zm-244.28-87.731c1.469-0.754 3.223-0.769 4.705-0.042l211.63 107.23-82.364 41.005-206.9-109.49 72.929-38.706zm-12.813 444.06l-202.51-107.48-0.097-0.051c-1.741-0.909-2.824-2.692-2.824-4.656v-199.35l205.44 107.12v204.42zm15.153-230.34l-204.3-106.54 97.024-51.493 205.88 108.94-98.594 49.085zm231.43 117.88c1e-3 2.007-1.115 3.809-2.911 4.703l-213.68 108.27v-204.74l98.386-48.982v51.355c0 8.281 6.714 14.995 14.995 14.995s14.995-6.714 14.995-14.995v-66.286l88.219-43.92v199.6z"/></svg></a></li>
                <li class="relative" data-toggle="tooltip" data-placement="bottom" title="Скидки"><div class="notification-el">{{ $promotionsCount }}</div><a href="{{ route('promotion-list-show') }}"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m484.26 215.41c-6-4.9336-15.07-12.391-16.105-16.262-1.1602-4.3477 2.918-15.281 5.8945-23.262 5.7656-15.473 12.305-33.008 3.6719-47.926-8.7383-15.102-27.355-18.203-43.777-20.938-7.8242-1.3047-19.652-3.2734-22.672-6.2969-3.0234-3.0234-4.9922-14.848-6.2969-22.672-2.7344-16.422-5.8359-35.039-20.938-43.777-14.918-8.6328-32.453-2.0938-47.922 3.6719-7.9844 2.9766-18.922 7.0508-23.266 5.8945-3.8711-1.0352-11.328-10.105-16.262-16.105-10.691-13.004-22.809-27.738-40.59-27.738s-29.898 14.734-40.59 27.738c-4.9336 6-12.391 15.07-16.262 16.105-4.3477 1.1602-15.281-2.918-23.262-5.8945-15.473-5.7656-33.008-12.305-47.926-3.6719-15.102 8.7383-18.203 27.355-20.938 43.777-1.3047 7.8242-3.2734 19.652-6.2969 22.672-3.0234 3.0234-14.848 4.9922-22.672 6.2969-16.422 2.7344-35.039 5.8359-43.777 20.938-8.6328 14.918-2.0938 32.453 3.6719 47.922 2.9766 7.9844 7.0547 18.918 5.8945 23.266-1.0352 3.8711-10.105 11.328-16.105 16.262-13.004 10.691-27.738 22.809-27.738 40.59s14.734 29.898 27.738 40.59c6 4.9336 15.07 12.391 16.105 16.262 1.1602 4.3477-2.918 15.281-5.8945 23.262-5.7656 15.473-12.305 33.008-3.6719 47.926 8.7383 15.102 27.355 18.203 43.777 20.938 7.8242 1.3047 19.652 3.2734 22.672 6.2969 3.0234 3.0234 4.9922 14.848 6.2969 22.672 2.7344 16.422 5.8359 35.039 20.938 43.777 14.918 8.6289 32.453 2.0938 47.922-3.6719 7.9844-2.9766 18.918-7.0547 23.266-5.8945 3.8711 1.0352 11.328 10.105 16.262 16.105 10.691 13.004 22.809 27.738 40.59 27.738s29.898-14.734 40.59-27.738c4.9336-6 12.391-15.07 16.262-16.105 4.3477-1.1562 15.281 2.918 23.262 5.8945 15.473 5.7656 33.008 12.305 47.926 3.6719 15.102-8.7383 18.203-27.355 20.938-43.777 1.3047-7.8242 3.2734-19.652 6.2969-22.672 3.0234-3.0234 14.848-4.9922 22.672-6.2969 16.422-2.7344 35.039-5.8359 43.777-20.938 8.6328-14.918 2.0938-32.453-3.6719-47.922-2.9766-7.9844-7.0547-18.918-5.8945-23.266 1.0352-3.8711 10.105-11.328 16.105-16.262 13.004-10.691 27.738-22.809 27.738-40.59s-14.734-29.898-27.738-40.59zm-18.762 58.363c-11.012 9.0547-22.395 18.418-25.879 31.453-3.5977 13.453 1.6641 27.562 6.75 41.207 2.3789 6.3867 7.332 19.672 5.7812 22.816-1.7617 3.043-16.684 5.5273-23.059 6.5898-14.254 2.375-28.992 4.8281-38.707 14.547-9.7188 9.7188-12.176 24.453-14.547 38.707-1.0625 6.375-3.5469 21.301-6.5859 23.059-0.011718 0.003906-1.4648 0.62891-6.1836-0.36328-4.832-1.0156-10.566-3.1523-16.637-5.418-13.645-5.0859-27.754-10.348-41.211-6.75-13.031 3.4844-22.395 14.871-31.449 25.883-4.5273 5.5039-13.945 16.957-17.773 16.957s-13.246-11.453-17.773-16.961c-9.0547-11.012-18.418-22.395-31.453-25.879-3.0938-0.82812-6.2227-1.1875-9.3711-1.1875-10.547 0-21.332 4.0195-31.836 7.9375-6.3828 2.3789-19.668 7.3359-22.816 5.7812-3.043-1.7617-5.5273-16.684-6.5898-23.059-2.3711-14.254-4.8281-28.992-14.547-38.707-9.7148-9.7188-24.453-12.176-38.707-14.547-6.375-1.0625-21.301-3.5469-23.055-6.5859-0.007812-0.011718-0.63281-1.4648 0.35938-6.1836 1.0156-4.832 3.1562-10.566 5.418-16.637 5.0859-13.645 10.348-27.754 6.75-41.211-3.4844-13.031-14.871-22.395-25.879-31.449-5.5078-4.5273-16.961-13.945-16.961-17.773s11.453-13.246 16.961-17.773c11.012-9.0547 22.395-18.418 25.879-31.453 3.5977-13.453-1.6641-27.562-6.75-41.207-2.3789-6.3867-7.332-19.672-5.7812-22.816 1.7617-3.043 16.684-5.5273 23.059-6.5898 14.254-2.375 28.992-4.8281 38.707-14.547 9.7188-9.7188 12.176-24.453 14.547-38.707 1.0625-6.375 3.5469-21.301 6.5859-23.059 0.011718-0.003906 1.4648-0.62891 6.1836 0.36328 4.832 1.0156 10.566 3.1523 16.637 5.418 13.645 5.0859 27.754 10.344 41.211 6.75 13.031-3.4844 22.395-14.871 31.449-25.883 4.5273-5.5039 13.945-16.957 17.773-16.957s13.246 11.453 17.773 16.961c9.0547 11.012 18.418 22.395 31.453 25.879 13.457 3.5977 27.562-1.6641 41.207-6.75 6.3867-2.3789 19.672-7.332 22.816-5.7812 3.043 1.7617 5.5273 16.684 6.5898 23.059 2.375 14.254 4.8281 28.992 14.547 38.707 9.7188 9.7188 24.453 12.176 38.707 14.547 6.375 1.0625 21.301 3.5469 23.059 6.5859 0.003906 0.011718 0.62891 1.4648-0.36328 6.1836-1.0156 4.832-3.1523 10.566-5.418 16.637-5.0859 13.645-10.348 27.754-6.75 41.211 3.4844 13.031 14.871 22.395 25.883 31.449 5.5039 4.5273 16.957 13.945 16.957 17.773s-11.453 13.246-16.961 17.773z"/><path d="m344.77 167.23c-5.7656-5.7656-15.121-5.7656-20.887 0l-156.65 156.65c-5.7656 5.7656-5.7656 15.117 0 20.887 2.8828 2.8828 6.6641 4.3242 10.445 4.3242 3.7773 0 7.5586-1.4414 10.441-4.3242l156.65-156.65c5.7656-5.7656 5.7656-15.117 0-20.887z"/><path d="m195.08 237.54c23.41 0 42.461-19.051 42.461-42.461 0-23.414-19.051-42.461-42.461-42.461-23.414 0-42.461 19.047-42.461 42.461 0 23.41 19.047 42.461 42.461 42.461zm0-55.387c7.125 0 12.922 5.8008 12.922 12.926s-5.7969 12.922-12.922 12.922-12.926-5.7969-12.926-12.922 5.8008-12.926 12.926-12.926z"/><path d="m316.92 274.46c-23.41 0-42.461 19.051-42.461 42.461 0 23.414 19.051 42.461 42.461 42.461 23.414 0 42.461-19.047 42.461-42.461 0-23.41-19.047-42.461-42.461-42.461zm0 55.387c-7.125 0-12.922-5.8008-12.922-12.926s5.7969-12.922 12.922-12.922 12.926 5.7969 12.926 12.922-5.8008 12.926-12.926 12.926z"/></svg></a></li>
                <li class="relative" data-toggle="tooltip" data-placement="bottom" title="Новые события"><div class="notification-el">{{ $eventCount }}</div><a href="{{ route('event-list') }}"><svg enable-background="new 0 0 454.136 454.136" version="1.1" viewBox="0 0 454.136 454.136" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m391.02 321.95c-27.089-27.089-42.308-63.83-42.308-102.14v-35.833c0-57-39.206-104.84-92.121-118.03v-36.418c0-16.306-13.22-29.526-29.527-29.526s-29.526 13.22-29.526 29.526v36.417c-52.915 13.195-92.121 61.031-92.121 118.03v35.833c0 38.31-15.219 75.05-42.308 102.14-11.907 11.907-15.469 29.814-9.025 45.372 6.444 15.557 21.625 25.701 38.464 25.701h73.4c0 33.754 27.363 61.116 61.116 61.116s61.116-27.363 61.116-61.116h73.4c16.839 0 32.02-10.143 38.464-25.701 6.445-15.557 2.883-33.464-9.024-45.371zm-163.96 102.19c-17.158 0-31.116-13.959-31.116-31.116h62.232c0 17.157-13.958 31.116-31.116 31.116zm145.26-68.298c-1.807 4.363-6.026 7.181-10.748 7.181h-269.03c-4.722 0-8.941-2.819-10.748-7.181s-0.817-9.339 2.522-12.678c32.949-32.949 51.094-76.757 51.094-123.35v-35.833c0-50.535 41.113-91.648 91.648-91.648s91.648 41.113 91.648 91.648v35.833c0 46.596 18.146 90.404 51.095 123.35 3.339 3.339 4.328 8.315 2.521 12.678z"/></svg></a></li>
            </ul>
            <div class="col-sm-6 d-flex flex-row justify-content-center justify-content-sm-end header__top_lk align-items-sm-center">
                <div class="header__top_userpick">Л</div>
                <div class="dropdown">
                    <a
                            class="dropdown-toggle header__myaccount_link"
                            href="#" role="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">Личный кабинет
                    </a>
                    <!-- <div class="dropdown-menu dropdown-menu__personal-account">
                        <div class="row dropdown-menu__row justify-content-center">
                            <span class="dropdown-menu__title">Вход в Личный Кабинет</span>
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <input type="text" class="form-control user" placeholder="Имя пользователя">
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <input type="text" class="form-control pass" placeholder="Пароль">
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <button class="btn btn-block btn-danger">Войти</button>
                        </div>
                        <div class="separator-hr"></div>
                        <div class="d-flex justify-content-between dropdown-menu__row">
                            <a href="##" class="link-style">Регистрация</a>
                            <a href="##" class="link-style">Забыли пароль?</a>
                        </div>
                    </div> -->
                    <!-- <div class="dropdown-menu dropdown-menu__personal-account">
                        <div class="row dropdown-menu__row justify-content-center">
                            <span class="dropdown-menu__title">Регистрация</span>
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <input type="text" class="form-control phone" placeholder="+7 (___) ___-__-__">
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <input type="text" class="form-control pass" placeholder="Пароль">
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <span class="annotation-text">Не менее 8 символов, минимум 1 буква, минимум 1 цифра</span>
                        </div>
                        <div class="input-group dropdown-menu__row">
                            <input type="text" class="form-control" placeholder="LCG7">
                            <input type="text" class="form-control" placeholder="&nbsp;">
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <button class="btn btn-block btn-danger">Отправить код подтверждения</button>
                        </div>
                        <div class="separator-hr"></div>
                        <div class="d-flex justify-content-center dropdown-menu__row">
                            <a href="##" class="link-style">Вход</a>
                        </div>
                    </div> -->
                    <!-- <div class="dropdown-menu dropdown-menu__personal-account">
                        <div class="row dropdown-menu__row justify-content-center">
                            <span class="dropdown-menu__title">Восстановление пароля</span>
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <input type="text" class="form-control phone" placeholder="+7 (___) ___-__-__">
                        </div>
                        <div class="input-group dropdown-menu__row">
                            <input type="text" class="form-control" placeholder="LCG7">
                            <input type="text" class="form-control" placeholder="&nbsp;">
                        </div>
                        <div class="d-flex dropdown-menu__row">
                            <button class="btn btn-block btn-danger">Восстановить пароль</button>
                        </div>
                        <div class="separator-hr"></div>
                        <div class="d-flex dropdown-menu__row justify-content-center">
                            <a href="##" class="link-style">Вход</a>
                        </div>
                    </div> -->
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <div class="dropdown-menu dropdown-menu__personal-account">
                            <div class="d-flex dropdown-menu__row justify-content-center">
                                <a href="{{ route('profile-data-show') }}" class="link-style">Кабинет</a>
                            </div>
                            <div class="d-flex dropdown-menu__row justify-content-center">
                                <a href="{{ route('logout') }}" class="link-style">Выйти</a>
                            </div>
                            {{--<div class="row dropdown-menu__row justify-content-center">--}}
                                {{--<span class="dropdown-menu__title">Подтверждение регистрации</span>--}}
                            {{--</div>--}}
                            {{--<div class="d-flex dropdown-menu__row">--}}
                                {{--<input type="text" class="form-control" placeholder="Код подтверждения">--}}
                            {{--</div>--}}
                            {{--<div class="row dropdown-menu__row justify-content-center">--}}
                                {{--<span class="annotation-text">Код отправлен на номер +7 (000) 000-00-00</span>--}}
                            {{--</div>--}}
                            {{--<div class="d-flex">--}}
                                {{--<a href="##" class="link-style">Запросить код еще раз</a>--}}
                            {{--</div>--}}
                            {{--<div class="d-flex dropdown-menu__row">--}}
                                {{--<button class="btn btn-block btn-danger">Подтвердить регистрацию</button>--}}
                            {{--</div>--}}
                            {{--<div class="separator-hr"></div>--}}
                            {{--<div class="d-flex dropdown-menu__row justify-content-center">--}}
                                {{--<a href="##" class="link-style">Вернуться</a>--}}
                            {{--</div>--}}
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