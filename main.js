// ════════════════════════════════════════════════════════
// Hamburger Menu Toggle
// ════════════════════════════════════════════════════════
function initMobileMenu() {
  const hamburger = document.querySelector('.hamburger');
  const navLinks = document.querySelector('.nav-links');
  
  if (!hamburger) return;
  
  hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active');
    navLinks.classList.toggle('open');
  });
  
  // Close menu on link click
  document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
      hamburger.classList.remove('active');
      navLinks.classList.remove('open');
    });
  });
}

// ════════════════════════════════════════════════════════
// Scroll-to-Top Button
// ════════════════════════════════════════════════════════
function initScrollToTop() {
  const scrollBtn = document.querySelector('.scroll-to-top');
  
  if (!scrollBtn) return;
  
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      scrollBtn.classList.add('show');
    } else {
      scrollBtn.classList.remove('show');
    }
  });
  
  scrollBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// ════════════════════════════════════════════════════════
// Navbar Background Opacity on Scroll
// ════════════════════════════════════════════════════════
function initNavbarScroll() {
  const navbar = document.querySelector('.navbar');
  
  if (!navbar) return;
  
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
}

// ════════════════════════════════════════════════════════
// Scroll-Triggered Animations (IntersectionObserver)
// Elements are visible by default; .in-view adds a subtle
// fadeUp animation when they scroll into view.
// ════════════════════════════════════════════════════════
function initScrollAnimations() {
  if (!('IntersectionObserver' in window)) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.card, .stat-box, .service-detail-card, .timeline-item, .faq-item, .pricing-card').forEach(el => {
    observer.observe(el);
  });
}

// ════════════════════════════════════════════════════════
// FAQ Accordion Toggle
// ════════════════════════════════════════════════════════
function initFaqAccordion() {
  const faqItems = document.querySelectorAll('.faq-item');
  
  faqItems.forEach(item => {
    const header = item.querySelector('.faq-header');
    
    if (header) {
      header.addEventListener('click', () => {
        // Close other items in same category
        const category = item.closest('.faq-category');
        if (category) {
          category.querySelectorAll('.faq-item.active').forEach(active => {
            if (active !== item) {
              active.classList.remove('active');
            }
          });
        }
        
        item.classList.toggle('active');
      });
    }
  });
}

// ════════════════════════════════════════════════════════
// Contact Form Validation with Visual Feedback
// ════════════════════════════════════════════════════════
function initFormValidation() {
  const form = document.querySelector('.contact-form-card');
  
  if (!form) return;
  
  const inputs = form.querySelectorAll('input, textarea');
  
  inputs.forEach(input => {
    input.addEventListener('blur', () => validateField(input));
    input.addEventListener('input', () => {
      if (input.classList.contains('error')) {
        validateField(input);
      }
    });
  });
}

function validateField(field) {
  const value = field.value.trim();
  const isEmail = field.type === 'email';
  const isPhone = field.type === 'tel';
  
  let isValid = false;
  
  if (isEmail) {
    isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  } else if (isPhone) {
    isValid = /^[\d\s+()-]*$/.test(value) && value.length > 0;
  } else {
    isValid = value.length > 0;
  }
  
  if (isValid) {
    field.classList.remove('error');
    field.classList.add('valid');
  } else {
    field.classList.add('error');
    field.classList.remove('valid');
  }
  
  return isValid;
}

// ════════════════════════════════════════════════════════
// Page Transition Fade-In
// ════════════════════════════════════════════════════════
function initPageTransition() {
  const wrapper = document.querySelector('.page-wrapper');
  
  if (wrapper) {
    wrapper.classList.add('fade-in');
  }
}

// ════════════════════════════════════════════════════════
// Initialize All
// ════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  initPageTransition();
  initMobileMenu();
  initScrollToTop();
  initNavbarScroll();
  initScrollAnimations();
  initFaqAccordion();
  initFormValidation();
});
