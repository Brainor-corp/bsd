ymaps.ready(function () {
    var myMap = new ymaps.Map('map', {
            center: [59.93655153819403, 30.350843874102225],
            zoom: 11
        }, {
            searchControlProvider: 'yandex#search'
        }),

        // Создаём макет содержимого.
        MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
            '<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
        ),

        // myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
        //     hintContent: 'Собственный значок метки',
        //     balloonContent: 'Это красивая метка'
        // }, {
        //     // Опции.
        //     // Необходимо указать данный тип макета.
        //     iconLayout: 'default#image',
        //     // Своё изображение иконки метки.
        //     iconImageHref: 'images/img/logo-map.png',
        //     // Размеры метки.
        //     iconImageSize: [40, 40],
        //     // Смещение левого верхнего угла иконки относительно
        //     // её "ножки" (точки привязки).
        //     iconImageOffset: [-5, -38]
        // }),

        myPlacemarkWithContent = new ymaps.Placemark([59.93655153819403, 30.350843874102225], {
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
        });

        myPlacemarkWithContent1 = new ymaps.Placemark([59.918788761502526, 30.449463335161795], {
            hintContent: 'Проспект большевиков',
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
        });

    myMap.geoObjects
        .add(myPlacemarkWithContent)
        .add(myPlacemarkWithContent1);
});