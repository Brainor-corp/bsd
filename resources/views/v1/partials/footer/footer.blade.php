<footer class="footer-b bg-dark">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-12 col-sm-6 col-lg-3 col-xl footer-item">
                <div class="footer_title">Услуги</div>
                <ul class="m-0">
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/uslugi/mezh-terminalnaya-perevozka') }}">Меж-терминальная перевозка</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/uslugi/aviaperevozka') }}">Авиаперевозка</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/uslugi/dostavka-dokumentov') }}">Доставка документов</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/uslugi/dostavka-v-gipermarkety') }}">Доставка в гипермаркеты</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/uslugi/konteynernye-perevozki') }}">Контейнерные перевозки</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/uslugi/pryamaya-mashina') }}">Прямая машина</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/klientam/dopolnitelnye-uslugi') }}">Дополнительные услуги</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-lg-3 col-xl footer-item">
                <div class="footer_title">Клиентам</div>
                <ul class="m-0">
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/klientam/faq') }}">FAQ</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('reviews') }}">Отзывы</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/o-kompanii/dokumenty-i-sertifikaty') }}">Документы</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/klientam/dopolnitelnye-uslugi') }}">Дополнительные услуги</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('pricesPage') }}">Прайс лист</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/klientam/garantiya') }}">Гарантия</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/klientam/napravleniya') }}">Направления</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-lg-3 col-xl footer-item">
                <div class="footer_title">О компании</div>
                <ul class="m-0">
                    {{--<li class="ftr_list-item"><a class="ftr_link" href="{{ url('/o-kompanii/o-nas') }}">О нас</a></li>--}}
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('news-list-show') }}">Новости</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('promotion-list-show') }}">Акции</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/o-kompanii/reklamodatelyam') }}">Рекламодателям</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('terminals-addresses-show') }}">Адреса терминалов</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('documents-show') }}">Документы и сертификаты</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('partners-page') }}">Партнеры</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ url('/o-kompanii/sotrudniki') }}">Сотрудники</a></li>
                </ul>
            </div>
            <div class="col-12 col-sm-6 col-lg-3 col-xl footer-item">
                <div class="footer_title">Личный кабинет</div>
                <ul class="m-0">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        <li class="ftr_list-item"><a class="ftr_link" href="{{ route('logout') }}">Выйти</a></li>
                    @else
                        <li class="ftr_list-item"><a class="ftr_link" href="{{ route('login') }}">Войти</a></li>
                    @endif
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('event-list') }}">Лента событий</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="#">Мои заказы</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="{{ route('report-list') }}">Отчеты</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="#">Переписка с менеджером</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="#">Претензии</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="#">Для физических лиц</a></li>
                    <li class="ftr_list-item"><a class="ftr_link" href="#">Для юридических лиц</a></li>
                </ul>
            </div>
            <div class="col-12 col-xl footer-item text-lg-right footer-contacts">
                <h4 class="whiteTxtColor mb-10"><i class="fa fa-phone"></i>8 (800) 000-00-00</h4>
                <p class="darkTxtColor">
                    198095, г. Санкт-Петербург,<br />
                    Митрофаньевское <br />
                    шоссе, д. 10 A</p>
                <div class="d-flex justify-content-lg-end">
                    <a href="##" class="social-link margin-item d-flex justify-content-center align-items-center"><i class="fa fa-vk"></i></a>
                    <a href="##" class="social-link margin-item d-flex justify-content-center align-items-center"><i class="fa fa-facebook"></i></a>
                    <a href="##" class="social-link margin-item d-flex justify-content-center align-items-center"><i class="fa fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="row darkTxtColor justify-content-between footer-info">
            <div class="col-12 col-xl footer-item">
                © 2010-2019  Балтийская Служба Доставки
                <br>
                <small>
                    @include('v1.partials.google-recaptcha.terms')
                </small>
            </div>
            <div class="col-12 col-xl footer-item"><a class="ftr_link" href="{{ url('/politika-konfidencialnosti') }}">Политика конфиденциальности</a></div>
            <div class="col-12 col-xl footer-item d-flex justify-content-xl-end">
                <span class="">Сайт разработала студия</span>
                <div class="newclients"></div>
            </div>
        </div>
    </div>
</footer>