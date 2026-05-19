// ============================================
// Main JavaScript for ApiSpi
// ============================================

// Smooth scroll behavior for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Navigation active link handling
function updateActiveNav() {
    const currentLocation = location.pathname;
    const menuItems = document.querySelectorAll('.nav-menu a');

    menuItems.forEach(item => {
        item.classList.remove('active');
        const href = item.getAttribute('href');
        
        // Check if current page matches the link
        if (currentLocation.includes(href) || 
            (currentLocation === '/' && href === 'index.html')) {
            item.classList.add('active');
        }
    });
}

// Run on page load
document.addEventListener('DOMContentLoaded', updateActiveNav);

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeIn 0.6s ease forwards';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Add fade-in animation CSS dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .agent-card,
    .benefit-card,
    .blog-card {
        opacity: 0;
    }
`;
document.head.appendChild(style);

// Observe all cards for animation
document.querySelectorAll('.agent-card, .benefit-card, .blog-card').forEach(card => {
    observer.observe(card);
});

// Keyboard navigation support
document.addEventListener('keydown', (e) => {
    // Escape key to close any modals/overlays
    if (e.key === 'Escape') {
        console.log('Close any open overlays here');
    }
    
    // Alt+Home to go to home
    if (e.altKey && e.key === 'Home') {
        window.location.href = '/';
    }
});

// Utility function to format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Utility function to format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Add hover effects to interactive elements
document.querySelectorAll('a, button').forEach(element => {
    element.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
    });
});

// Track page view (analytics integration point)
function trackPageView() {
    console.log('Page viewed:', window.location.pathname);
    // Integration point for analytics service like Google Analytics
}

document.addEventListener('DOMContentLoaded', trackPageView);

// Form submission handler
function handleFormSubmit(formId, onSuccess) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Collect form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Here you would typically send data to a backend service
        console.log('Form data:', data);
        
        // Show success message
        if (onSuccess) {
            onSuccess(data);
        }
        
        // Reset form
        form.reset();
    });
}

// Initialize tooltips if they exist
function initializeTooltips() {
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            tooltip.style.cssText = `
                position: absolute;
                background: rgba(0, 102, 255, 0.9);
                color: white;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 0.85rem;
                white-space: nowrap;
                z-index: 1000;
            `;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
            tooltip.style.left = (rect.left + (rect.width - tooltip.offsetWidth) / 2) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                delete this._tooltip;
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeTooltips);

// Lazy load images (if needed)
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Export utility functions for use in other scripts
window.ApiSpi = {
    formatCurrency,
    formatDate,
    handleFormSubmit
};

console.log('ApiSpi JavaScript loaded successfully');
