// ════════════════════════════════════════════════════════
// Algérie Télécom — Internationalisation FR / AR
// ════════════════════════════════════════════════════════

const AT_TRANSLATIONS = {

  fr: {
    // ── Navigation ──────────────────────────────────────
    'nav.home':     'Accueil',
    'nav.services': 'Services',
    'nav.tarifs':   'Tarifs',
    'nav.about':    'À Propos',
    'nav.faq':      'FAQ',
    'nav.contact':  'Contact',

    // ── Footer ──────────────────────────────────────────
    'footer.copy':   '© 2025 Algérie Télécom · Tous droits réservés',
    'footer.thesis': 'Projet de fin d\'études — Informatique · Bases de Données',

    // ── Index — Hero ────────────────────────────────────
    'hero.badge':           'Toujours proche · دائما أقرب',
    'hero.title.line1':     'Bienvenue chez',
    'hero.title.line2':     'Algérie Télécom',
    'hero.desc':            'Le premier opérateur national de télécommunications — connectant l\'Algérie avec des solutions haut débit, mobiles et d\'entreprise.',
    'hero.btn.services':    'Nos Services',
    'hero.btn.contact':     'Nous Contacter',

    // ── Index — Stats ───────────────────────────────────
    'stat.N_abonnes': '+15M',
    'stat.abonnes': 'Abonnés',
    'stat.wilayas': 'Wilayas couvertes',
    'stat.debit':    '1 Gbps',
    'stat.fibre':   'Fibre Optique',
    'stat.support': 'Support Client',
    'stat.support_num': '24/7',

    // ── Index — Section À propos ────────────────────────
    'section.about.label': 'À Propos',
    'section.about.title': 'Le Pionnier des\nTélécommunications',
    'section.about.desc':  'Algérie Télécom est la principale entreprise de télécommunications en Algérie, engagée à fournir une connectivité fiable, rapide et innovante à travers tout le territoire national.',
    'card.internet.title': 'Internet Haut Débit',
    'card.internet.desc':  'Surfer, télécharger et communiquer à grande vitesse grâce à notre infrastructure ADSL et Fibre Optique de dernière génération.',
    'card.mobile.title':   'Services Mobiles',
    'card.mobile.desc':    'Restez connecté avec vos proches où que vous soyez grâce à notre réseau mobile à couverture nationale étendue.',
    'card.biz.title':      'Solutions Entreprise',
    'card.biz.desc':       'Optimisez vos communications professionnelles avec nos offres dédiées : VPN, téléphonie IP, connectivité sécurisée.',

    // ── Index — Section Offres ──────────────────────────
    'section.offers.label': 'Nos Offres',
    'section.offers.title': 'Des Solutions pour\nChaque Besoin',
    'section.offers.desc':  'De la fibre optique ultra-rapide aux solutions d\'entreprise, une offre adaptée à chaque client.',
    'card.adsl.title':      'ADSL',
    'card.adsl.desc':       'Connexion haut débit accessible sur l\'ensemble du réseau téléphonique national, idéale pour les particuliers.',
    'card.fibre.title':     'Fibre Optique',
    'card.fibre.desc':      'Vitesses exceptionnelles allant jusqu\'à 1 Gbps grâce au réseau de fibre optique déployé dans les grandes villes.',
    'card.tel.title':       'Téléphonie Fixe',
    'card.tel.desc':        'Qualité d\'appel remarquable et tarifs avantageux sur notre réseau de téléphonie fixe historique.',

    // ── Services page ───────────────────────────────────
    'services.hero.label': 'Catalogue Complet',
    'services.hero.title': 'Nos Services',
    'services.hero.desc':  'Découvrez notre gamme complète de solutions télécoms conçues pour répondre aux besoins des particuliers, des professionnels et des entreprises.',
    'services.stat.coverage': 'Couverture nationale',
    'services.stat.debit':    'Débit max fibre',
    'services.stat.dispo':    'Disponibilité réseau',
    'services.internet.label':  'Internet',
    'services.internet.title':  'Connectivité Haut Débit',
    'services.adsl.title':      'ADSL — Internet Haut Débit',
    'services.adsl.desc':       'Profitez d\'une connexion stable et rapide via le réseau téléphonique filaire. Idéal pour le surf, le streaming, et l\'envoi d\'emails, disponible dans toutes les wilayas du pays.',
    'services.adsl.tag':        'Jusqu\'à 20 Mbps',
    'services.fibre.title':     'Fibre Optique — Ultra Haut Débit',
    'services.fibre.desc':      'Accédez à des vitesses de connexion exceptionnelles grâce à notre infrastructure Fibre Optique de dernière génération. Téléchargez des films en secondes, jouez en ligne sans latence, télétravaillez sans interruption.',
    'services.fibre.tag':       'Jusqu\'à 1 Gbps',
    'services.tel.label':       'Téléphonie',
    'services.tel.title':       'Communications Voix',
    'services.mobile.title':    'Services Mobiles',
    'services.mobile.desc':     'Restez en contact avec vos proches grâce à nos offres mobiles compétitives. Couverture 4G/LTE sur l\'ensemble du territoire, roaming international, et forfaits adaptés à tous les profils.',
    'services.mobile.tag':      '4G/LTE Nationale',
    'services.fixe.title':      'Téléphonie Fixe',
    'services.fixe.desc':       'Notre réseau de téléphonie fixe historique garantit une qualité d\'appel remarquable avec des tarifs avantageux pour les communications locales, nationales et internationales.',
    'services.fixe.tag':        'Qualité HD',
    'services.biz.label':       'Entreprises',
    'services.biz.title':       'Solutions Professionnelles',
    'services.b2b.title':       'Solutions d\'Entreprise — Communication Intégrée',
    'services.b2b.desc':        'Nos solutions B2B couvrent la téléphonie IP, la connectivité sécurisée par VPN, la gestion de réseau multi-sites et les lignes dédiées haute disponibilité.',
    'services.b2b.tag':         'VPN · IP · MPLS',
    'services.hosting.title':   'Hébergement & Data Centers',
    'services.hosting.desc':    'Hébergez vos données et applications dans nos centres de données sécurisés situés en Algérie. Conformité réglementaire, disponibilité 99,9%, sauvegardes automatiques, et support technique dédié 24h/24.',
    'services.hosting.tag':     'Cloud · Colocation',
    'services.cta':             'Demander un devis',

    // ── Tarif page ──────────────────────────────────────
    'tarif.hero.label':  'Nos Offres',
    'tarif.hero.title':  'Plans Tarifaires',
    'tarif.hero.desc':   'Choisissez l\'offre qui correspond à vos besoins. Tous nos plans incluent le service client 24/7 et une installation gratuite.',
    'tarif.compare.label': 'Détail',
    'tarif.compare.title': 'Comparaison des Plans',
    'tarif.table.feature':   'Caractéristiques',
    'tarif.table.adsl':      'ADSL Basic',
    'tarif.table.standard':  'Fibre Standard',
    'tarif.table.premium':   'Fibre Premium',
    'tarif.table.speed':     'Débit descendant',
    'tarif.table.install':   'Installation',
    'tarif.table.telephony': 'Téléphonie',
    'tarif.table.cloud':     'Stockage Cloud',
    'tarif.table.support':   'Support Client',
    'tarif.table.price':     'Prix mensuel',
    'tarif.speed.adsl':      '20 Mbps',
    'tarif.speed.std':       '100 Mbps',
    'tarif.speed.prem':      '1 Gbps',
    'tarif.install.free':    'Gratuite',
    'tarif.install.config':  'Gratuite + Configuration',
    'tarif.tel.local':       'Local illimité',
    'tarif.tel.national':    'National illimité',
    'tarif.tel.intl':        'International illimité',
    'tarif.cloud.none':      '—',
    'tarif.cloud.std':       '50 GB',
    'tarif.cloud.prem':      '500 GB',
    'tarif.support.std':     'Standard 24/7',
    'tarif.support.prio':    'Prioritaire 24/7',
    'tarif.support.vip':     'VIP 24/7/365',
    'tarif.price.adsl':      '1 299 DA',
    'tarif.price.std':       '2 999 DA',
    'tarif.price.prem':      '4 999 DA',
    'tarif.conditions.label': 'Info',
    'tarif.conditions.title': 'Conditions Générales',
    'card.engagement.title':   'Engagement',
    'card.engagement.desc':    'Tous nos plans sont sans engagement. Vous pouvez modifier ou résilier votre abonnement à tout moment sans frais additionnels.',
    'card.flexibilite.title':  'Flexibilité',
    'card.flexibilite.desc':   'Changez de plan à tout moment. Passez à une offre supérieure immédiatement ou réduisez vos services selon vos besoins.',
    'card.disponibilite.title': 'Disponibilité',
    'card.disponibilite.desc':  'Les tarifs et la disponibilité peuvent varier selon votre localité. Vérifiez la couverture de votre zone avant de commander.',
    'card.promotions.title':    'Promotions',
    'card.promotions.desc':     'Consultez nos offres spéciales et réductions saisonnières. Les nouvelles souscriptions peuvent bénéficier d\'avantages exclusifs.',
    'tarif.cta.title': 'Prêt à Changer d\'Offre ?',

    // ── About page ──────────────────────────────────────
    'about.hero.label': 'Notre Histoire',
    'about.hero.title': 'À Propos d\'Algérie Télécom',
    'about.hero.desc':  'Depuis notre création, nous nous engageons à fournir les meilleures solutions de télécommunications aux Algériens, connectant le pays avec innovation et fiabilité.',
    'about.mission.label': 'Fondamentaux',
    'about.mission.title': 'Notre Mission',
    'about.mission.desc':  'Fournir des services de télécommunications de classe mondiale accessibles à tous les Algériens, stimulant la transformation numérique et la croissance économique du pays.',
    'about.vision.title':  'Notre Vision',
    'about.vision.desc':   'Être le leader incontesté des télécommunications en Afrique du Nord, reconnu pour l\'excellence, l\'innovation et notre engagement envers nos clients et la société.',
    'about.values.label':  'Fondamentaux',
    'about.values.title':  'Nos Valeurs Fondamentales',
    'about.innovation.title':   'Innovation',
    'about.innovation.desc':    'Nous investissons continuellement dans les technologies de pointe pour offrir des solutions avant-gardistes à nos clients.',
    'about.connectivity.title': 'Connectivité',
    'about.connectivity.desc':  'Nous rapprochons les gens, les entreprises et les communautés à travers un réseau robuste et fiable couvrant tout le pays.',
    'about.reliability.title':  'Fiabilité',
    'about.reliability.desc':   'Nos services garantissent une disponibilité 99,9% et une qualité exceptionnelle pour que nos clients puissent toujours compter sur nous.',
    'about.service.title':      'Service Client',
    'about.service.desc':       'Nous plaçons la satisfaction de nos clients au cœur de nos activités, offrant un support 24/7 multicanal.',
    'about.timeline.label': 'Jalons',
    'about.timeline.title': 'Notre Parcours',
    'about.1974.title': 'Fondation',
    'about.1974.desc':  'Création d\'Algérie Télécom, débuts modestes avec le réseau de téléphonie fixe national.',
    'about.1999.title': 'Lancement ADSL',
    'about.1999.desc':  'Introduction du service ADSL haut débit, révolutionnant l\'accès Internet en Algérie.',
    'about.2007.title': 'Services Mobiles',
    'about.2007.desc':  'Entrée dans le marché mobile 3G, élargissant notre portefeuille de services.',
    'about.2015.title': 'Fibre Optique',
    'about.2015.desc':  'Lancement du déploiement national de fibre optique, offrant des vitesses ultra-haut débit.',
    'about.2022.title': 'Transformation Numérique',
    'about.2022.desc':  'Modernisation complète de nos infrastructures et lancement de services cloud innovants.',
    'about.2025.title': 'Leadership Régional',
    'about.2025.desc':  'Consolidation de notre position comme leader des télécommunications en Afrique du Nord.',
    'about.stats.label':        'Chiffres',
    'about.stats.title':        'Nos Statistiques',
    'about.stats.clients':      'Clients Actifs',
    'about.stats.satisfaction': 'Satisfaction Client',
    'about.stats.wilayas':      'Wilayas Couvertes',
    'about.stats.emplois':      'Emplois Créés',
    'about.cta.title': 'Prêt à Nous Rejoindre ?',
    'about.cta.btn':   'Nous Contacter',

    // ── FAQ page ────────────────────────────────────────
    'faq.hero.label': 'Questions',
    'faq.hero.title': 'Questions Fréquentes',
    'faq.hero.desc':  'Consultez nos réponses aux questions les plus fréquemment posées. Vous n\'avez pas trouvé votre réponse ?',
    'faq.hero.link':  'Contactez-nous',
    'faq.internet.label': 'Internet',
    'faq.internet.q1': 'Quelle est la différence entre ADSL et Fibre Optique ?',
    'faq.internet.a1': 'L\'ADSL utilise les lignes téléphoniques existantes et offre des débits jusqu\'à 20 Mbps. La Fibre Optique utilise des câbles de fibres optiques et offre des débits beaucoup plus rapides, jusqu\'à 1 Gbps, avec une latence plus faible. Optez pour la fibre si vous cherchez une connexion ultra-rapide pour le gaming ou le télétravail exigeant.',
    'faq.internet.q2': 'Comment vérifier si ma zone est couverte par la fibre optique ?',
    'faq.internet.a2': 'Vous pouvez vérifier la disponibilité de la fibre optique en visitant notre site ou en appelant le 121. Indiquez simplement votre adresse pour connaître les offres disponibles chez vous. La couverture s\'étend progressivement à tous les centres urbains du pays.',
    'faq.internet.q3': 'Quelle est la vitesse de débit garantie ?',
    'faq.internet.a3': 'Les vitesses indiquées sont des valeurs maximales théoriques. La vitesse réelle dépend de plusieurs facteurs : distance du central, qualité de la ligne, équipements utilisés et charge du réseau. Nos contrats garantissent un minimum de 50% de la vitesse souscrite en conditions normales d\'utilisation.',
    'faq.tel.label': 'Téléphonie',
    'faq.tel.q1': 'Comment puis-je activer un service mobile ?',
    'faq.tel.a1': 'Vous pouvez activer un service mobile auprès de n\'importe quel agent Algérie Télécom en vous présentant avec une pièce d\'identité valide. Vous recevrez une carte SIM et pourrez choisir votre forfait parmi nos offres. L\'activation est généralement instantanée.',
    'faq.tel.q2': 'Puis-je conserver mon numéro en changeant d\'opérateur ?',
    'faq.tel.a2': 'Oui, la portabilité des numéros est disponible en Algérie. Pour conserver votre numéro en changeant d\'opérateur, contactez notre service client qui vous guidera dans les démarches administratives. La portabilité est généralement effective sous 24 à 48 heures.',
    'faq.tel.q3': 'Proposez-vous l\'international ?',
    'faq.tel.a3': 'Oui, nous proposons des offres d\'appels et de SMS internationaux à tarifs avantageux. Consultez nos tarifs d\'appel vers les principales destinations ou abonnez-vous à nos forfaits internationaux pour réduire vos frais. Le roaming international est également disponible.',
    'faq.fact.label': 'Facturation',
    'faq.fact.q1': 'Quand puis-je recevoir ma facture ?',
    'faq.fact.a1': 'Les factures sont généralement émises entre le 5 et le 15 de chaque mois, selon votre date d\'activation ou votre cycle de facturation. Vous pouvez consulter et télécharger vos factures en ligne via votre espace client sur notre site.',
    'faq.fact.q2': 'Comment puis-je payer ma facture ?',
    'faq.fact.a2': 'Plusieurs moyens de paiement sont disponibles : prélèvement automatique bancaire, virement, chèque, ou paiement en personne dans nos agences. Vous pouvez également payer via notre plateforme en ligne sécurisée pour plus de commodité.',
    'faq.fact.q3': 'Que faire en cas de facture anormale ?',
    'faq.fact.a3': 'Si vous constatez une anomalie sur votre facture, contactez immédiatement notre service client au 121 ou via notre site. Nous effectuerons une investigation complète et rectifierons toute erreur identifiée. Vous ne serez jamais facturé pour des services non utilisés.',
    'faq.support.label': 'Support Technique',
    'faq.support.q1': 'Ma connexion est lente, que faire ?',
    'faq.support.a1': 'D\'abord, vérifiez que votre routeur est à proximité et bien ventilé. Redémarrez votre modem et routeur. Si le problème persiste, contactez notre support au 121 ou en ligne. Nous effectuerons un diagnostic à distance et enverrons un technicien si nécessaire, gratuitement si c\'est un problème réseau.',
    'faq.support.q2': 'Comment réinitialiser mon mot de passe WiFi ?',
    'faq.support.a2': 'Consultez le manuel de votre routeur ou accédez à l\'interface d\'administration (généralement 192.168.1.1). Le nom d\'utilisateur et mot de passe par défaut se trouvent sur l\'étiquette du routeur. Si vous avez oublié votre mot de passe, réinitialisez l\'appareil en appuyant sur le bouton reset pendant 10 secondes.',
    'faq.support.q3': 'Quels sont les horaires du support technique ?',
    'faq.support.a3': 'Notre support client est disponible 24/7/365. Vous pouvez nous contacter par téléphone au 121 (gratuit depuis une ligne Algérie Télécom), par email, par chat en ligne, ou en vous rendant dans nos agences. Pour les urgences, préférez l\'appel téléphonique.',
    'faq.cta.title': 'Votre Question n\'est pas Listée ?',
    'faq.cta.btn':   'Nous Contacter',

    // ── Contact page ────────────────────────────────────
    'contact.hero.label': 'Support & Info',
    'contact.hero.title': 'Contactez-nous',
    'contact.hero.desc':  'Notre équipe est disponible pour répondre à toutes vos questions, demandes d\'information ou réclamations.',
    'contact.info.title':    'Nos Coordonnées',
    'contact.email.label':   'Email',
    'contact.phone.label':   'Téléphone',
    'contact.address.label': 'Adresse',
    'contact.address.value': '1, Rue Docteur Saïd Houari<br>Alger, Algérie',
    'contact.hours.label':   'Horaires',
    'contact.hours.value':   'Dim – Jeu : 08h00 – 17h00<br>Support technique : 24h/24',
    'contact.location.label':'Localisation',
    'contact.form.title':    'Envoyez-nous un Message',
    'contact.form.prenom':   'Prénom',
    'contact.form.nom':      'Nom',
    'contact.form.email':    'Adresse Email',
    'contact.form.tel':      'Téléphone',
    'contact.form.tel.opt':  '(optionnel)',
    'contact.form.wilaya':   'Wilaya',
    'contact.form.sujet':    'Objet de la demande',
    'contact.form.msg':      'Votre Message',
    'contact.form.submit':   'Envoyer le Message',
    'contact.form.guarantee':'Réponse garantie sous 24 heures ouvrables',
    'contact.opt.default':       '— Sélectionner —',
    'contact.opt.souscription':  'Souscription',
    'contact.opt.resiliation':   'Résiliation',
    'contact.opt.incident':      'Incident technique',
    'contact.opt.facturation':   'Facturation',
    'contact.opt.entreprise':    'Solutions entreprise',
    'contact.opt.autre':         'Autre',
    'contact.ph.prenom':  'Votre prénom',
    'contact.ph.nom':     'Votre nom',
    'contact.ph.email':   'exemple@email.com',
    'contact.ph.tel':     '+213 XX XX XX XX',
    'contact.ph.msg':     'Décrivez votre demande ou question (20 caractères minimum)...',

    // ── Validation messages ─────────────────────────────
    'val.name':    'Veuillez saisir votre prénom et nom.',
    'val.email':   'Adresse email invalide.',
    'val.wilaya':  'Veuillez sélectionner votre wilaya.',
    'val.sujet':   'Veuillez sélectionner un objet.',
    'val.message': 'Le message doit contenir au moins 20 caractères.',
    'val.success': 'Message envoyé avec succès ! Nous vous répondrons sous 24h ouvrables.',
    'val.error':   'Une erreur s\'est produite. Veuillez vérifier vos informations et réessayer.',

    // ── Pricing cards (dynamique) ───────────────────────
    'price.popular':   'Populaire',
    'price.debit':     'de débit',
    'price.install':   'Installation gratuite',
    'price.support':   'Support client 24/7',
    'price.choose':    'Choisir',
    'price.per.month': '/mois',
    'price.loading':   'Chargement des offres…',
    'price.error':     'Erreur de chargement des offres.',

    // ── Selects dynamiques ──────────────────────────────
    'select.wilaya.default': '— Chargement des wilayas… —',
    'select.wilaya.error':   '— Erreur de chargement —',
    'select.offre.default':  '— Sélectionner votre offre —',
    'select.service.default':'— Sélectionner un service —',
    'select.client.default': '— Sélectionner un client —',
    'slider.loading':        'Chargement…',

    // ── Flash info ───────────────────────────────────────
    'flash.alert.label': 'Alerte',
    'flash.alert.text':  '📢 PROMOTION : Profitez de -20% sur nos offres fibre jusqu\'à la fin du mois ! | ⚡ Algérie Télécom : Plus proche de vous._________________|________________ À l\'occasion de la fête de l\'indépendance, profitez de -15% sur nos offres fibre jusqu\'à la fin du mois ! | ⚡ Algérie Télécom : Plus proche de vous.',
    'flash.alert.title': '🚨Alerte!🚨',
  },

  // ════════════════════════════════════════════════════
  ar: {
    // ── Navigation ──────────────────────────────────────
    'nav.home':     'الرئيسية',
    'nav.services': 'الخدمات',
    'nav.tarifs':   'الأسعار',
    'nav.about':    'حولنا',
    'nav.faq':      'الأسئلة الشائعة',
    'nav.contact':  'اتصل بنا',

    // ── Footer ──────────────────────────────────────────
    'footer.copy':   '© 2025 اتصالات الجزائر · جميع الحقوق محفوظة',
    'footer.thesis': 'مشروع تخرج — معلوماتية · قواعد بيانات',

    // ── Index — Hero ────────────────────────────────────
    'hero.badge':           'دائما أقرب · Toujours proche',
    'hero.title.line1':     'مرحباً بكم في',
    'hero.title.line2':     'اتصالات الجزائر',
    'hero.desc':            'أول مشغل وطني للاتصالات — يربط الجزائر بحلول الإنترنت عالي السرعة والاتصالات المحمولة وحلول الأعمال.',
    'hero.btn.services':    'خدماتنا',
    'hero.btn.contact':     'اتصل بنا',

    // ── Index — Stats ───────────────────────────────────
    'stat.N_abonnes': '+15 مليون',
    'stat.abonnes': 'مشترك',
    'stat.wilayas': 'ولاية مغطاة',
    'stat.debit':    '1 جيجابت/ث',
    'stat.fibre':   'ألياف ضوئية',
    'stat.support': 'دعم العملاء',
    'stat.support_num': '24/7',

    // ── Index — Section À propos ────────────────────────
    'section.about.label': 'حولنا',
    'section.about.title': 'رائد الاتصالات',
    'section.about.desc':  'اتصالات الجزائر هي الشركة الرائدة في مجال الاتصالات في الجزائر، ملتزمة بتوفير اتصال موثوق وسريع ومبتكر في جميع أنحاء البلاد.',
    'card.internet.title': 'إنترنت عالي السرعة',
    'card.internet.desc':  'تصفح وتنزيل وتواصل بسرعة عالية بفضل بنيتنا التحتية ADSL والألياف الضوئية من الجيل الأحدث.',
    'card.mobile.title':   'الخدمات المحمولة',
    'card.mobile.desc':    'ابقَ على تواصل مع أحبائك أينما كنت بفضل شبكتنا المحمولة ذات التغطية الوطنية الشاملة.',
    'card.biz.title':      'حلول الأعمال',
    'card.biz.desc':       'حسّن اتصالاتك المهنية مع عروضنا المخصصة: VPN، الهاتف IP، الاتصال الآمن.',

    // ── Index — Section Offres ──────────────────────────
    'section.offers.label': 'عروضنا',
    'section.offers.title': 'حلول لكل احتياج',
    'section.offers.desc':  'من الألياف الضوئية الفائقة السرعة إلى حلول الأعمال، عرض مناسب لكل عميل.',
    'card.adsl.title':      'ADSL',
    'card.adsl.desc':       'اتصال إنترنت عالي السرعة متاح عبر الشبكة الهاتفية الوطنية، مثالي للأفراد.',
    'card.fibre.title':     'الألياف الضوئية',
    'card.fibre.desc':      'سرعات استثنائية تصل إلى 1 جيجابت/ث بفضل شبكة الألياف الضوئية المنتشرة في المدن الكبرى.',
    'card.tel.title':       'الهاتف الثابت',
    'card.tel.desc':        'جودة مكالمات ممتازة وأسعار مناسبة على شبكتنا التاريخية للهاتف الثابت.',

    // ── Services page ───────────────────────────────────
    'services.hero.label': 'الكتالوج الكامل',
    'services.hero.title': 'خدماتنا',
    'services.hero.desc':  'اكتشف مجموعتنا الكاملة من حلول الاتصالات المصممة لتلبية احتياجات الأفراد والمهنيين والشركات.',
    'services.stat.coverage': 'تغطية وطنية',
    'services.stat.debit':    'أقصى سرعة للألياف',
    'services.stat.dispo':    'توافر الشبكة',
    'services.internet.label':  'الإنترنت',
    'services.internet.title':  'اتصالات عالية السرعة',
    'services.adsl.title':      'ADSL — إنترنت عالي السرعة',
    'services.adsl.desc':       'استمتع باتصال مستقر وسريع عبر الشبكة الهاتفية السلكية. مثالي للتصفح والبث وإرسال الرسائل الإلكترونية، متوفر في جميع ولايات البلاد.',
    'services.adsl.tag':        'حتى 20 ميجابت/ث',
    'services.fibre.title':     'الألياف الضوئية — سرعة فائقة',
    'services.fibre.desc':      'اتصل بسرعات استثنائية بفضل بنيتنا التحتية من الألياف الضوئية. نزّل أفلامًا في ثوانٍ، العب عبر الإنترنت بدون تأخير، اعمل عن بعد دون انقطاع.',
    'services.fibre.tag':       'حتى 1 جيجابت/ث',
    'services.tel.label':       'الهاتف',
    'services.tel.title':       'مكالمات صوتية',
    'services.mobile.title':    'الخدمات المحمولة',
    'services.mobile.desc':     'ابقَ على تواصل مع أحبائك بفضل عروضنا المحمولة التنافسية. تغطية 4G/LTE على كامل التراب الوطني، التجوال الدولي، وباقات تناسب جميع الأنماط.',
    'services.mobile.tag':      '4G/LTE وطنية',
    'services.fixe.title':      'الهاتف الثابت',
    'services.fixe.desc':       'تضمن شبكتنا التاريخية للهاتف الثابت جودة مكالمات ممتازة بأسعار مناسبة للاتصالات المحلية والوطنية والدولية.',
    'services.fixe.tag':        'جودة HD',
    'services.biz.label':       'الشركات',
    'services.biz.title':       'حلول مهنية',
    'services.b2b.title':       'حلول الأعمال — اتصال متكامل',
    'services.b2b.desc':        'تشمل حلولنا للأعمال الهاتف IP والاتصال الآمن بـVPN وإدارة الشبكة متعددة المواقع والخطوط المخصصة.',
    'services.b2b.tag':         'VPN · IP · MPLS',
    'services.hosting.title':   'الاستضافة ومراكز البيانات',
    'services.hosting.desc':    'استضف بياناتك وتطبيقاتك في مراكز البيانات الآمنة لدينا في الجزائر. امتثال تنظيمي، توافر 99.9%، نسخ احتياطية تلقائية، ودعم تقني مخصص 24/7.',
    'services.hosting.tag':     'سحابة · مشاركة',
    'services.cta':             'طلب عرض أسعار',

    // ── Tarif page ──────────────────────────────────────
    'tarif.hero.label':  'عروضنا',
    'tarif.hero.title':  'الخطط التسعيرية',
    'tarif.hero.desc':   'اختر العرض الذي يناسب احتياجاتك. تتضمن جميع خططنا خدمة عملاء 24/7 وتركيبًا مجانيًا.',
    'tarif.compare.label': 'التفاصيل',
    'tarif.compare.title': 'مقارنة الخطط',
    'tarif.table.feature':   'الميزات',
    'tarif.table.adsl':      'ADSL Basic',
    'tarif.table.standard':  'Fibre Standard',
    'tarif.table.premium':   'Fibre Premium',
    'tarif.table.speed':     'سرعة التنزيل',
    'tarif.table.install':   'التركيب',
    'tarif.table.telephony': 'الهاتف',
    'tarif.table.cloud':     'التخزين السحابي',
    'tarif.table.support':   'دعم العملاء',
    'tarif.table.price':     'السعر الشهري',
    'tarif.speed.adsl':      '20 ميجابت/ث',
    'tarif.speed.std':       '100 ميجابت/ث',
    'tarif.speed.prem':      '1 جيجابت/ث',
    'tarif.install.free':    'مجاني',
    'tarif.install.config':  'مجاني + إعداد',
    'tarif.tel.local':       'محلي غير محدود',
    'tarif.tel.national':    'وطني غير محدود',
    'tarif.tel.intl':        'دولي غير محدود',
    'tarif.cloud.none':      '—',
    'tarif.cloud.std':       '50 GB',
    'tarif.cloud.prem':      '500 GB',
    'tarif.support.std':     'قياسي 24/7',
    'tarif.support.prio':    'أولوية 24/7',
    'tarif.support.vip':     'VIP 24/7/365',
    'tarif.price.adsl':      '1 299 د.ج',
    'tarif.price.std':       '2 999 د.ج',
    'tarif.price.prem':      '4 999 د.ج',
    'tarif.conditions.label': 'معلومة',
    'tarif.conditions.title': 'الشروط العامة',
    'card.engagement.title':   'الالتزام',
    'card.engagement.desc':    'جميع خططنا بدون التزام. يمكنك تعديل أو إلغاء اشتراكك في أي وقت دون رسوم إضافية.',
    'card.flexibilite.title':  'المرونة',
    'card.flexibilite.desc':   'غيّر خطتك في أي وقت. انتقل إلى عرض أعلى فورًا أو قلّص خدماتك حسب احتياجاتك.',
    'card.disponibilite.title': 'التوافر',
    'card.disponibilite.desc':  'قد تختلف الأسعار والتوافر حسب موقعك. تحقق من تغطية منطقتك قبل الطلب.',
    'card.promotions.title':    'العروض الترويجية',
    'card.promotions.desc':     'تحقق من عروضنا الخاصة والتخفيضات الموسمية. قد يستفيد المشتركون الجدد من مزايا حصرية.',
    'tarif.cta.title': 'هل أنت مستعد لتغيير عرضك؟',

    // ── About page ──────────────────────────────────────
    'about.hero.label': 'تاريخنا',
    'about.hero.title': 'حول اتصالات الجزائر',
    'about.hero.desc':  'منذ تأسيسنا، نلتزم بتقديم أفضل حلول الاتصالات للجزائريين، نربط البلاد بالابتكار والموثوقية.',
    'about.mission.label': 'الأساسيات',
    'about.mission.title': 'مهمتنا',
    'about.mission.desc':  'تقديم خدمات اتصالات عالمية المستوى في متناول جميع الجزائريين، تعزيز التحول الرقمي والنمو الاقتصادي للبلاد.',
    'about.vision.title':  'رؤيتنا',
    'about.vision.desc':   'أن نكون الرائد غير المنازع في الاتصالات في شمال أفريقيا، معروفين بالتميز والابتكار والالتزام تجاه عملائنا والمجتمع.',
    'about.values.label':  'الأساسيات',
    'about.values.title':  'قيمنا الأساسية',
    'about.innovation.title':   'الابتكار',
    'about.innovation.desc':    'نستثمر باستمرار في أحدث التقنيات لتقديم حلول رائدة لعملائنا.',
    'about.connectivity.title': 'الاتصال',
    'about.connectivity.desc':  'نربط الناس والشركات والمجتمعات عبر شبكة قوية وموثوقة تغطي البلاد بأكملها.',
    'about.reliability.title':  'الموثوقية',
    'about.reliability.desc':   'تضمن خدماتنا توافرًا بنسبة 99.9% وجودة استثنائية حتى يتمكن عملاؤنا من الاعتماد علينا دائمًا.',
    'about.service.title':      'خدمة العملاء',
    'about.service.desc':       'نضع رضا عملائنا في صميم أنشطتنا، ونقدم دعمًا متعدد القنوات على مدار الساعة.',
    'about.timeline.label': 'المراحل',
    'about.timeline.title': 'مسيرتنا',
    'about.1974.title': 'التأسيس',
    'about.1974.desc':  'تأسيس اتصالات الجزائر، بدايات متواضعة مع الشبكة الوطنية للهاتف الثابت.',
    'about.1999.title': 'إطلاق ADSL',
    'about.1999.desc':  'إدخال خدمة الإنترنت ADSL عالي السرعة، مما أحدث ثورة في الوصول إلى الإنترنت في الجزائر.',
    'about.2007.title': 'الخدمات المحمولة',
    'about.2007.desc':  'الدخول إلى سوق الجوال 3G، وتوسيع محفظة خدماتنا.',
    'about.2015.title': 'الألياف الضوئية',
    'about.2015.desc':  'إطلاق النشر الوطني للألياف الضوئية، وتقديم سرعات فائقة.',
    'about.2022.title': 'التحول الرقمي',
    'about.2022.desc':  'التحديث الشامل لبنيتنا التحتية وإطلاق خدمات سحابية مبتكرة.',
    'about.2025.title': 'الريادة الإقليمية',
    'about.2025.desc':  'تعزيز مكانتنا كرائد في الاتصالات في شمال أفريقيا.',
    'about.stats.label':        'أرقام',
    'about.stats.title':        'إحصائياتنا',
    'about.stats.clients':      'عميل نشط',
    'about.stats.satisfaction': 'رضا العملاء',
    'about.stats.wilayas':      'ولاية مغطاة',
    'about.stats.emplois':      'وظيفة تم إنشاؤها',
    'about.cta.title': 'مستعد للانضمام إلينا؟',
    'about.cta.btn':   'اتصل بنا',

    // ── FAQ page ────────────────────────────────────────
    'faq.hero.label': 'أسئلة',
    'faq.hero.title': 'الأسئلة الشائعة',
    'faq.hero.desc':  'راجع إجاباتنا على الأسئلة الأكثر شيوعًا. لم تجد إجابتك؟',
    'faq.hero.link':  'اتصل بنا',
    'faq.internet.label': 'الإنترنت',
    'faq.internet.q1': 'ما الفرق بين ADSL والألياف الضوئية؟',
    'faq.internet.a1': 'يستخدم ADSL خطوط الهاتف الحالية ويوفر سرعات تصل إلى 20 ميجابت/ث. تستخدم الألياف الضوئية كابلات من الألياف وتوفر سرعات أسرع بكثير تصل إلى 1 جيجابت/ث مع زمن استجابة أقل. اختر الألياف إذا كنت تبحث عن اتصال فائق السرعة للألعاب أو العمل عن بُعد.',
    'faq.internet.q2': 'كيف أتحقق من توافر الألياف الضوئية في منطقتي؟',
    'faq.internet.a2': 'يمكنك التحقق من توافر الألياف الضوئية بزيارة موقعنا أو الاتصال بالرقم 121. ما عليك سوى إدخال عنوانك لمعرفة العروض المتاحة. تتوسع التغطية تدريجيًا لتشمل جميع المراكز الحضرية في البلاد.',
    'faq.internet.q3': 'ما هي سرعة التنزيل المضمونة؟',
    'faq.internet.a3': 'السرعات المذكورة هي قيم قصوى نظرية. تعتمد السرعة الفعلية على عدة عوامل: المسافة من المركز، جودة الخط، المعدات المستخدمة، وحمل الشبكة. تضمن عقودنا حدًا أدنى 50% من السرعة المشتركة في ظروف الاستخدام العادية.',
    'faq.tel.label': 'الهاتف',
    'faq.tel.q1': 'كيف يمكنني تفعيل خدمة المحمول؟',
    'faq.tel.a1': 'يمكنك تفعيل خدمة المحمول لدى أي وكيل لاتصالات الجزائر بتقديم هوية سارية. ستحصل على شريحة SIM ويمكنك اختيار باقتك من عروضنا. التفعيل فوري في الغالب.',
    'faq.tel.q2': 'هل يمكنني الاحتفاظ برقمي عند تغيير المشغل؟',
    'faq.tel.a2': 'نعم، نقل الأرقام متاح في الجزائر. للاحتفاظ برقمك عند تغيير المشغل، تواصل مع خدمة العملاء لدينا الذي سيرشدك خلال الإجراءات الإدارية. نقل الأرقام يتم عادةً خلال 24 إلى 48 ساعة.',
    'faq.tel.q3': 'هل تقدمون خدمات دولية؟',
    'faq.tel.a3': 'نعم، نقدم عروض مكالمات ورسائل دولية بأسعار مناسبة. راجع تعريفاتنا للوجهات الرئيسية أو اشترك في باقاتنا الدولية لتقليل تكاليفك. التجوال الدولي متاح أيضًا.',
    'faq.fact.label': 'الفاتورة',
    'faq.fact.q1': 'متى يمكنني استلام فاتورتي؟',
    'faq.fact.a1': 'تُصدر الفواتير عادةً بين الخامس والخامس عشر من كل شهر. يمكنك الاطلاع على فواتيرك وتنزيلها عبر الإنترنت من خلال حسابك على موقعنا.',
    'faq.fact.q2': 'كيف يمكنني دفع فاتورتي؟',
    'faq.fact.a2': 'تتوفر عدة طرق للدفع: الخصم التلقائي، التحويل المصرفي، الشيك، أو الدفع شخصيًا في وكالاتنا. يمكنك أيضًا الدفع عبر منصتنا الإلكترونية الآمنة.',
    'faq.fact.q3': 'ماذا أفعل في حالة فاتورة غير عادية؟',
    'faq.fact.a3': 'إذا لاحظت شذوذًا في فاتورتك، اتصل فورًا بخدمة العملاء على 121 أو عبر موقعنا. سنجري تحقيقًا شاملًا ونصحح أي خطأ محدد. لن تُفوتر أبدًا على خدمات لم تستخدمها.',
    'faq.support.label': 'الدعم التقني',
    'faq.support.q1': 'اتصالي بطيء، ماذا أفعل؟',
    'faq.support.a1': 'أولًا، تأكد من أن جهاز التوجيه قريب وجيد التهوية. أعد تشغيل المودم وجهاز التوجيه. إذا استمرت المشكلة، اتصل بدعمنا على 121 أو عبر الإنترنت.',
    'faq.support.q2': 'كيف أعيد تعيين كلمة مرور الواي فاي؟',
    'faq.support.a2': 'ادخل إلى واجهة الإدارة (عادةً 192.168.1.1). اسم المستخدم وكلمة المرور الافتراضيان موجودان على ملصق جهاز التوجيه. إذا نسيت كلمة مرورك، أعد ضبط الجهاز بالضغط على زر إعادة الضبط 10 ثوانٍ.',
    'faq.support.q3': 'ما هي أوقات عمل الدعم التقني؟',
    'faq.support.a3': 'خدمة العملاء لدينا متاحة 24/7/365. يمكنك الاتصال بنا عبر الهاتف على 121 (مجاني من خط اتصالات الجزائر)، أو البريد الإلكتروني، أو الدردشة المباشرة، أو بزيارة وكالاتنا.',
    'faq.cta.title': 'لم تجد سؤالك؟',
    'faq.cta.btn':   'اتصل بنا',

    // ── Contact page ────────────────────────────────────
    'contact.hero.label': 'الدعم والمعلومات',
    'contact.hero.title': 'اتصل بنا',
    'contact.hero.desc':  'فريقنا متاح للإجابة على جميع أسئلتك وطلبات المعلومات أو الشكاوى.',
    'contact.info.title':    'معلومات الاتصال',
    'contact.email.label':   'البريد الإلكتروني',
    'contact.phone.label':   'الهاتف',
    'contact.address.label': 'العنوان',
    'contact.address.value': '1، شارع الدكتور سعيد هواري<br>الجزائر، الجزائر',
    'contact.hours.label':   'أوقات العمل',
    'contact.hours.value':   'الأحد – الخميس: 08:00 – 17:00<br>الدعم التقني: 24 ساعة/7 أيام',
    'contact.location.label':'الموقع',
    'contact.form.title':    'أرسل لنا رسالة',
    'contact.form.prenom':   'الاسم الأول',
    'contact.form.nom':      'اللقب',
    'contact.form.email':    'البريد الإلكتروني',
    'contact.form.tel':      'الهاتف',
    'contact.form.tel.opt':  '(اختياري)',
    'contact.form.wilaya':   'الولاية',
    'contact.form.sujet':    'موضوع الطلب',
    'contact.form.msg':      'رسالتك',
    'contact.form.submit':   'إرسال الرسالة',
    'contact.form.guarantee':'ضمان الرد خلال 24 ساعة عمل',
    'contact.opt.default':       '— اختيار —',
    'contact.opt.souscription':  'اشتراك',
    'contact.opt.resiliation':   'إلغاء الاشتراك',
    'contact.opt.incident':      'حادثة تقنية',
    'contact.opt.facturation':   'الفوترة',
    'contact.opt.entreprise':    'حلول الأعمال',
    'contact.opt.autre':         'أخرى',
    'contact.ph.prenom':  'اسمك الأول',
    'contact.ph.nom':     'لقبك',
    'contact.ph.email':   'مثال@بريد.com',
    'contact.ph.tel':     '+213 XX XX XX XX',
    'contact.ph.msg':     'اشرح طلبك أو سؤالك (20 حرفًا على الأقل)...',

    // ── Validation messages ─────────────────────────────
    'val.name':    'يرجى إدخال الاسم الأول واللقب.',
    'val.email':   'عنوان البريد الإلكتروني غير صالح.',
    'val.wilaya':  'يرجى تحديد ولايتك.',
    'val.sujet':   'يرجى تحديد موضوع الطلب.',
    'val.message': 'يجب أن تحتوي الرسالة على 20 حرفًا على الأقل.',
    'val.success': 'تم إرسال الرسالة بنجاح! سنرد عليك في غضون 24 ساعة عمل.',
    'val.error':   'حدث خطأ. يرجى التحقق من معلوماتك والمحاولة مجددًا.',

    // ── Pricing cards (dynamique) ───────────────────────
    'price.popular':   'مميز',
    'price.debit':     'سرعة',
    'price.install':   'تركيب مجاني',
    'price.support':   'دعم العملاء 24/7',
    'price.choose':    'اختر',
    'price.per.month': '/شهر',
    'price.loading':   'جارٍ التحميل…',
    'price.error':     'خطأ في تحميل العروض.',

    // ── Selects dynamiques ──────────────────────────────
    'select.wilaya.default': '— اختر ولايتك —',
    'select.wilaya.error':   '— خطأ في التحميل —',
    'select.offre.default':  '— اختر عرضك —',
    'select.service.default':'— اختر خدمة —',
    'select.client.default': '— اختر عميلًا —',
    'slider.loading':        'جارٍ التحميل…',

    // ── Flash info ───────────────────────────────────────
    'flash.alert.label': 'تنبيه',
    'flash.alert.text':  '📢 عرض ترويجي: استفد من خصم -20% على عروض الألياف البصرية حتى نهاية الشهر! | ⚡ اتصالات الجزائر: دائما أقرب._________________|________________ بمناسبة عيد الاستقلال، استفد من خصم -15% على عروض الألياف البصرية حتى نهاية الشهر! | ⚡ اتصالات الجزائر: دائما أقرب.',
    'flash.alert.title': '🚨تنبيه!🚨',
  }
};

// ── Moteur de traduction ─────────────────────────────────────
function atT(key) {
  const lang = localStorage.getItem('at-lang') || 'fr';
  return AT_TRANSLATIONS[lang]?.[key] ?? AT_TRANSLATIONS['fr'][key] ?? key;
}

function atApplyLang(lang) {
  if (!AT_TRANSLATIONS[lang]) return;

  const t = AT_TRANSLATIONS[lang];
  const isRTL = lang === 'ar';

  // Direction et langue du document
  document.documentElement.lang = lang;
  document.documentElement.dir  = isRTL ? 'rtl' : 'ltr';

  // Éléments avec data-i18n (textContent)
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (t[key] !== undefined) el.textContent = t[key];
  });

  // Éléments avec data-i18n-html (innerHTML pour <br> etc.)
  document.querySelectorAll('[data-i18n-html]').forEach(el => {
    const key = el.getAttribute('data-i18n-html');
    if (t[key] !== undefined) el.innerHTML = t[key];
  });

  // Placeholders
  document.querySelectorAll('[data-i18n-ph]').forEach(el => {
    const key = el.getAttribute('data-i18n-ph');
    if (t[key] !== undefined) el.placeholder = t[key];
  });

  // Title attributes
  document.querySelectorAll('[data-i18n-title]').forEach(el => {
    const key = el.getAttribute('data-i18n-title');
    if (t[key] !== undefined) el.title = t[key];
  });

  // Liens de navigation (par href — sans modifier le HTML)
  const navMap = {
    'index.html':    'nav.home',
    'services.html': 'nav.services',
    'tarif.html':    'nav.tarifs',
    'about.html':    'nav.about',
    'faq.html':      'nav.faq',
    'contact.html':  'nav.contact',
  };
  document.querySelectorAll('.nav-links a, .footer-nav-links a').forEach(a => {
    const href = a.getAttribute('href');
    if (navMap[href] && t[navMap[href]]) a.textContent = t[navMap[href]];
  });

  // Footer copy & thesis
  const fc = document.querySelector('.footer-copy');
  if (fc) fc.textContent = t['footer.copy'];
  const ft = document.querySelector('.footer-thesis');
  if (ft) ft.textContent = t['footer.thesis'];

  // Bouton de langue actif
  document.querySelectorAll('.lang-option').forEach(btn => {
    btn.classList.toggle('active', btn.getAttribute('data-lang') === lang);
  });

  // Persister
  localStorage.setItem('at-lang', lang);

  // Flash info explicite (évite les soucis d'animation avec textContent)
  var flashLabel = document.querySelector('.flash-alert-label');
  var flashText = document.querySelector('.contenu-defilant span');
  var flashInfo = document.querySelector('.flash-info');
  if (flashLabel && t['flash.alert.label'] !== undefined) flashLabel.textContent = t['flash.alert.label'];
  if (flashText && t['flash.alert.text'] !== undefined) flashText.textContent = t['flash.alert.text'];
  if (flashInfo && t['flash.alert.title'] !== undefined) flashInfo.title = t['flash.alert.title'];

  // Émettre un événement pour que main.js puisse rafraîchir le contenu dynamique
  document.dispatchEvent(new CustomEvent('at:langchange', { detail: { lang } }));
}

function atInitLang() {
  const saved = localStorage.getItem('at-lang') || 'fr';
  atApplyLang(saved);

  document.querySelectorAll('.lang-option').forEach(btn => {
    btn.addEventListener('click', () => atApplyLang(btn.getAttribute('data-lang')));
  });
}

document.addEventListener('DOMContentLoaded', atInitLang);
