@extends('v1.layouts.innerPageLayout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('make-payment') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="sum">Сумма:</label>
                        <input type="number" name="sum" id="sum" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary">Оплатить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection