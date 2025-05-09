ymaps.ready(init);

function init() {
    var lng = document.getElementById('lng-input').value;
    var lat  = document.getElementById('lat-input').value;

    if (lat !== "" && lng !== "") {
        center = [lat, lng]
    } else {
        center = [55.753994, 37.622093]
    }
    var myPlacemark,
        myMap = new ymaps.Map('map', {
            center: center,
            zoom: 9,
            controls: ['searchControl', 'zoomControl']
        }, {
            searchControlProvider: 'yandex#search'
        });

    if (lat !== "" && lng !== "") {
        myPlacemark = createPlacemark(center);
        myMap.geoObjects.add(myPlacemark);
        // Слушаем событие окончания перетаскивания на метке.
        myPlacemark.events.add('dragend', function () {
            getAddress(myPlacemark.geometry.getCoordinates());
        });
        myPlacemark.properties.set({
            // В качестве контента балуна задаем строку с адресом объекта.
            iconCaption: document.getElementById('address-input').value,
            balloonContent: document.getElementById('address-input').value
        });
    }

    // Слушаем клик на карте.
    myMap.events.add('click', function (e) {
        var coords = e.get('coords');
        console.log(coords);
        // Если метка уже создана – просто передвигаем ее.
        if (myPlacemark) {
            myPlacemark.geometry.setCoordinates(coords);
        }
        // Если нет – создаем.
        else {
            myPlacemark = createPlacemark(coords);
            myMap.geoObjects.add(myPlacemark);
            // Слушаем событие окончания перетаскивания на метке.
            myPlacemark.events.add('dragend', function () {
                getAddress(myPlacemark.geometry.getCoordinates());
            });
        }
        getAddress(coords);
        document.getElementById('lat-input').value = coords[0];
        document.getElementById('lng-input').value = coords[1];
    });

    // Создание метки.
    function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#redDotIconWithCaption',
            draggable: true
        });
    }

    // Определяем адрес по координатам (обратное геокодирование).
    function getAddress(coords) {
        myPlacemark.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);

            myPlacemark.properties
                .set({
                    // Формируем строку с данными об объекте.
                    iconCaption: [
                        // Название населенного пункта или вышестоящее административно-территориальное образование.
                        firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                        // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                        firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                    ].filter(Boolean).join(', '),
                    // В качестве контента балуна задаем строку с адресом объекта.
                    balloonContent: firstGeoObject.getAddressLine()
                });
            document.getElementById('address-input').value = firstGeoObject.getAddressLine();
        });
    }

    document.getElementById('address-input').onkeyup = function () {
        myPlacemark.properties.set('iconCaption', 'поиск...');
    };
}