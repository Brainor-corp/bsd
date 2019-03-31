$(function () {
    
    $('.slider').bxSlider({
        minSlides: 1,
        maxSlides: 6,
        slideWidth: 170,
        moveSlides: 1,
        slideMargin: 30,
        hideControlOnEnd: true,
        pager: false,
        prevText: '',
        nextText: ''
    });
    $('.bx-prev').addClass('fa fa-chevron-left')
    $('.bx-next').addClass('fa fa-chevron-right');
    $('.lb-prev').addClass('fa fa-chevron-left')
    $('.lb-next').addClass('fa fa-chevron-right');

    lightbox.option({
        'albumLabel': 'Изображение %1 из %2'
    })
})