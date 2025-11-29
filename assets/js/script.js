// Mobile Navigation
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('navLinks');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

// Modal Functions
function openJoinForm() {
    document.getElementById('joinModal').style.display = 'block';
}

function closeJoinForm() {
    document.getElementById('joinModal').style.display = 'none';
}

// Cookie Consent
function acceptCookies() {
    document.getElementById('cookieConsent').style.display = 'none';
    document.cookie = "cookieConsent=true; max-age=2592000; path=/";
}

// Check if consent already given
document.addEventListener('DOMContentLoaded', function() {
    if(document.cookie.indexOf('cookieConsent=true') > -1) {
        document.getElementById('cookieConsent').style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('joinModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
});

// Form Validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = 'red';
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    return isValid;
}