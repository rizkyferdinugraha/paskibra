<div class="loader-overlay" id="loaderOverlay" style="display: none;">
    <div class="loader-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loader-text mt-2">Memuat...</div>
    </div>
</div>

<style>
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(2px);
}

.loader-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.loader-text {
    color: #6c757d;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Dark mode support */
[data-bs-theme="dark"] .loader-content {
    background: #1e293b;
    color: #e2e8f0;
}

[data-bs-theme="dark"] .loader-text {
    color: #94a3b8;
}

/* Spinner animation */
.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.25em;
}

/* Fade in/out animation */
.loader-overlay.fade-in {
    animation: fadeIn 0.3s ease-in;
}

.loader-overlay.fade-out {
    animation: fadeOut 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
</style>

<script>
// Global loader functions
window.showLoader = function(message = 'Memuat...') {
    const loader = document.getElementById('loaderOverlay');
    const loaderText = loader.querySelector('.loader-text');
    
    if (loaderText) {
        loaderText.textContent = message;
    }
    
    loader.style.display = 'flex';
    loader.classList.add('fade-in');
};

window.hideLoader = function() {
    const loader = document.getElementById('loaderOverlay');
    loader.classList.add('fade-out');
    
    setTimeout(() => {
        loader.style.display = 'none';
        loader.classList.remove('fade-out');
    }, 300);
};

// Auto-hide loader on page load
document.addEventListener('DOMContentLoaded', function() {
    // Hide loader if it's still showing after page load
    setTimeout(() => {
        window.hideLoader();
    }, 100);
});

// Show loader on form submissions
document.addEventListener('submit', function(e) {
    // Don't show loader for certain forms (like search forms)
    if (!e.target.classList.contains('no-loader')) {
        window.showLoader('Menyimpan data...');
    }
});

// Show loader on link clicks (for navigation)
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link && !link.hasAttribute('data-no-loader') && 
        !link.href.includes('#') && 
        !link.href.includes('javascript:') &&
        link.href !== window.location.href &&
        // Exclude links that open in new tabs
        !link.hasAttribute('target') &&
        // Exclude sidebar navigation links (they're internal navigation)
        !link.closest('#sidebar') &&
        // Exclude logout links
        !link.href.includes('logout')) {
        window.showLoader('Memuat halaman...');
    }
});

// Show loader on AJAX requests
let activeRequests = 0;

function showAjaxLoader() {
    activeRequests++;
    if (activeRequests === 1) {
        window.showLoader('Memuat data...');
    }
}

function hideAjaxLoader() {
    activeRequests--;
    if (activeRequests === 0) {
        window.hideLoader();
    }
}

// Override fetch to show loader
const originalFetch = window.fetch;
window.fetch = function(...args) {
    showAjaxLoader();
    return originalFetch.apply(this, args)
        .finally(() => {
            hideAjaxLoader();
        });
};

// Override XMLHttpRequest to show loader
const originalXHROpen = XMLHttpRequest.prototype.open;
const originalXHRSend = XMLHttpRequest.prototype.send;

XMLHttpRequest.prototype.open = function(...args) {
    this._loaderActive = true;
    return originalXHROpen.apply(this, args);
};

XMLHttpRequest.prototype.send = function(...args) {
    if (this._loaderActive) {
        showAjaxLoader();
        this.addEventListener('loadend', () => {
            hideAjaxLoader();
        });
    }
    return originalXHRSend.apply(this, args);
};
</script>
