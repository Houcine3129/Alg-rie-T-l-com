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
let scrollObserver = null;

function initScrollAnimations() {
  if (!('IntersectionObserver' in window)) return;

  scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        scrollObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.card, .stat-box, .service-detail-card, .timeline-item, .faq-item, .pricing-card').forEach(el => {
    scrollObserver.observe(el);
  });
}

// ════════════════════════════════════════════════════════
// Slider images animation (simple auto-scroll)
// ════════════════════════════════════════════════════════
async function initSlider() {
  console.log('initSlider CALLED');
  const track = document.getElementById('slider-track');
  console.log('track found:', !!track);
  if (!track) return;

  try {
    const response = await fetch('PHP/api_slider.php');
    const data = await response.json();

    track.innerHTML = '';
    const cards = [];

    data.images.forEach(img => {
      const card = document.createElement('div');
      card.className = 'slider-img-card';
      card.innerHTML = `
        <img src="${img.image}" alt="${img.nom}">
        <div class="slider-img-label">${img.description || img.nom}</div>
      `;
      cards.push(card);
    });

    // Dupliquer pour l'effet de défilement infini (translateX(-50%))
    [...cards, ...cards].forEach(c => track.appendChild(c.cloneNode(true)));

  } catch (error) {
    track.innerHTML = '<div class="slider-img-card no-img">Erreur de chargement</div>';
    console.error('Erreur slider :', error);
  }
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
    if (!response.ok) {
      const errBody = await response.json().catch(() => ({}));
      throw new Error(errBody.error || `HTTP ${response.status}`);
    }
    const data = await response.json();

    if (!data || !Array.isArray(data.offres)) {
      throw new Error('Format de réponse invalide');
    }

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
      if (scrollObserver) scrollObserver.observe(card);
    });

  } catch (error) {
    container.innerHTML = '<p style="color:#ff8080;text-align:center;">Erreur de chargement des offres : ' + error.message + '</p>';
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
  initSlider();
  initStatsWilayas();
  initPricingCards();
  initWilayasSelect();
  initOffresSelect();
  initServicesSelect();
  initClientsSelect();
  initThemeToggle();
  initLangSwitcher();
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

// ════════════════════════════════════════════════════════
// Language Switcher (FR / AR)
// ════════════════════════════════════════════════════════

// Arabic translations map
const AR_TRANSLATIONS = {
  // Nav links
  'Accueil': 'الرئيسية',
  'Services': 'الخدمات',
  'Tarifs': 'التعريفات',
  'À Propos': 'من نحن',
  'FAQ': 'الأسئلة الشائعة',
  'Contact': 'اتصل بنا',

  // Hero
  'Bienvenue chez': 'مرحباً بكم في',
  'Nos Services': 'خدماتنا',
  'Nous Contacter': 'اتصل بنا',

  // Stats
  'Abonnés': 'مشترك',
  'Wilayas couvertes': 'ولاية مغطاة',
  'Fibre Optique': 'الألياف البصرية',
  'Support Client': 'دعم العملاء',
  'Couverture nationale': 'تغطية وطنية',
  'Débit max fibre': 'أقصى سرعة ألياف',
  'Disponibilité réseau': 'توفر الشبكة',
  'Clients Actifs': 'عميل نشط',
  'Satisfaction Client': 'رضا العملاء',
  'Wilayas Couvertes': 'ولاية مغطاة',
  'Emplois Créés': 'وظيفة تم إنشاؤها',

  // Section labels
  'À Propos': 'من نحن',
  'Nos Offres': 'عروضنا',
  'Catalogue Complet': 'الكتالوج الكامل',
  'Internet': 'الإنترنت',
  'Téléphonie': 'الهاتف',
  'Entreprises': 'الشركات',
  'Notre Histoire': 'تاريخنا',
  'Fondamentaux': 'القيم الأساسية',
  'Jalons': 'المحطات',
  'Chiffres': 'الأرقام',
  'Support & Info': 'الدعم والمعلومات',
  'Détail': 'التفاصيل',
  'Info': 'معلومات',

  // Headings
  'Le Pionnier des\nTélécommunications': 'رائد\nالاتصالات',
  'Des Solutions pour\nChaque Besoin': 'حلول لكل\nاحتياج',
  'Nos Services': 'خدماتنا',
  'Plans Tarifaires': 'خطط الأسعار',
  'À Propos d\'Algérie Télécom': 'حول الجزائر للاتصالات',
  'Nos Valeurs Fondamentales': 'قيمنا الأساسية',
  'Notre Parcours': 'مسيرتنا',
  'Nos Statistiques': 'إحصائياتنا',
  'Comparaison des Plans': 'مقارنة الخطط',
  'Conditions Générales': 'الشروط العامة',
  'Contactez-nous': 'اتصل بنا',
  'Connectivité Haut Débit': 'اتصال عالي السرعة',
  'Communications Voix': 'اتصالات الصوت',
  'Solutions Professionnelles': 'حلول احترافية',

  // Cards
  'Internet Haut Débit': 'إنترنت عالي السرعة',
  'Services Mobiles': 'الخدمات المحمولة',
  'Solutions Entreprise': 'حلول الأعمال',
  'ADSL': 'ADSL',
  'Téléphonie Fixe': 'الهاتف الثابت',
  'Notre Mission': 'مهمتنا',
  'Notre Vision': 'رؤيتنا',
  'Innovation': 'الابتكار',
  'Connectivité': 'الاتصال',
  'Fiabilité': 'الموثوقية',
  'Service Client': 'خدمة العملاء',

  // Buttons / CTA
  'Demander un devis': 'طلب عرض سعر',
  'Demander un Devis': 'طلب عرض سعر',
  'Prêt à Nous Rejoindre ?': 'هل أنت مستعد للانضمام إلينا؟',
  'Prêt à Changer d\'Offre ?': 'هل أنت مستعد لتغيير العرض؟',

  // Footer
  'Tous droits réservés': 'جميع الحقوق محفوظة',
  'Projet de fin d\'études — Informatique · Bases de Données': 'مشروع التخرج — إعلام آلي · قواعد البيانات',

  // Contact
  'Nos Coordonnées': 'معلومات الاتصال',
  'Envoyez-nous un Message': 'أرسل لنا رسالة',
  'Prénom': 'الاسم الأول',
  'Nom': 'اللقب',
  'Adresse Email': 'البريد الإلكتروني',
  'Téléphone': 'الهاتف',
  'Wilaya': 'الولاية',
  'Objet de la demande': 'موضوع الطلب',
  'Votre Message': 'رسالتك',
  'Envoyer le Message': 'إرسال الرسالة',
  'Réponse garantie sous 24 heures ouvrables': 'رد مضمون خلال 24 ساعة عمل',
  'Email': 'البريد الإلكتروني',
  'Adresse': 'العنوان',
  'Horaires': 'أوقات العمل',
  'Localisation': 'الموقع',
};

const FR_TRANSLATIONS = {};
Object.entries(AR_TRANSLATIONS).forEach(([fr, ar]) => { FR_TRANSLATIONS[ar] = fr; });

function applyLanguage(lang) {
  const isAr = lang === 'ar';
  document.documentElement.lang = lang;
  document.body.dir = isAr ? 'rtl' : 'ltr';

  // Translate all [data-fr] tagged elements
  document.querySelectorAll('[data-fr]').forEach(el => {
    el.textContent = isAr ? (AR_TRANSLATIONS[el.dataset.fr] || el.dataset.fr) : el.dataset.fr;
  });

  localStorage.setItem('at-lang', lang);
}

function initLangSwitcher() {
  const switcher = document.getElementById('lang-switcher');
  const btn = document.getElementById('lang-btn');
  const dropdown = document.getElementById('lang-dropdown');
  const currentLabel = document.getElementById('lang-current');

  if (!switcher || !btn || !dropdown) return;

  // Restore saved language
  const saved = localStorage.getItem('at-lang') || 'fr';
  if (saved === 'ar') {
    applyLanguage('ar');
    currentLabel.textContent = 'AR';
    dropdown.querySelector('[data-lang="fr"]').classList.remove('active');
    dropdown.querySelector('[data-lang="ar"]').classList.add('active');
  }

  // Toggle dropdown
  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = switcher.classList.toggle('open');
    btn.setAttribute('aria-expanded', isOpen);
  });

  // Option click
  dropdown.querySelectorAll('.lang-option').forEach(opt => {
    opt.addEventListener('click', () => {
      const lang = opt.dataset.lang;
      const label = opt.dataset.label;

      // Update active state
      dropdown.querySelectorAll('.lang-option').forEach(o => o.classList.remove('active'));
      opt.classList.add('active');
      currentLabel.textContent = label;

      applyLanguage(lang);

      // Close dropdown
      switcher.classList.remove('open');
      btn.setAttribute('aria-expanded', false);
    });
  });
// ════════════════════════════════════════════════════════
// Language Switcher (FR ↔ AR)
// ════════════════════════════════════════════════════════
function initLangSwitcher() {
  const btn = document.getElementById('langBtn');
  if (!btn) return;

  const html = document.documentElement;

  // Restore saved language on page load
  const saved = localStorage.getItem('lang') || 'fr';
  applyLang(saved);

  btn.addEventListener('click', () => {
    const current = html.getAttribute('lang') === 'ar' ? 'ar' : 'fr';
    const next = current === 'fr' ? 'ar' : 'fr';
    applyLang(next);
    localStorage.setItem('lang', next);
  });

  function applyLang(lang) {
    html.setAttribute('lang', lang);
    html.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
    btn.classList.toggle('is-ar', lang === 'ar');

    // Swap all elements that have data-fr and data-ar attributes
    document.querySelectorAll('[data-fr]').forEach(el => {
      el.textContent = lang === 'ar'
        ? (el.dataset.ar || el.textContent)
        : (el.dataset.fr || el.textContent);
    });
  }
}
  // Close on outside click
  document.addEventListener('click', (e) => {
    if (!switcher.contains(e.target)) {
      switcher.classList.remove('open');
      btn.setAttribute('aria-expanded', false);
    }
  });

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      switcher.classList.remove('open');
      btn.setAttribute('aria-expanded', false);
      btn.focus();
    }
  });
}