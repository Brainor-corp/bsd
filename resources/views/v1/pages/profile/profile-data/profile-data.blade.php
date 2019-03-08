@extends('v1.layouts.innerPageLayout')


@section('content')
    <div class="breadcrumb__list d-flex">
        <div class="container">
            <span class="breadcrumb__item"><a href="{{ route('index') }}" class="">Главная</a></span>
            <span class="breadcrumb__item"><a href="##" class="">Личный кабинет</a></span>
            <span class="breadcrumb__item">Профиль</span>
        </div>
    </div>
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>Профиль</h1>
                    </header>
                    <div class="row">
                        <div class="col-4">
                            <form>
                                <div class="row">
                                    <label class="col-auto profile__label">ФИО</label>
                                    <div class="col">
                                        <input type="text" class="form-control form-group" value="Фамилия">
                                        <input type="text" class="form-control form-group" value="Имя">
                                        <input type="text" class="form-control form-group" value="Отчество">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-auto profile__label">Email</label>
                                    <div class="col">
                                        <span class="form-control-text margin-item">postelnyak91@mail.ru</span>
                                        <i class="fa fa-pencil-square-o margin-item"></i>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-auto profile__label">Телефон</label>
                                    <div class="col">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-auto profile__label">Пароль</label>
                                    <div class="col">
                                        <input type="text" class="form-control form-group" value="Старый пароль">
                                        <input type="text" class="form-control form-group" value="Новый пароль">
                                    </div>
                                </div>
                                <button class="footer-btn btn margin-item btn-danger">Сохранить изменения</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection