let map = L.map('map').setView([56.9512, 24.1129], 9);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    // attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let points = [];
let markers = [];
let routeLine = null;

map.on('click', function(e) {
    // ограничение 25 точек
    if (points.length >= 25) {
        alert('Maximum 25 points allowed');
        return;
    }

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // добавляем в массив
    points.push({ lat: lat, lng: lng });

    // создаём маркер
    let marker = L.marker([lat, lng]).addTo(map);
    markers.push(marker);

    // перерисовываем линию маршрута
    if (routeLine) {
        map.removeLayer(routeLine);
    }

    routeLine = L.polyline(
        points.map(p => [p.lat, p.lng]),
        { color: 'blue' }
    ).addTo(map);

    // сохраняем JSON в hidden input
    document.getElementById('points-data').value = JSON.stringify(points);
});

// 🔥 ДОПОЛНИТЕЛЬНО (очень удобно)
// кнопка очистки маршрута (если добавишь кнопку в HTML)
window.clearRoute = function () {
    points = [];

    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }

    document.getElementById('points-data').value = '';
}

window.removeLastPoint = function () {
    if (points.length === 0) return;

    // удалить последнюю точку из массива
    points.pop();

    // удалить последний маркер с карты
    const lastMarker = markers.pop();
    if (lastMarker) {
        map.removeLayer(lastMarker);
    }

    // удалить старую линию
    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }

    // перерисовать линию (если есть точки)
    if (points.length > 0) {
        routeLine = L.polyline(
            points.map(p => [p.lat, p.lng]),
            { color: 'blue' }
        ).addTo(map);
    }

    // обновить hidden input
    document.getElementById('points-data').value = JSON.stringify(points);
}