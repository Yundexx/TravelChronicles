// === ELEMENTS ===
const detailsModal = document.getElementById('details-modal');
const closeDetails = document.getElementById('close-details');

const imageModal = document.getElementById('image-modal');
const modalImage = document.getElementById('modal-image');
const closeImage = document.getElementById('close-image');

// === OPEN DETAILS ===
document.querySelectorAll('.show-details').forEach(btn => {
    btn.addEventListener('click', function () {

        const row = this.closest('tr');

        const name = row.children[0].textContent;
        const user = row.getAttribute('data-user');
        const date = row.getAttribute('data-created');
        const description = row.getAttribute('data-description');
        const photos = JSON.parse(row.getAttribute('data-photos') || '[]');

        document.getElementById('d-name').textContent = name;
        document.getElementById('d-user').textContent = user;
        document.getElementById('d-date').textContent = new Date(date).toLocaleString();
        document.getElementById('d-description').textContent = description || 'Nav apraksta';

        const photosContainer = document.getElementById('d-photos');
        photosContainer.innerHTML = '';

        if (photos.length === 0) {
            photosContainer.innerHTML = '<p class="text-gray-500">Nav foto</p>';
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
    });
});

// === CLOSE DETAILS ===
if (closeDetails) {
    closeDetails.addEventListener('click', () => {
        detailsModal.classList.add('hidden');
    });
}

// === IMAGE MODAL ===
window.openImage = function (src) {
    modalImage.src = src;
    imageModal.classList.remove('hidden');
};

if (closeImage) {
    closeImage.addEventListener('click', () => {
        imageModal.classList.add('hidden');
    });
}

if (imageModal) {
    imageModal.addEventListener('click', (e) => {
        if (e.target === imageModal) {
            imageModal.classList.add('hidden');
        }
    });
}

console.log("PROFILE JS LOADED");