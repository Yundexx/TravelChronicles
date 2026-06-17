// Manage route visualization, feedback, route filtering, favorites, and modal windows. Basically all map-related interactions on the route listing page.

// Initialize the map
let map = L.map('map').setView([56.9512, 24.1129], 9);

let markers = [];
let line = null;
let activeRouteButton = null;

// Load OpenStreetMap tiles
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    // attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

/**
 * Remove all markers and route lines from the map.
 */
function clearMap() {
    markers.forEach(m => map.removeLayer(m));
    markers = [];

    if (line) {
        map.removeLayer(line);
        line = null;
    }
}

// Display a selected route on the map
document.querySelectorAll('.select-route').forEach(btn => {

    btn.addEventListener('click', function() {

        if (activeRouteButton) {
            activeRouteButton.classList.remove('route-active');
        }

        activeRouteButton = this;
        this.classList.add('route-active');

        const row = this.closest('tr');
        const points = JSON.parse(row.getAttribute('data-points'));

        clearMap();

        if (!points || points.length === 0) {
            alert('No points in route');
            return;
        }

        const latlngs = points.map(p => [
            parseFloat(p.latitude),
            parseFloat(p.longitude)
        ]);

        line = L.polyline(latlngs, {
            color: 'blue'
        }).addTo(map);

        latlngs.forEach(coord => {
            markers.push(L.marker(coord).addTo(map));
        });

        map.fitBounds(
            line.getBounds(),
            { padding: [50, 50] }
        );
    });

});
// Feedback modal functionality
let currentRouteId = null;

const mapColumn = document.getElementById('map-column');
const feedbackModal = document.getElementById('feedback-modal');
const feedbackList = document.getElementById('feedback-list');
const feedbackForm = document.getElementById('feedback-form');
const feedbackInput = document.getElementById('feedback-input');
const closeFeedback = document.getElementById('close-feedback');

// Close feedback modal
if (closeFeedback) {
    closeFeedback.addEventListener('click', () => {
        feedbackModal.classList.add('hidden');
        mapColumn.classList.remove('hidden');
    });
}

// Submit new feedback
if (feedbackForm && feedbackInput) {
    feedbackForm.addEventListener('submit', async function(e) {

        e.preventDefault();

        const feedback = feedbackInput.value.trim();

        if (!feedback) return;

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        await fetch(`/routes/${currentRouteId}/feedback`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ feedback })
        });

        feedbackInput.value = '';

        await loadFeedbacks(currentRouteId);
    });
}

// Open feedback modal
document.querySelectorAll('.show-feedback').forEach(btn => {
    btn.addEventListener('click', async function() {

        const row = this.closest('tr');

        currentRouteId = row.getAttribute('data-route-id');

        feedbackModal.classList.remove('hidden');
        mapColumn.classList.add('hidden');

        if (feedbackInput) {
            feedbackInput.value = '';
        }

        await loadFeedbacks(currentRouteId);
    });
});

/**
 * Load feedback for the selected route.
 */
async function loadFeedbacks(routeId) {

    feedbackList.innerHTML = '<li>Loading...</li>';

    const res = await fetch(`/routes/${routeId}/feedback`);
    const data = await res.json();

    feedbackList.innerHTML = '';

    if (data.length === 0) {

        feedbackList.innerHTML = '<li class="text-gray-500">No feedbacks yet.</li>';

    } else {

        data.forEach(fb => {
            feedbackList.innerHTML += `
                <li class="border-b py-1">
                    <span class="font-semibold">${fb.user}:</span> ${fb.feedback}
                </li>
            `;
        });

    }
}

// Route filtering functionality
const filterType = document.getElementById('filter-type');
const filterInput = document.getElementById('filter-input');
const tableRows = document.querySelectorAll('#routes-table tbody tr');

/**
 * Filter routes based on selected criteria.
 */
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

        } else if (type === 'flagged') {

            const checkbox = row.querySelector('.flag-checkbox');
            const checked = checkbox ? checkbox.checked : false;

            match = value === ''
                || (value === 'yes' && checked)
                || (value === 'no' && !checked);
        }

        row.style.display = match ? '' : 'none';
    });
}

if (filterType && filterInput) {
    filterType.addEventListener('change', filterTable);
    filterInput.addEventListener('input', filterTable);
}

// Route details modal
const detailsModal = document.getElementById('details-modal');
const closeDetails = document.getElementById('close-details');

// Open route details modal
document.querySelectorAll('.show-details').forEach(btn => {
    btn.addEventListener('click', function() {

        const row = this.closest('tr');

        const name = row.children[0].textContent;
        const user = row.getAttribute('data-user');
        const date = row.getAttribute('data-created');
        const description = row.getAttribute('data-description');
        const photos = JSON.parse(row.getAttribute('data-photos') || '[]');

        document.getElementById('d-name').textContent = name;
        document.getElementById('d-user').textContent = user;
        document.getElementById('d-country').textContent = row.dataset.country;
        document.getElementById('d-city').textContent = row.dataset.city;
        document.getElementById('d-date').textContent = new Date(date).toLocaleString();
        document.getElementById('d-description').textContent = description || 'No description';

        // Display route photos
        const photosContainer = document.getElementById('d-photos');
        photosContainer.innerHTML = '';

        if (photos.length === 0) {

            photosContainer.innerHTML = '<p class="text-gray-500">Nav fotografijas</p>';

        } else {

            photos.forEach(photo => {
                photosContainer.innerHTML += `
                    <img 
                        src="/storage/${photo.photo_path}" 
                        class="w-24 h-24 object-cover rounded shadow cursor-pointer hover:scale-105 transition"
                        onclick="openImage('/storage/${photo.photo_path}')"
                    >
                `;
            });

        }

        detailsModal.classList.remove('hidden');
        mapColumn.classList.add('hidden');
    });
});

// Close route details modal
if (closeDetails) {
    closeDetails.addEventListener('click', () => {
        detailsModal.classList.add('hidden');
        mapColumn.classList.remove('hidden');
    });
}

// Toggle route favorites
document.querySelectorAll('.favorite-btn').forEach(btn => {

    btn.addEventListener('click', async function () {

        const routeId = this.getAttribute('data-route-id');

        const csrf = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        const res = await fetch(`/routes/${routeId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            }
        });

        if (!res.ok) {
            alert('Error updating favorites');
            return;
        }

        const data = await res.json();

        if (data.favorited) {
            this.classList.add('favorite-active');
        } else {
            this.classList.remove('favorite-active');
        }

    });

});

// Image preview modal
const imageModal = document.getElementById('image-modal');
const modalImage = document.getElementById('modal-image');
const closeImage = document.getElementById('close-image');

// Toggle flagged routes
document.querySelectorAll('.flag-btn').forEach(btn => {

    btn.addEventListener('click', async function () {

        const routeId = this.dataset.routeId;

        const csrf = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        const res = await fetch(`/routes/${routeId}/flag`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            alert('Kļūda saglabājot atzīmi');
            return;
        }

        const data = await res.json();

        if (data.flagged) {
            this.classList.add('favorite-active');
        } else {
            this.classList.remove('favorite-active');
        }

    });

});
/**
 * Open image preview modal.
 */
window.openImage = function(src) {
    modalImage.src = src;
    imageModal.classList.remove('hidden');
}

// Close image modal with button
if (closeImage) {
    closeImage.addEventListener('click', () => {
        imageModal.classList.add('hidden');
    });
}

// Close image modal when clicking outside the image
if (imageModal) {
    imageModal.addEventListener('click', (e) => {
        if (e.target === imageModal) {
            imageModal.classList.add('hidden');
        }
    });
}