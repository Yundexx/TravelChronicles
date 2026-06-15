// Manage route creation on the map.

// Initialize the map centered on Riga
let map = L.map('map').setView([56.9512, 24.1129], 9);

// Load OpenStreetMap tiles
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    // attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Route data containers
let points = [];
let markers = [];
let routeLine = null;

const submitButton = document.getElementById('submit-route');

/**
 * Enable or disable the submit button
 * depending on the number of route points.
 */
function updateSubmitButton() {
    if (points.length >= 2) {
        submitButton.disabled = false;

        submitButton.classList.remove(
            'opacity-50',
            'cursor-not-allowed'
        );

    } else {
        submitButton.disabled = true;

        submitButton.classList.add(
            'opacity-50',
            'cursor-not-allowed'
        );
    }
}

// Add a new route point when the map is clicked
map.on('click', function(e) {

    // Limit routes to a maximum of 25 points
    if (points.length >= 25) {
        alert('Maximum 25 points allowed');
        return;
    }

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    // Save coordinates to the route array
    points.push({ lat: lat, lng: lng });

    // Create a marker for the selected point
    let marker = L.marker([lat, lng]).addTo(map);
    markers.push(marker);

    // Remove the previous route line
    if (routeLine) {
        map.removeLayer(routeLine);
    }

    // Draw the updated route line
    routeLine = L.polyline(
        points.map(p => [p.lat, p.lng]),
        { color: 'blue' }
    ).addTo(map);

    // Store route points in a hidden form field
    document.getElementById('points-data').value = JSON.stringify(points);

    updateSubmitButton();
});

/**
 * Remove all route points and reset the map.
 */
window.clearRoute = function () {
    points = [];

    // Remove all markers from the map
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    // Remove the route line
    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }

    // Clear stored route data
    document.getElementById('points-data').value = '';

    updateSubmitButton();
}

/**
 * Remove the last added route point.
 */
window.removeLastPoint = function () {
    if (points.length === 0) return;

    // Remove the last point from the route
    points.pop();

    // Remove the corresponding marker
    const lastMarker = markers.pop();
    if (lastMarker) {
        map.removeLayer(lastMarker);
    }

    // Remove the existing route line
    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }

    // Redraw the route if points remain
    if (points.length > 0) {
        routeLine = L.polyline(
            points.map(p => [p.lat, p.lng]),
            { color: 'blue' }
        ).addTo(map);
    }

    // Update stored route data
    document.getElementById('points-data').value = JSON.stringify(points);

    updateSubmitButton();
}