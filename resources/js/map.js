let map = L.map('map').setView([56.9512, 24.1129], 9);
let markers = [];
let line = null;

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    // attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

function clearMap() {
    markers.forEach(m => map.removeLayer(m));
    markers = [];
    if (line) {
        map.removeLayer(line);
        line = null;
    }
}

document.querySelectorAll('.select-route').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = this.closest('tr');
        const points = JSON.parse(row.getAttribute('data-points'));

        clearMap();

        if (!points || points.length === 0) {
            alert('No points in route');
            return;
        }

        // координаты маршрута
        const latlngs = points.map(p => [
            parseFloat(p.latitude),
            parseFloat(p.longitude)
        ]);

        // рисуем линию
        line = L.polyline(latlngs, { color: 'blue' }).addTo(map);

        // добавляем маркеры
        latlngs.forEach(coord => {
            markers.push(L.marker(coord).addTo(map));
        });

        // центрируем карту
        map.fitBounds(line.getBounds(), { padding: [50, 50] });
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

        // Get CSRF token safely
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
            // match = row.children[2].textContent.toLowerCase().includes(value);
        } else if (type === 'flagged') {
            const checkbox = row.querySelector('.flag-checkbox');
            const checked = checkbox ? checkbox.checked : false;
            match = value === '' || (value === 'yes' && checked) || (value === 'no' && !checked);
        }
        row.style.display = match ? '' : 'none';
    });
}

if (filterType && filterInput) {
    filterType.addEventListener('change', filterTable);
    filterInput.addEventListener('input', filterTable);
}

const detailsModal = document.getElementById('details-modal');
const closeDetails = document.getElementById('close-details');

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
        document.getElementById('d-date').textContent = new Date(date).toLocaleString();
        document.getElementById('d-description').textContent = description || 'No description';

        // 🔥 ВСТАВКА ФОТО
        const photosContainer = document.getElementById('d-photos');
        photosContainer.innerHTML = '';

        if (photos.length === 0) {
            photosContainer.innerHTML = '<p class="text-gray-500">No photos</p>';
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

if (closeDetails) {
    closeDetails.addEventListener('click', () => {
        detailsModal.classList.add('hidden');
        mapColumn.classList.remove('hidden');
    });
}

document.querySelectorAll('.favorite-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', async function () {

        const routeId = this.getAttribute('data-route-id');

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const res = await fetch(`/routes/${routeId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf
            }
        });

        if (res.ok) {
            const data = await res.json();
            this.checked = data.favorited;
        } else {
            alert('Error updating favorites');
            this.checked = !this.checked;
        }
    });
});

const imageModal = document.getElementById('image-modal');
const modalImage = document.getElementById('modal-image');
const closeImage = document.getElementById('close-image');

// открыть
window.openImage = function(src) {
    modalImage.src = src;
    imageModal.classList.remove('hidden');
}

// закрыть по кнопке
if (closeImage) {
    closeImage.addEventListener('click', () => {
        imageModal.classList.add('hidden');
    });
}

// закрыть по клику вне картинки
if (imageModal) {
    imageModal.addEventListener('click', (e) => {
        if (e.target === imageModal) {
            imageModal.classList.add('hidden');
        }
    });
}