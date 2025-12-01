// FoodFusion - Enhanced JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // Search Toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchClose = document.getElementById('searchClose');
    const searchBar = document.getElementById('searchBar');
    
    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', () => {
            searchBar.classList.add('active');
            document.querySelector('.search-input').focus();
        });
    }
    
    if (searchClose && searchBar) {
        searchClose.addEventListener('click', () => {
            searchBar.classList.remove('active');
        });
    }

    // Dropdown Menus
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('show');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // Cookie Consent
    function checkCookieConsent() {
        if (!document.cookie.includes('cookieConsent=true')) {
            const cookieConsent = document.getElementById('cookieConsent');
            if (cookieConsent) {
                setTimeout(() => {
                    cookieConsent.classList.add('show');
                }, 1000);
            }
        }
    }

    function acceptCookies() {
        document.cookie = "cookieConsent=true; max-age=2592000; path=/";
        const cookieConsent = document.getElementById('cookieConsent');
        if (cookieConsent) {
            cookieConsent.classList.remove('show');
        }
    }

    // Initialize cookie consent
    checkCookieConsent();

    // Form Validation
    function validateForm(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
                
                // Add error message
                if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'This field is required';
                    input.parentNode.insertBefore(errorMsg, input.nextSibling);
                }
            } else {
                input.classList.remove('error');
                const errorMsg = input.nextElementSibling;
                if (errorMsg && errorMsg.classList.contains('error-message')) {
                    errorMsg.remove();
                }
                
                // Email validation
                if (input.type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value)) {
                        isValid = false;
                        input.classList.add('error');
                        const errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.textContent = 'Please enter a valid email address';
                        input.parentNode.insertBefore(errorMsg, input.nextSibling);
                    }
                }
                
                // Password strength
                if (input.type === 'password' && input.value.length < 6) {
                    isValid = false;
                    input.classList.add('error');
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'Password must be at least 6 characters';
                    input.parentNode.insertBefore(errorMsg, input.nextSibling);
                }
            }
        });
        
        return isValid;
    }

    // Add form validation to all forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });

    // Image Lazy Loading
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Smooth Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Back to Top Button
    const backToTop = document.createElement('button');
    backToTop.innerHTML = '<i class="fas fa-chevron-up"></i>';
    backToTop.className = 'back-to-top';
    backToTop.style.display = 'none';
    document.body.appendChild(backToTop);

    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTop.style.display = 'block';
        } else {
            backToTop.style.display = 'none';
        }
    });

    // Recipe Card Interactions
    const recipeCards = document.querySelectorAll('.recipe-card');
    recipeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Like Button Functionality
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('liked');
            const icon = this.querySelector('i');
            if (this.classList.contains('liked')) {
                icon.className = 'fas fa-heart';
                this.style.color = '#e74c3c';
            } else {
                icon.className = 'far fa-heart';
                this.style.color = '';
            }
        });
    });

    // Modal Functions
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    };

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Newsletter Form Submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Simulate subscription
            const button = this.querySelector('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="loading"></span> Subscribing...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = 'Subscribed!';
                button.style.background = '#27ae60';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.style.background = '';
                    this.reset();
                }, 2000);
            }, 1500);
        });
    }

    // Initialize animations
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check

    // Global functions
    window.acceptCookies = acceptCookies;
});

// Utility Functions
const FoodFusion = {
    // Show notification
    showNotification: function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.parentElement.removeChild(notification);
                }
            }, 300);
        }, 5000);
    },
    
    // Format date
    formatDate: function(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    },
    
    // Truncate text
    truncateText: function(text, length = 100) {
        if (text.length <= length) return text;
        return text.substr(0, length) + '...';
    },
    
    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Cookie Consent Management
class CookieConsent {
    constructor() {
        this.cookieName = 'foodfusion_cookies';
        this.consent = this.getConsent();
        this.init();
    }
    
    init() {
        if (!this.consent || this.consent.version < 2) {
            setTimeout(() => this.showConsent(), 1000);
        }
    }
    
    getConsent() {
        const cookie = document.cookie.split('; ').find(row => row.startsWith(`${this.cookieName}=`));
        return cookie ? JSON.parse(decodeURIComponent(cookie.split('=')[1])) : null;
    }
    
    showConsent() {
        const consentElement = document.getElementById('cookieConsent');
        if (consentElement) {
            consentElement.classList.add('show');
            this.bindEvents();
        }
    }
    
    hideConsent() {
        const consentElement = document.getElementById('cookieConsent');
        if (consentElement) {
            consentElement.classList.remove('show');
        }
    }
    
    saveConsent(preferences) {
        const consent = {
            essential: true,
            analytics: preferences.analytics || false,
            marketing: preferences.marketing || false,
            version: 2,
            timestamp: new Date().toISOString()
        };
        
        const expiry = new Date();
        expiry.setFullYear(expiry.getFullYear() + 1);
        
        document.cookie = `${this.cookieName}=${encodeURIComponent(JSON.stringify(consent))}; expires=${expiry.toUTCString()}; path=/; SameSite=Lax`;
        
        // Apply consent preferences
        this.applyConsent(consent);
        this.hideConsent();
        
        // Show confirmation
        this.showNotification('Cookie preferences saved successfully!', 'success');
    }
    
    applyConsent(consent) {
        // Google Analytics
        if (consent.analytics) {
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-XXXXXXXXXX');
        }
        
        // Marketing pixels
        if (consent.marketing) {
            // Initialize marketing scripts here
            console.log('Marketing cookies enabled');
        }
    }
    
    bindEvents() {
        // Close button
        const closeBtn = document.getElementById('cookieClose');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.hideConsent());
        }
        
        // Save preferences
        const saveBtn = document.querySelector('.btn-save');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                const preferences = {
                    analytics: document.querySelector('input[name="analytics"]').checked,
                    marketing: document.querySelector('input[name="marketing"]').checked
                };
                this.saveConsent(preferences);
            });
        }
        
        // Accept all
        const acceptBtn = document.querySelector('.btn-accept');
        if (acceptBtn) {
            acceptBtn.addEventListener('click', () => {
                this.saveConsent({ analytics: true, marketing: true });
            });
        }
        
        // Cookie type toggle details
        const cookieTypes = document.querySelectorAll('.cookie-type');
        cookieTypes.forEach(type => {
            const header = type.querySelector('.type-header');
            header.addEventListener('click', () => {
                type.classList.toggle('active');
            });
        });
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">&times;</button>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        });
    }
}

// Footer functionality
class EnhancedFooter {
    constructor() {
        this.init();
    }
    
    init() {
        this.initNewsletter();
        this.initScrollToTop();
        this.initQuickChat();
        this.initLanguageSelector();
    }
    
    initNewsletter() {
        const form = document.getElementById('newsletterForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = form.querySelector('input[type="email"]').value;
                this.subscribeNewsletter(email);
            });
        }
    }
    
    subscribeNewsletter(email) {
        // Simulate API call
        const btn = document.querySelector('.btn-newsletter');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#27ae60';
            
            // Show success message
            const notification = document.createElement('div');
            notification.className = 'notification notification-success';
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="fas fa-check-circle"></i>
                    <span>Successfully subscribed to newsletter!</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.classList.add('show'), 100);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
            
            // Reset form
            document.getElementById('newsletterForm').reset();
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
        }, 1500);
    }
    
    initScrollToTop() {
        const btn = document.getElementById('backToTop');
        if (btn) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    btn.classList.add('show');
                } else {
                    btn.classList.remove('show');
                }
            });
            
            btn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    }
    
    initQuickChat() {
        const chatBtn = document.getElementById('quickChat');
        if (chatBtn) {
            chatBtn.addEventListener('click', () => {
                // Open chat modal or redirect to chat page
                window.open('chat.php', '_blank');
            });
        }
    }
    
    initLanguageSelector() {
        const selector = document.getElementById('languageSelect');
        if (selector) {
            selector.addEventListener('change', (e) => {
                const lang = e.target.value;
                // Implement language change logic
                console.log('Language changed to:', lang);
                // In real app: window.location.href = `?lang=${lang}`;
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize cookie consent
    const cookieConsent = new CookieConsent();
    
    // Initialize footer functionality
    const enhancedFooter = new EnhancedFooter();
    
    // Add tooltip functionality
    const elementsWithTooltip = document.querySelectorAll('[data-tooltip]');
    elementsWithTooltip.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.dataset.tooltip;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.position = 'fixed';
            tooltip.style.left = rect.left + rect.width / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            tooltip.style.transform = 'translateX(-50%)';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
    
    // Add hover effects to social links
    const socialLinks = document.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.1)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Global functions for backward compatibility
function acceptCookies() {
    const cookieConsent = new CookieConsent();
    cookieConsent.saveConsent({ analytics: true, marketing: true });
}

function customizeCookies() {
    const consentElement = document.getElementById('cookieConsent');
    if (consentElement) {
        consentElement.classList.add('show');
    }
}

function saveCookiePreferences() {
    const cookieConsent = new CookieConsent();
    const preferences = {
        analytics: document.querySelector('input[name="analytics"]').checked,
        marketing: document.querySelector('input[name="marketing"]').checked
    };
    cookieConsent.saveConsent(preferences);
}

function acceptAllCookies() {
    const cookieConsent = new CookieConsent();
    cookieConsent.saveConsent({ analytics: true, marketing: true });
}