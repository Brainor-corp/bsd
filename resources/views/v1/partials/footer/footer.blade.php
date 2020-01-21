<footer class="footer-b bg-dark">
    <div class="container">
        <div class="row justify-content-between">
            @php
                $footerMenu = \Zeus\Admin\Cms\Helpers\MenuHelper::getMenuTreeBySlug('menyu-podvala')
            @endphp
            @include('v1.partials.footer.footer-menu', ['nodeTree' => $footerMenu])
            <div class="col-12 col-xl footer-item text-lg-right footer-contacts">
                <h4 class="whiteTxtColor mb-10"><i class="fa fa-phone"></i>
                    @if(isset($closestTerminal->phone))
                        @php
                            $phones = preg_split("/(;|,)/", str_replace(' ', '', $closestTerminal->phone));
                        @endphp
                        @if(!empty($phones[0]))
                            {{ $phones[0] }}
                        @endif
                    @endif
                </h4>
                <p class="darkTxtColor">
                    @if(isset($closestTerminal->address))
                        {{ $closestTerminal->address }}
                    @endif
                </p>
                <div class="d-flex justify-content-lg-end">
                    <a href="https://vk.com/bsdtrans" class="social-link margin-item d-flex justify-content-center align-items-center"><i class="fa fa-vk"></i></a>
                    <a href="https://www.instagram.com/tk_bsd_russia" class="social-link margin-item d-flex justify-content-center align-items-center"><i class="fa fa-instagram"></i></a>
                    <iframe class="margin-item" src="https://yandex.ru/sprav/widget/rating-badge/1148338667" width="150" height="50" frameborder="0"></iframe>
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
            <div class="col-12 col-xl footer-item">
                <a class="ftr_link" href="{{ url('/politika-konfidencialnosti') }}">Политика конфиденциальности</a> <br>
                <span>ОГРН: 1089847071181</span> <br>
                <span>ИНН: 7816435129</span> <br>
                <span>КПП: 781601001</span>
            </div>
            <div class="col-12 col-xl footer-item d-flex justify-content-xl-end">
                <span class="">Сайт разработала студия</span>
                <div class="newclients"></div>
            </div>
        </div>
    </div>
</footer>
