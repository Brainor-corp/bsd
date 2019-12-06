@foreach($orders as $order)
    @if($order instanceof \App\Order)
        @include('v1.pages.profile.profile-inner.order-row')
    @else
        @include('v1.pages.profile.profile-inner.forwarding-receipt-row')
    @endif
@endforeach
