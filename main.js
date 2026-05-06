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
// Chargement dynamique des Cartes Tarifs depuis l'API
// ════════════════════════════════════════════════════════
async function initPricingCards() {
  const container = document.getElementById('pricing-container');
  if (!container) return;

  try {
    const response = await fetch('php/api_offres.php');
    const data = await response.json();

    container.innerHTML = '';

    data.offres.forEach(offre => {
      const isPopulaire = offre.populaire == 1;
      const prix = Number(offre.prix).toLocaleString('fr-DZ');

      const card = document.createElement('div');
      card.className = 'pricing-card' + (isPopulaire ? ' popular' : '');

      card.innerHTML = `
        ${isPopulaire ? '<div class="pricing-badge">Populaire</div>' : ''}
        <div class="pricing-name">${offre.nom}</div>
        <div class="pricing-description">${offre.description || ''}</div>
        <div class="pricing-price">${prix} DA</div>
        <div class="pricing-price-period">/mois</div>
        <div class="pricing-features">
          <div class="pricing-feature">${offre.debit || '—'} de débit</div>
          <div class="pricing-feature">Installation gratuite</div>
          <div class="pricing-feature">Support client 24/7</div>
        </div>
        <div class="pricing-cta">
          <a href="contact.html" class="btn ${isPopulaire ? 'btn-primary' : 'btn-outline'}" style="width:100%;justify-content:center;">Choisir</a>
        </div>
      `;

      container.appendChild(card);
    });

  } catch (error) {
    container.innerHTML = '<p style="color:#ff8080;text-align:center;">Erreur de chargement des offres.</p>';
    console.error('Erreur chargement offres :', error);
  }
}

// ════════════════════════════════════════════════════════
// Stat dynamique — nombre de wilayas
// ════════════════════════════════════════════════════════
async function initStatsWilayas() {
  const el = document.getElementById('stat-wilayas');
  if (!el) return;

  try {
    const response = await fetch('php/api_wilayas.php');
    const data = await response.json();
    el.textContent = data.wilayas.length;
  } catch (error) {
    console.error('Erreur stat wilayas :', error);
  }
}

// ════════════════════════════════════════════════════════
// Chargement dynamique des Wilayas depuis l'API
// ════════════════════════════════════════════════════════
async function initWilayasSelect() {
  const select = document.querySelector('select[name="wilaya_id"]');
  if (!select) return;

  try {
    const response = await fetch('php/api_wilayas.php');
    const data = await response.json();

    select.innerHTML = '<option value="">— Sélectionner votre wilaya —</option>';

    data.wilayas.forEach(wilaya => {
      const option = document.createElement('option');
      option.value = wilaya.id;
      option.textContent = wilaya.code + ' — ' + wilaya.nom;
      select.appendChild(option);
    });

  } catch (error) {
    select.innerHTML = '<option value="">— Erreur de chargement —</option>';
    console.error('Erreur chargement wilayas :', error);
  }
}

// ════════════════════════════════════════════════════════
// Chargement dynamique des Offres depuis l'API
// ════════════════════════════════════════════════════════
async function initOffresSelect() {
  const select = document.querySelector('select[name="offre_id"]');
  if (!select) return;

  try {
    const response = await fetch('php/api_offres.php');
    const data = await response.json();

    select.innerHTML = '<option value="">— Sélectionner votre offre —</option>';

    data.offres.forEach(offre => {
      const option = document.createElement('option');
      option.value = offre.id;
      option.textContent = offre.nom + ' — ' + Number(offre.prix).toLocaleString('fr-DZ') + ' DA/mois';
      select.appendChild(option);
    });

  } catch (error) {
    select.innerHTML = '<option value="">— Erreur de chargement —</option>';
    console.error('Erreur chargement offres :', error);
  }
}

// ════════════════════════════════════════════════════════
// Chargement dynamique des Services depuis l'API
// ════════════════════════════════════════════════════════
async function initServicesSelect() {
  const select = document.querySelector('select[name="service_id"]');
  if (!select) return;

  try {
    const response = await fetch('php/api_services.php');
    const data = await response.json();

    select.innerHTML = '<option value="">— Sélectionner un service —</option>';

    data.services.forEach(service => {
      const option = document.createElement('option');
      option.value = service.id;
      option.textContent = service.nom + (service.tag ? ' — ' + service.tag : '');
      select.appendChild(option);
    });

  } catch (error) {
    select.innerHTML = '<option value="">— Erreur de chargement —</option>';
    console.error('Erreur chargement services :', error);
  }
}

// ════════════════════════════════════════════════════════
// Chargement dynamique des Clients depuis l'API
// ════════════════════════════════════════════════════════
async function initClientsSelect() {
  const select = document.querySelector('select[name="client_id"]');
  if (!select) return;

  try {
    const response = await fetch('php/api_clients.php');
    const data = await response.json();

    select.innerHTML = '<option value="">— Sélectionner un client —</option>';

    data.clients.forEach(client => {
      const option = document.createElement('option');
      option.value = client.id;
      option.textContent = client.prenom + ' ' + client.nom + ' — ' + client.email;
      select.appendChild(option);
    });

  } catch (error) {
    select.innerHTML = '<option value="">— Erreur de chargement —</option>';
    console.error('Erreur chargement clients :', error);
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
  initStatsWilayas();
  initPricingCards();
  initWilayasSelect();
  initOffresSelect();
  initServicesSelect();
  initClientsSelect();
  initThemeToggle();
});
// ════════════════════════════════════════════════════════
// Dark / Light Theme Toggle
// ════════════════════════════════════════════════════════
function initThemeToggle() {
  const STORAGE_KEY = 'at-theme';

  // Apply saved theme — dark mode is opt-in, light is default
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved === 'dark') document.body.classList.add('dark-mode');

  const btn = document.getElementById('theme-toggle-btn');
  if (!btn) return;

  btn.addEventListener('click', () => {
    const isDark = document.body.classList.toggle('dark-mode');
    localStorage.setItem(STORAGE_KEY, isDark ? 'dark' : 'light');
  });
}
