let map = L.map('map').setView([56.9512, 24.1129], 9);
let markers = [];
let line = null;

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

function clearMap() {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
    if (line) {
        map.removeLayer(line);
        line = null;
    }
}

async function getCoords(location) {
    const coordMatch = location.match(/^\s*(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)\s*$/);
    if (coordMatch) {
        return [parseFloat(coordMatch[1]), parseFloat(coordMatch[3])];
    }
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`;
    const res = await fetch(url);
    const data = await res.json();
    if (data && data.length > 0) {
        return [parseFloat(data[0].lat), parseFloat(data[0].lon)];
    }
    return null;
}

document.querySelectorAll('.select-route').forEach(btn => {
    btn.addEventListener('click', async function() {
        const row = this.closest('tr');
        const start = row.getAttribute('data-start');
        const end = row.getAttribute('data-end');
        clearMap();

        const startCoords = await getCoords(start);
        const endCoords = await getCoords(end);

        if (startCoords && endCoords) {
            markers.push(L.marker(startCoords).addTo(map).bindPopup('Start: ' + start).openPopup());
            markers.push(L.marker(endCoords).addTo(map).bindPopup('End: ' + end).openPopup());
            line = L.polyline([startCoords, endCoords], {color: 'blue'}).addTo(map);
            map.fitBounds([startCoords, endCoords], {padding: [50, 50]});
        } else {
            alert('Could not find one or both locations.');
        }
    });
});

// Feedback modal logic
let currentRouteId = null;
const mapColumn = document.getElementById('map-column');
const feedbackModal = document.getElementById('feedback-modal');
const feedbackList = document.getElementById('feedback-list');
const feedbackForm = document.getElementById('feedback-form');
const feedbackInput = document.getElementById('feedback-input');
const closeFeedback = document.getElementById('close-feedback');

if (closeFeedback) {
    closeFeedback.addEventListener('click', () => {
        feedbackModal.classList.add('hidden');
        mapColumn.classList.remove('hidden');
    });
}

if (feedbackForm && feedbackInput) {
    feedbackForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const feedback = feedbackInput.value.trim();
        if (!feedback) return;
        // Save feedback via AJAX
        await fetch(`/routes/${currentRouteId}/feedback`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ feedback })
        });
        feedbackInput.value = '';
        await loadFeedbacks(currentRouteId);
    });
}

document.querySelectorAll('.show-feedback').forEach(btn => {
    btn.addEventListener('click', async function() {
        const row = this.closest('tr');
        currentRouteId = row.getAttribute('data-route-id');
        feedbackModal.classList.remove('hidden');
        mapColumn.classList.add('hidden');
        if (feedbackInput) feedbackInput.value = '';
        await loadFeedbacks(currentRouteId);

        // description logic
        // const description = this.getAttribute('data-description') || 'No description';
        // const name = this.getAttribute('data-route-name') || '';
        // document.getElementById('route-description').innerHTML =
        //     `<div class="mb-2"><span class="font-bold">${name}</span></div>
        //      <div>${description}</div>`;
    });
});

async function loadFeedbacks(routeId) {
    feedbackList.innerHTML = '<li>Loading...</li>';
    const res = await fetch(`/routes/${routeId}/feedback`);
    const data = await res.json();
    feedbackList.innerHTML = '';
    if (data.length === 0) {
        feedbackList.innerHTML = '<li class="text-gray-500">No feedbacks yet.</li>';
    } else {
        data.forEach(fb => {
            feedbackList.innerHTML += `<li class="border-b py-1"><span class="font-semibold">${fb.user}:</span> ${fb.feedback}</li>`;
        });
    }
}

const filterType = document.getElementById('filter-type');
const filterInput = document.getElementById('filter-input');
const tableRows = document.querySelectorAll('#routes-table tbody tr');

function filterTable() {
    const type = filterType.value;
    const value = filterInput.value.toLowerCase();

    tableRows.forEach(row => {
        let match = false;
        if (type === 'name') {
            match = row.children[0].textContent.toLowerCase().includes(value);
        } else if (type === 'city') {
            match = row.children[1].textContent.toLowerCase().includes(value);
        } else if (type === 'country') {
            // If you have a country column, adjust the index accordingly
            // match = row.children[2].textContent.toLowerCase().includes(value);
        } else if (type === 'flagged') {
            const checked = row.querySelector('.flag-checkbox').checked;
            match = value === '' || (value === 'yes' && checked) || (value === 'no' && !checked);
        }
        row.style.display = match ? '' : 'none';
    });
}

if (filterType && filterInput) {
    filterType.addEventListener('change', filterTable);
    filterInput.addEventListener('input', filterTable);
}