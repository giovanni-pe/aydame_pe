/* public/js/app.js */

// Main JavaScript file for the Sarah App

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initializeApp();
});

/**
 * Initialize the application
 */
function initializeApp() {
    // Add event listeners
    addEventListeners();
    
    // Any other initialization logic
    console.log('Sarah App initialized!');
}

/**
 * Add event listeners to elements
 */
function addEventListeners() {
    // Search button click
    const searchButton = document.querySelector('.search-button');
    if (searchButton) {
        searchButton.addEventListener('click', function() {
            const searchInput = document.querySelector('.search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                console.log('Searching for:', searchTerm);
                // Call search function or redirect to search page
                // window.location.href = 'search?q=' + encodeURIComponent(searchTerm);
            }
        });
    }
    
    // Bottom navigation items
    const navItems = document.querySelectorAll('.bottom-nav-item');
    if (navItems.length) {
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Remove active class from all items
                navItems.forEach(nav => nav.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // If it's not the active page, navigate to it
                if (!this.classList.contains('active-page')) {
                    const link = this.getAttribute('href');
                    if (link && link !== '#') {
                        window.location.href = link;
                    }
                }
            });
        });
    }
}