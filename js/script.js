/**
 * Portfolio JavaScript
 * Alexandre Dupont - Développeur Web Frontend
 */

// ===================================
// DOM Ready
// ===================================
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initScrollAnimations();
    initCounterAnimation();
    initProjectsFilter();
    initContactForms();
    initSmoothScroll();
});

// ===================================
// Navigation
// ===================================
function initNavigation() {
    const navbar = document.getElementById('navbar');
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    
    // Scroll effect on navbar
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Mobile menu toggle
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('.nav-link');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navbar.contains(event.target)) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
}

// ===================================
// Scroll Animations
// ===================================
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');
    
    if (animatedElements.length === 0) return;
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    animatedElements.forEach(function(element) {
        observer.observe(element);
    });
}

// ===================================
// Counter Animation
// ===================================
function initCounterAnimation() {
    const statNumbers = document.querySelectorAll('.stat-number[data-count]');
    
    if (statNumbers.length === 0) return;
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    statNumbers.forEach(function(number) {
        observer.observe(number);
    });
}

function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-count'), 10);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(function() {
        current += step;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

// ===================================
// Projects Filter
// ===================================
function initProjectsFilter() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card-full');
    
    if (filterButtons.length === 0 || projectCards.length === 0) return;
    
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter projects
            projectCards.forEach(function(card) {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    card.classList.remove('hidden');
                    card.style.animation = 'fadeInUp 0.5s ease forwards';
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
}

// ===================================
// Contact Forms
// ===================================
function initContactForms() {
    const contactForm = document.getElementById('contact-form');
    const projectForm = document.getElementById('project-form');
    const successModal = document.getElementById('success-modal');
    const closeModalBtn = document.getElementById('close-modal');
    
    // Contact form submission
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validate form
            if (validateForm(contactForm)) {
                // Simulate form submission
                simulateFormSubmission(contactForm, successModal);
            }
        });
    }
    
    // Project form submission
    if (projectForm) {
        projectForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validate form
            if (validateForm(projectForm)) {
                // Simulate form submission
                simulateFormSubmission(projectForm, successModal);
            }
        });
    }
    
    // Close modal
    if (closeModalBtn && successModal) {
        closeModalBtn.addEventListener('click', function() {
            successModal.classList.remove('active');
        });
        
        // Close modal when clicking outside
        successModal.addEventListener('click', function(event) {
            if (event.target === successModal) {
                successModal.classList.remove('active');
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && successModal.classList.contains('active')) {
                successModal.classList.remove('active');
            }
        });
    }
}

function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(function(field) {
        // Remove previous error styling
        field.style.borderColor = '';
        
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#ef4444';
            
            // Add shake animation
            field.style.animation = 'shake 0.5s ease';
            setTimeout(function() {
                field.style.animation = '';
            }, 500);
        }
        
        // Email validation
        if (field.type === 'email' && field.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                isValid = false;
                field.style.borderColor = '#ef4444';
            }
        }
    });
    
    return isValid;
}

function simulateFormSubmission(form, modal) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<span>Envoi en cours...</span>';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(function() {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Reset form
        form.reset();
        
        // Show success modal
        if (modal) {
            modal.classList.add('active');
        }
    }, 1500);
}

// ===================================
// Smooth Scroll
// ===================================
function initSmoothScroll() {
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    
    scrollLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            const href = this.getAttribute('href');
            
            if (href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                event.preventDefault();
                
                const navbarHeight = document.getElementById('navbar').offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ===================================
// Utility Functions
// ===================================

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            func.apply(context, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const context = this;
        const args = arguments;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(function() {
                inThrottle = false;
            }, limit);
        }
    };
}

// Add shake animation keyframes dynamically
const shakeKeyframes = `
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = shakeKeyframes;
document.head.appendChild(styleSheet);

// ===================================
// Parallax Effect (Optional)
// ===================================
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    if (parallaxElements.length === 0) return;
    
    window.addEventListener('scroll', throttle(function() {
        const scrollY = window.pageYOffset;
        
        parallaxElements.forEach(function(element) {
            const speed = parseFloat(element.getAttribute('data-parallax')) || 0.5;
            const offset = scrollY * speed;
            element.style.transform = 'translateY(' + offset + 'px)';
        });
    }, 16));
}

// ===================================
// Lazy Loading Images
// ===================================
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    if (lazyImages.length === 0) return;
    
    const imageObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(function(img) {
        imageObserver.observe(img);
    });
}

// ===================================
// Typing Effect (Optional for Hero)
// ===================================
function initTypingEffect() {
    const typingElement = document.querySelector('[data-typing]');
    
    if (!typingElement) return;
    
    const words = JSON.parse(typingElement.getAttribute('data-typing'));
    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    
    function type() {
        const currentWord = words[wordIndex];
        
        if (isDeleting) {
            typingElement.textContent = currentWord.substring(0, charIndex - 1);
            charIndex--;
        } else {
            typingElement.textContent = currentWord.substring(0, charIndex + 1);
            charIndex++;
        }
        
        let typeSpeed = isDeleting ? 50 : 100;
        
        if (!isDeleting && charIndex === currentWord.length) {
            typeSpeed = 2000;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            typeSpeed = 500;
        }
        
        setTimeout(type, typeSpeed);
    }
    
    type();
}

// ===================================
// Initialize Optional Features
// ===================================
// Uncomment to enable
// initParallax();
// initLazyLoading();
// initTypingEffect();
