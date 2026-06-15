// Manage user deletion confirmation modal.
document.addEventListener('DOMContentLoaded', () => {

    // Get modal and form elements
    const modal = document.getElementById('delete-modal');
    const deleteForm = document.getElementById('delete-form');
    const userNameText = document.getElementById('delete-user-name');
    const cancelBtn = document.getElementById('cancel-delete');

    // Open the modal when a delete button is clicked
    document.querySelectorAll('.open-delete-modal').forEach(btn => {
        btn.addEventListener('click', () => {

            // Retrieve selected user information
            const userId = btn.getAttribute('data-user-id');
            const userName = btn.getAttribute('data-user-name');

            // Display the user's name in the confirmation message
            userNameText.textContent = userName;

            // Set the form action URL for the selected user
            deleteForm.action = `/admin/users/${userId}`;

            // Show the modal window
            modal.classList.remove('hidden');
        });
    });

    // Close the modal when the cancel button is clicked
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

});