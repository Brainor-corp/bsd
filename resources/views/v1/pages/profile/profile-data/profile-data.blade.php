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
                    @include('v1.partials.profile.profile-tabs')
                    <header class="wrapper__header">
                        <h1>Профиль</h1>
                    </header>
                    <div class="row">
                        @if(isset($showPassResetMsg))
                            <div class="col-12">
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <p>
                                        Ваша учетная запись создана оператором. Если Вы хотите изменить пароль, Вы можете сделать это на этой странице.
                                    </p>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-4 col-12">

                            <div class="row">
                                @if (count($errors) > 0)
                                    <div class="col-12 pt-2">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                @if(session('success'))
                                    <div class="col-12 pt-2">
                                        <div class="alert alert-success">
                                            <ul>
                                                <li>{{session('success')}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <form method="post" action="{{ route('edit-profile-data') }}">
                                <input type="hidden" hidden="hidden" name="user_id" value="{{ auth()->user()->id}}">
                                <div class="row">
                                    <label class="col-auto profile__label">ФИО</label>
                                    <div class="col">
                                        <input type="text" class="form-control form-group" name="surname" value="{{ auth()->user()->surname ?? '' }}" placeholder="Фамилия">
                                        <input type="text" class="form-control form-group" name="name" value="{{ auth()->user()->name ?? '' }}" placeholder="Имя">
                                        <input type="text" class="form-control form-group" name="patronomic" value="{{ auth()->user()->patronomic ?? '' }}" placeholder="Отчество">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-auto profile__label">Email</label>
                                    <div class="col">
                                        <input type="text" class="form-control form-group" name="email" value="{{ auth()->user()->email ?? '' }}" placeholder="E-mail">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-auto profile__label">Телефон</label>
                                    <div class="col">
                                        <input type="text" class="form-control form-group phone-mask" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="+7(XXX)XXX-XX-XX">
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-12 profile__label form-group">Изменить пароль</label>
                                    <div class="col-12">
                                        <input type="password" class="form-control form-group" name="old_password" placeholder="Старый пароль">
                                        <input type="password" class="form-control form-group mb-0" name="password" placeholder="Новый пароль">
                                        <small id="passwordHelpBlock" class="form-text text-muted mb-3">
                                            Не менее 8 символов, минимум 1 буква, минимум 1 цифра.
                                        </small>
                                        <input type="password" class="form-control form-group" name="password_confirmation" placeholder="Новый пароль ещё раз">
                                    </div>
                                </div>
                                <a href="/uploads/2020/1/28/Заявление_на_регистрацию_ЛК_(Юридической_лицо)_2020.docx">Письмо "О регистрации личного кабинета" (юридическое лицо)</a>
                                <br>
                                <br>
                                <a href="/uploads/2019/11/21/Заявление_на_предоставление_услуги_Личный_кабинет_(физическое_лицо).docx">Письмо "О регистрации личного кабинета" (физическое лицо)</a>
                                <br>
                                <button class="footer-btn btn margin-item btn-danger mx-0">Сохранить изменения</button>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
