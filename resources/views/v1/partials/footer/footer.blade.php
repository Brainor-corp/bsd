<footer class="footer-b bg-dark">
    <div class="container">
        <div class="row justify-content-between">
            @php($footerMenu = \Zeus\Admin\Cms\Helpers\MenuHelper::getMenuTreeBySlug('menyu-podvala'))
            @include('v1.partials.footer.footer-menu', ['nodeTree' => $footerMenu])
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