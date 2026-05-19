document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const userNameText = document.getElementById('delete-user-name');
    const cancelBtn = document.getElementById('cancel-delete');

    // открыть modal
    document.querySelectorAll('.open-delete-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            const userId = btn.getAttribute('data-user-id');
            const userName = btn.getAttribute('data-user-name');

            userNameText.textContent = userName;

            deleteForm.action = `/admin/users/${userId}`;

            modal.classList.remove('hidden');
        });
    });

    // закрыть modal
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

});