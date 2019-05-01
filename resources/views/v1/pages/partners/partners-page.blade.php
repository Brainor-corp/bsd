@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item">Партнеры</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Партнеры</h1>
                    </header>
                    <div class="container">
                        <div class="row partners__list">
                            @foreach($partners as $partner)
                                <div class="partners__item d-flex justify-content-center align-items-center">
                                    <img src="{{ url($partner->thumb) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <section class="section">
                                <header class="wrapper__header">
                                    <h2>Предложение вступить в наши ряды</h2>
                                </header>
                                <p>ООО «Балтийская Служба Доставки» приглашает к сотрудничеству региональные транспортные компании, специализирующиеся на доставке сборных грузов по территории Российской Федерации.</p>
                                <ul class="styles-list">
                                    <li>Опыт работы в логистике, специализация в перевозке сборных грузов.</li>
                                    <li>Наличие склада.</li>
                                    <li>Оптимальное ценовое предложение и возможность ценового компромисса.</li>
                                    <li>Готовность к изменениям, стандартизации бизнес-процессов.</li>
                                    <li>Ориентация на долгосрочные партнерские отношения.</li>
                                    <li>Соблюдение, согласованных сроков и регулярности перевозок.</li>
                                    <li>Знание рынка транспортных услуг в своем регионе.</li>
                                </ul>
                            </section>
                        </div>
                        <div class="col-12 col-lg-4">
                            <form action="{{ route('partners-page-action') }}" method="post">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @csrf
                                <div class="form-block">
                                    <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                                        <label class="calc__label_max">ФИО</label>
                                        <input name="fio" type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                                        <label class="calc__label_max">Название компании</label>
                                        <input name="company_name" type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="form-item d-flex align-items-center justify-content-between calc__block_inpg">
                                        <label class="calc__label_max">Телефон</label>
                                        <input name="phone" required type="text" class="form-control" placeholder="">
                                    </div>
                                    <button type="submit" class="btn btn-danger">Отправить заявку</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection