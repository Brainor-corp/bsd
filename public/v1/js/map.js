ymaps.ready(function () {
    var myMap = new ymaps.Map('map', {
            center: [59.93655153819403, 30.350843874102225],
            zoom: 11,
            controls: ['zoomControl', 'fullscreenControl']
        }, {
            searchControlProvider: 'yandex#search',
            maxZoom: 17,
        }),

        // Создаём макет содержимого.
        MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
        );

    $('.terminal-block').each(function (key, el) {
        let point = $(el).data('point').split(', ');

        myMap.geoObjects.add(
            new ymaps.Placemark([point[1], point[0]], {
                hintContent: 'Безымянный',
                balloonContent: 'А эта — новогодняя',
            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#image',
                // Своё изображение иконки метки.
                iconImageHref: 'images/img/logo-map.png',
                // Размеры метки.
                iconImageSize: [40, 40],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-5, -38]
            })
        );
    });

    myMap.behaviors.disable('scrollZoom');
    myMap.setBounds(myMap.geoObjects.getBounds());
});