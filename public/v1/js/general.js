var ua = window.navigator.userAgent;
var isIE = /MSIE|Trident/.test(ua);

if(isIE) {
    document.getElementById('ie-alert').style.display = "block";
}

$(document).ready(function () {
    if($('#process-order').length) {
        $('#process-order').modal('show');
    }

    $('.city-search').selectize({
        create: false,
        maxItems: 1,
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        load: function (query, callback) {
            if (!query.length) return callback();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/api/get-cities-by-term',
                type: 'POST',
                data: {
                    term: query,
                    maxresults: 10
                },
                error: function (data) {
                    console.log(data);
                    callback();
                },
                success: function (res) {
                    console.log(res);
                    callback(res);
                }
            });
        },
        render: {
            option: function(item, escape) {
                return '<div>' +
                    '<span class="title">' +
                    '<span class="name">' + escape(item.name) + '</span>' +
                    '</span>' +
                    '</div>';
            }
        },
        onChange: function(value, isOnInitialize) {
            if(value) {
                window.location.replace($('#changeCityGlobal').data('redirect').toString() + '/' + value);
            }
        }
    });

    $('.phone-mask').mask('+7 (000) 000-00-00');
    $('.sms-code-mask').mask('000000');

    let searchParams = new URLSearchParams(window.location.search);
    if(searchParams.has('cn') && $('.header__myaccount_link').hasClass('cn')) {
        $('.header__myaccount_link').dropdown('toggle')
    }

    $('#workers-slider').on('slide.bs.carousel', function (e) {

        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 6;
        var totalItems = $('#workers-slider .carousel-item').length;

        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i = 0; i < it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('#workers-slider .carousel-item').eq(i).appendTo('#workers-slider .carousel-inner');
                }
                else {
                    $('#workers-slider .carousel-item').eq(0).appendTo('#workers-slider .carousel-inner');
                }
            }
        }
    });

    $('#certificates-slider').on('slide.bs.carousel', function (e) {

        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 6;
        var totalItems = $('#certificates-slider .carousel-item').length;

        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i = 0; i < it; i++) {
                if (e.direction=="left") {
                    $('#certificates-slider .carousel-item').eq(i).appendTo('#certificates-slider .carousel-inner');
                }
                else {
                    $('#certificates-slider .carousel-item').eq(0).appendTo('#certificates-slider .carousel-inner');
                }
            }
        }
    });

    /* show lightbox when clicking a thumbnail */
    $('.certificate__item').click(function(event){
        console.log('test');
        event.preventDefault();
        var content = $('.modal-body');
        content.empty();
        var title = $(this).attr("title");
        $('.modal-title').html(title);
        content.html($(this).html());
        $(".modal-profile").modal({show:true});
    });

    $('.toggle-password').click(function () {
        let btn = $(this);
        let input = $($(this).data("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
            $(btn).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr("type", "password");
            $(btn).addClass('fa-eye').removeClass('fa-eye-slash');
        }
    });
});
