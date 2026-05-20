let detailsMap = null;
let detailsRouteLine = null;
let detailsMarkers = [];

document.querySelectorAll('.show-details').forEach(button => {

    button.addEventListener('click', function () {

        const row = this.closest('tr');

        const name = row.children[0].innerText;
        const description = row.dataset.description;
        const created = row.dataset.created;
        const user = row.dataset.user;

        const points = JSON.parse(row.dataset.points || '[]');
        const photos = JSON.parse(row.dataset.photos || '[]');

        document.getElementById('d-name').textContent = name;
        document.getElementById('d-user').textContent = user;
        document.getElementById('d-date').textContent = created;
        document.getElementById('d-description').textContent = description;

        const photosContainer = document.getElementById('d-photos');
        photosContainer.innerHTML = '';

        photos.forEach(photo => {

            const img = document.createElement('img');

            img.src = '/storage/' + photo.photo_path;

            img.className = `
                w-24 h-24
                object-cover
                rounded-xl
                shadow
                cursor-pointer
                hover:scale-105
                transition
            `;

            img.addEventListener('click', () => {

                document.getElementById('image-modal').classList.remove('hidden');
                document.getElementById('modal-image').src = img.src;

            });

            photosContainer.appendChild(img);

        });

        document.getElementById('details-modal').classList.remove('hidden');

        setTimeout(() => {

            if (detailsMap) {
                detailsMap.remove();
            }

            detailsMap = L.map('details-map');

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(detailsMap);

            detailsMarkers = [];

            const latlngs = points.map(point => {

                const latlng = [
                    point.latitude,
                    point.longitude
                ];

                const marker = L.marker(latlng).addTo(detailsMap);

                detailsMarkers.push(marker);

                return latlng;

            });

            if (latlngs.length > 0) {

                detailsRouteLine = L.polyline(latlngs, {
                    color: '#2563eb',
                    weight: 4
                }).addTo(detailsMap);

                detailsMap.fitBounds(detailsRouteLine.getBounds(), {
                    padding: [30, 30]
                });

            } else {

                detailsMap.setView([56.9496, 24.1052], 7);

            }

        }, 100);

    });

});

document.getElementById('close-details').addEventListener('click', () => {

    document.getElementById('details-modal').classList.add('hidden');

    if (detailsMap) {
        detailsMap.remove();
        detailsMap = null;
    }

});

document.getElementById('close-image').addEventListener('click', () => {

    document.getElementById('image-modal').classList.add('hidden');

});