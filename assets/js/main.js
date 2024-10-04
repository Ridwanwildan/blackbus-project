// Main JavaScript file for Blackbus

// Function to toggle admin dashboard link visibility based on user role
document.addEventListener('DOMContentLoaded', function() {
    const userRole = document.getElementById('user-role').value; // Hidden input with user role value
    const adminLink = document.getElementById('admin-link'); // Admin dashboard link
    
    if (userRole === 'admin') {
        adminLink.style.display = 'block'; // Show admin dashboard link for admin users
    } else {
        adminLink.style.display = 'none'; // Hide for normal users
    }
});

// Function to handle seat selection in the modal
function updateSeatDropdown(totalSeats, bookedSeats) {
    const seatDropdown = document.getElementById('seat-dropdown');
    seatDropdown.innerHTML = ''; // Clear existing options

    for (let i = 1; i <= totalSeats; i++) {
        if (!bookedSeats.includes(i)) { // Only show available seats
            const option = document.createElement('option');
            option.value = i;
            option.text = `Seat ${i}`;
            seatDropdown.appendChild(option);
        }
    }
}

// Function to display notification after successful payment
function showPaymentSuccessMessage() {
    const successMessage = document.getElementById('payment-success-message');
    successMessage.style.display = 'block';

    setTimeout(function() {
        successMessage.style.display = 'none'; // Hide the message after 3 seconds
        window.location.href = 'home.php'; // Redirect to home page
    }, 3000);
}

// Search bus functionality
document.getElementById('search-btn').addEventListener('click', function() {
    const origin = document.getElementById('origin').value;
    const destination = document.getElementById('destination').value;
    const departureDate = document.getElementById('departure-date').value;

    if (!origin || !destination || !departureDate) {
        alert('Please fill in all search fields.');
        return;
    }

    // Perform search (Assuming data is fetched from backend via AJAX or page reload)
    // This is just a placeholder for now.
    console.log(`Searching for buses from ${origin} to ${destination} on ${departureDate}`);
});

// Function to handle modal close and cancel actions
document.getElementById('cancel-modal-btn').addEventListener('click', function() {
    const modal = document.getElementById('seat-selection-modal');
    modal.style.display = 'none'; // Hide modal
});

// Open the seat selection modal
document.getElementById('seat-selection-btn').addEventListener('click', function() {
    const modal = document.getElementById('seat-selection-modal');
    modal.style.display = 'block'; // Show modal
});

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('seat-selection-modal');
    if (event.target === modal) {
        modal.style.display = 'none'; // Hide modal if clicked outside
    }
};
