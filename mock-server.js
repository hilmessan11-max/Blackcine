const http = require('http');
const https = require('https');
const fs = require('fs');
const path = require('path');

const PORT_API = 8000;
const PORT_STATIC = 8080;
const FRONTEND_DIR = path.join(__dirname, 'Blackcine-Frontend');

const TMDB_API_KEY = process.env.TMDB_API_KEY || null;
const TMDB_BEARER = process.env.TMDB_BEARER_TOKEN || null;
const TMDB_BASE_URL = 'https://api.themoviedb.org/3';

const MIME_TYPES = {
  '.html': 'text/html',
  '.css': 'text/css',
  '.js': 'application/javascript',
  '.json': 'application/json',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.woff': 'font/woff',
  '.woff2': 'font/woff2',
  '.ttf': 'font/ttf',
  '.webp': 'image/webp',
};

const films = [
  {
    id: 1,
    name: 'Sankara - Le Prix de la Liberté',
    poster: 'assets/images/F1.jpg',
    release_date: '2024-03-15',
    average_rating: 4.7,
    genres: ['Drame', 'Historique'],
    origin_country: 'Burkina Faso',
    synopsis: 'Un portrait vibrant de Thomas Sankara, leader charismatique du Burkina Faso, qui a redéfini la destinée de son pays.'
  },
  {
    id: 2,
    name: 'Les Larmes du Lac Tchad',
    poster: 'assets/images/F2.jpg',
    release_date: '2024-06-20',
    average_rating: 4.5,
    genres: ['Drame', 'Thriller'],
    origin_country: 'Nigéria',
    synopsis: 'Une famille tchadienne lutte pour survivre face aux changements climatiques qui menacent leur mode de vie ancestral.'
  },
  {
    id: 3,
    name: 'Dakar Blues',
    poster: 'assets/images/F3.jpg',
    release_date: '2025-01-10',
    average_rating: 4.3,
    genres: ['Comédie', 'Romance'],
    origin_country: 'Sénégal',
    synopsis: 'Un musicien de Dakar navigue entre amour, ambition et tradition dans les rues animées de la capitale sénégalaise.'
  },
  {
    id: 4,
    name: 'L\'Héritage des Griots',
    poster: 'assets/images/F4.jpg',
    release_date: '2024-09-05',
    average_rating: 4.8,
    genres: ['Drame', 'Documentaire'],
    origin_country: 'Mali',
    synopsis: 'Un vieux griot transmet ses connaissances à son petit-fils lors d\'un voyage initiatique à travers le Mali.'
  },
  {
    id: 5,
    name: 'Noirs et Blancs',
    poster: 'assets/images/image1.jpg',
    release_date: '2025-02-14',
    average_rating: 4.1,
    genres: ['Comédie', 'Romance'],
    origin_country: 'Côte d\'Ivoire',
    synopsis: 'Deux familles ivoiriennes de milieux différents se retrouvent liées par le mariage de leurs enfants.'
  },
  {
    id: 6,
    name: 'La Forteresse Invisible',
    poster: 'assets/images/image2.jpg',
    release_date: '2024-11-30',
    average_rating: 4.6,
    genres: ['Action', 'Thriller'],
    origin_country: 'Cameroun',
    synopsis: 'Une agente de renseignement camerounaise doit empêcher un complot international avant qu\'il ne soit trop tard.'
  },
  {
    id: 7,
    name: 'Sous le Baobab',
    poster: 'assets/images/image3.jpg',
    release_date: '2024-07-22',
    average_rating: 4.4,
    genres: ['Drame', 'Animation'],
    origin_country: 'Bénin',
    synopsis: 'Un conte animé qui suit les aventures de jeunes enfants béninois découvrant les secrets du baobab magique.'
  },
  {
    id: 8,
    name: 'Nairobi Rising',
    poster: 'assets/images/image4.jpg',
    release_date: '2025-04-01',
    average_rating: 4.2,
    genres: ['Horreur', 'Thriller'],
    origin_country: 'Kenya',
    synopsis: 'Une étudiante américaine d\'origine kényane découvre des secrets terrifiants lors de son retour à Nairobi.'
  },
];

const series = [
  {
    id: 1,
    name: 'Lagos Confidential',
    poster: 'assets/images/image5.jpg',
    release_date: '2024-01-15',
    average_rating: 4.6,
    genres: ['Drame', 'Thriller'],
    origin_country: 'Nigéria',
    synopsis: 'Une enquête policière dans les milieux corrompus de Lagos révèle des secrets politiques dangereux.',
    seasons_count: 3
  },
  {
    id: 2,
    name: 'Les Rois du Soleil',
    poster: 'assets/images/image6.jpg',
    release_date: '2024-05-10',
    average_rating: 4.3,
    genres: ['Drame', 'Historique'],
    origin_country: 'Sénégal',
    synopsis: 'L\'histoire epic des royaumes anciens du Sénégal, racontée à travers les yeux d\'une princesse guerrière.',
    seasons_count: 2
  },
  {
    id: 3,
    name: 'Kinshasa Stories',
    poster: 'assets/images/image7.jpg',
    release_date: '2024-08-20',
    average_rating: 4.1,
    genres: ['Comédie', 'Drame'],
    origin_country: 'Mali',
    synopsis: 'Les vies entremêlées de plusieurs habitants de Kinshasa, chacun poursuivant ses rêves dans la ville.',
    seasons_count: 1
  },
  {
    id: 4,
    name: 'Savane',
    poster: 'assets/images/image8.jpg',
    release_date: '2024-12-01',
    average_rating: 4.5,
    genres: ['Drame', 'Romance'],
    origin_country: 'Burkina Faso',
    synopsis: 'Une saga familiale qui suit trois générations d\'une famille burkinabè à travers les turbulences du temps.',
    seasons_count: 2
  },
];

const articles = [
  {
    id: 1,
    title: 'Le renouveau du cinéma africain en 2025',
    excerpt: 'Comment les cinéastes africains redéfinissent les codes du cinéma mondial avec des œuvres audacieuses et innovantes.',
    thumbnail: 'assets/images/image191.jpg',
    published_at: '2025-07-15',
    category: 'Actualités',
    author: 'Aminata Diallo',
    views_count: 12450
  },
  {
    id: 2,
    title: 'Interview exclusive : Ousmane Sembène, l\'héritage vivant',
    excerpt: 'Un aperçu rare de l\'héritage du père du cinéma africain et son influence sur les nouvelles générations.',
    thumbnail: 'assets/images/image171.jpg',
    published_at: '2025-06-20',
    category: 'Interviews',
    author: 'Ibrahim Konaté',
    views_count: 8930
  },
  {
    id: 3,
    title: 'Top 10 des festivals de cinéma africain incontournables',
    excerpt: 'Découvrez les festivals qui façonnent le paysage cinématographique du continent.',
    thumbnail: 'assets/images/image181.jpg',
    published_at: '2025-05-10',
    category: 'Festivals',
    author: 'Fatoumata Camara',
    views_count: 6720
  },
  {
    id: 4,
    title: 'La jeune garde du cinéma ivoirien',
    excerpt: 'Portrait des réalisateurs émergents qui font rayonner la Côte d\'Ivoire sur la scène internationale.',
    thumbnail: 'assets/images/image201.jpg',
    published_at: '2025-04-18',
    category: 'Portraits',
    author: 'Kouassi Yao',
    views_count: 5340
  },
  {
    id: 5,
    title: 'Femmes dans le cinéma africain : percées et défis',
    excerpt: 'Analyse du rôle croissant des femmes dans l\'industrie cinématographique africaine.',
    thumbnail: 'assets/images/image211.jpg',
    published_at: '2025-03-25',
    category: 'Analyses',
    author: 'Aïcha Traoré',
    views_count: 4210
  },
];

const emissions = [
  {
    id: 1,
    title: 'Ciné Talk',
    description: 'Discussion hebdomadaire sur l\'actualité du cinéma africain avec des invités de marque.',
    thumbnail: 'assets/images/blogv29.jpg',
    host: 'Moussa Diabaté',
    schedule: 'Chaque mercredi à 20h'
  },
  {
    id: 2,
    title: 'Behind the Scenes',
    description: 'Plongée coulisses des plus grandes productions cinématographiques africaines.',
    thumbnail: 'assets/images/image9.jpg',
    host: 'Nadia Ouédraogo',
    schedule: 'Chaque samedi à 18h'
  },
  {
    id: 3,
    title: 'Ciné Junior',
    description: 'Découverte du cinéma pour les jeunes avec critiques et interviews.',
    thumbnail: 'assets/images/mv-item10.jpg',
    host: 'Samuel Abiola',
    schedule: 'Chaque dimanche à 15h'
  },
];

const festivals = [
  {
    id: 1,
    name: 'FESPACO 2025',
    description: 'Le plus grand festival panafricain de cinéma, rendez-vous incontournable du septième art africain.',
    starts_at: '2025-02-22',
    ends_at: '2025-03-01',
    country: 'Burkina Faso',
    city: 'Ouagadougou',
    status: 'upcoming'
  },
  {
    id: 2,
    name: 'Festival de Carthage',
    description: 'Le festival international du film de Carthage, vitrine du cinéma méditerranéen et africain.',
    starts_at: '2025-10-18',
    ends_at: '2025-10-25',
    country: 'Tunisie',
    city: 'Carthage',
    status: 'upcoming'
  },
  {
    id: 3,
    name: 'Dak\'Art Biennale',
    description: 'Biennale de Dakar mêlant arts visuels et cinématographiques dans un spectacle unique.',
    starts_at: '2025-05-16',
    ends_at: '2025-06-15',
    country: 'Sénégal',
    city: 'Dakar',
    status: 'active'
  },
];

const selections = [
  {
    id: 1,
    title: 'Sélection Officielle FESPACO 2025',
    description: 'Les films en compétition pour la prestigieuse Étalon d\'Or de Yennenga.',
    type: 'official',
    is_active: true
  },
  {
    id: 2,
    title: 'Films à ne pas manquer',
    description: 'Notre sélection des meilleurs films africains du moment.',
    type: 'editorial',
    is_active: true
  },
  {
    id: 3,
    title: 'Cinéma d\'auteur africain',
    description: 'Des œuvres qui repoussent les frontières du cinéma contemporain.',
    type: 'thematic',
    is_active: true
  },
];

const castings = [
  {
    id: 1,
    title: 'Casting - Le Fleuve du Silence',
    description: 'Recherche acteurs et actrices pour drame familial tourné au Sénégal.',
    casting_type: 'feature_film',
    city: 'Dakar',
    country: 'Sénégal',
    end_date: '2025-08-30',
    is_urgent: true,
    roles_count: 8
  },
  {
    id: 2,
    title: 'Casting - Séries TV "Sapeurs"',
    description: 'Casting ouvert pour nouvelle série sur la culture de la SAPE au Congo.',
    casting_type: 'series',
    city: 'Douala',
    country: 'Cameroun',
    end_date: '2025-09-15',
    is_urgent: false,
    roles_count: 12
  },
  {
    id: 3,
    title: 'Casting - Documentaire "Voix du Sahel"',
    description: 'Recherche témoins et intervenants pour documentaire sur la vie au Sahel.',
    casting_type: 'documentary',
    city: 'Niamey',
    country: 'Nigéria',
    end_date: '2025-07-20',
    is_urgent: true,
    roles_count: 5
  },
];

const projects = [
  {
    id: 1,
    title: 'Projet "Teranga" - Court-métrage',
    description: 'Court-métrage sur l\'hospitalité sénégalaise et les liens familiaux.',
    project_type: 'short_film',
    city: 'Saint-Louis',
    country: 'Sénégal',
    status: 'in_production',
    team_size: 15,
    budget: '2500000',
    thumbnail: 'assets/images/F1.jpg'
  },
  {
    id: 2,
    title: 'Série "Marché Balafon"',
    description: 'Série comique se déroulant dans un marché vibrant d\'Abidjan.',
    project_type: 'series',
    city: 'Abidjan',
    country: 'Côte d\'Ivoire',
    status: 'pre_production',
    team_size: 22,
    budget: '8500000',
    thumbnail: 'assets/images/F1.jpg'
  },
  {
    id: 3,
    title: 'Documentaire "Lumière du Nord"',
    description: 'Documentaire explorant les traditions cinématographiques du Nord du Mali.',
    project_type: 'documentary',
    city: 'Tombouctou',
    country: 'Mali',
    status: 'completed',
    team_size: 8,
    budget: '1200000',
    thumbnail: 'assets/images/F1.jpg'
  },
];

const contests = [
  {
    id: 1,
    title: 'Concourt Court-Métrage Panafricain 2025',
    description: 'Soumettez votre court-métrage et gagnez une production professionnelle.',
    contest_type: 'short_film',
    registration_deadline: '2025-09-01',
    prizes: [
      { rank: 1, description: 'Production professionnelle complète', value: '10000000' },
      { rank: 2, description: 'Équipement de tournage', value: '3000000' },
      { rank: 3, description: 'Formation en réalisation', value: '1500000' }
    ]
  },
  {
    id: 2,
    title: 'Prix du Scénario Africain',
    description: 'Compétition pour les meilleurs scénarios originaux du continent.',
    contest_type: 'screenplay',
    registration_deadline: '2025-10-15',
    prizes: [
      { rank: 1, description: 'Développement du scénario avec mentorat', value: '5000000' },
      { rank: 2, description: 'Bourse de recherche', value: '2000000' },
      { rank: 3, description: 'Accès aux ateliers du FESPACO', value: '750000' }
    ]
  },
];

const partners = [
  {
    id: 1,
    name: 'Institut Français',
    logo: 'assets/images/default-partner.png',
    partner_type: 'institution',
    description: 'Partenaire culturel majeur pour la promotion du cinéma africain en France.'
  },
  {
    id: 2,
    name: 'Canal+ Afrique',
    logo: 'assets/images/default-partner.png',
    partner_type: 'media',
    description: 'Diffuseur principal de productions cinématographiques africaines.'
  },
  {
    id: 3,
    name: 'BNP Paribas Afrique',
    logo: 'assets/images/default-partner.png',
    partner_type: 'sponsor',
    description: 'Soutien financier au développement de l\'industrie cinématographique africaine.'
  },
];

const videos = [
  {
    id: 1,
    title: 'Bande-annonce : Sankara - Le Prix de la Liberté',
    thumbnail: 'assets/images/image191.jpg',
    source_url: 'https://example.com/videos/sankara-trailer.mp4',
    duration: '2:34',
    views_count: 245000
  },
  {
    id: 2,
    title: 'Interview : Les réalisateurs du futur',
    thumbnail: 'assets/images/image171.jpg',
    source_url: 'https://example.com/videos/interview-directors.mp4',
    duration: '12:45',
    views_count: 89000
  },
  {
    id: 3,
    title: 'Reportage : Coulisses du FESPACO',
    thumbnail: 'assets/images/ceb1.jpg',
    source_url: 'https://example.com/videos/fespaco-bts.mp4',
    duration: '8:20',
    views_count: 56000
  },
];

const genres = [
  { id: 1, name: 'Action' },
  { id: 2, name: 'Comédie' },
  { id: 3, name: 'Drame' },
  { id: 4, name: 'Horreur' },
  { id: 5, name: 'Romance' },
  { id: 6, name: 'Thriller' },
  { id: 7, name: 'Documentaire' },
  { id: 8, name: 'Animation' },
];

const countries = [
  'Bénin', 'Burkina Faso', 'Cameroun', 'Côte d\'Ivoire', 'Sénégal',
  'Nigéria', 'Kenya', 'Mali', 'Ghana', 'Togo', 'Tunisie', 'Maroc'
];

const footerData = {
  stats: {
    films: 1250,
    series: 380,
    users: 45000,
    articles: 890
  }
};

function getListForType(type) {
  const map = {
    films: films,
    series: series,
    articles: articles,
    emissions: emissions,
    festivals: festivals,
    selections: selections,
    castings: castings,
    projects: projects,
    contests: contests,
    partners: partners,
    videos: videos,
  };
  return map[type] || null;
}

function paginate(array, page, perPage) {
  const p = parseInt(page) || 1;
  const pp = parseInt(perPage) || 10;
  const start = (p - 1) * pp;
  const end = start + pp;
  return {
    data: array.slice(start, end),
    current_page: p,
    last_page: Math.ceil(array.length / pp),
    total: array.length,
    per_page: pp,
  };
}

function getHomeData() {
  return {
    slides: [
      { id: 1, title: 'Sankara - Le Prix de la Liberté', image: 'assets/images/F1.jpg', link: '/films/1' },
      { id: 2, title: 'FESPACO 2025 - Le plus grand festival africain', image: 'assets/images/image191.jpg', link: '/festivals/1' },
      { id: 3, title: 'Nairobi Rising - Thriller kényan', image: 'assets/images/image171.jpg', link: '/films/8' },
    ],
    featured_articles: articles.slice(0, 3),
    featured_videos: [],
    top_films: films.slice(0, 4),
    top_series: series.slice(0, 2),
    now_showing: [],
    editorial_selection: selections[0],
    classics: films.slice(4, 8),
    active_castings: castings.filter(c => c.is_urgent),
    active_projects: projects.slice(0, 2),
    active_contests: contests,
    partners: partners.slice(0, 2),
  };
}

function handleApiRequest(req, res) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
  res.setHeader('Content-Type', 'application/json; charset=utf-8');

  if (req.method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  const url = new URL(req.url, `http://localhost:${PORT_API}`);
  const pathname = url.pathname;
  const page = url.searchParams.get('page');
  const perPage = url.searchParams.get('per_page');

  let result = null;

  if (pathname === '/api/v1/home') {
    result = { success: true, data: getHomeData() };
  } else if (pathname === '/api/v1/titles/films') {
    result = { success: true, ...paginate(films, page, perPage) };
  } else if (pathname === '/api/v1/titles/series') {
    result = { success: true, ...paginate(series, page, perPage) };
  } else if (pathname === '/api/v1/articles') {
    result = { success: true, ...paginate(articles, page, perPage) };
  } else if (pathname === '/api/v1/articles/featured') {
    result = { success: true, data: articles[0] };
  } else if (pathname === '/api/v1/articles/popular') {
    result = { success: true, data: [] };
  } else if (pathname === '/api/v1/emissions') {
    result = { success: true, data: emissions };
  } else if (pathname === '/api/v1/festivals') {
    result = { success: true, data: festivals };
  } else if (pathname === '/api/v1/festivals/active') {
    const active = festivals.find(f => f.status === 'active') || null;
    result = { success: true, data: active };
  } else if (pathname === '/api/v1/selections') {
    result = { success: true, data: selections };
  } else if (pathname === '/api/v1/castings') {
    result = { success: true, data: castings };
  } else if (pathname === '/api/v1/projects') {
    result = { success: true, data: projects };
  } else if (pathname === '/api/v1/contests') {
    result = { success: true, data: contests };
  } else if (pathname === '/api/v1/videos') {
    result = { success: true, data: videos };
  } else if (pathname === '/api/v1/videos/featured') {
    result = { success: true, data: null };
  } else if (pathname === '/api/v1/showtimes') {
    result = { success: true, data: [] };
  } else if (pathname === '/api/v1/showtimes/now-showing') {
    result = { success: true, data: [] };
  } else if (pathname === '/api/v1/titles/genres') {
    result = { success: true, data: genres };
  } else if (pathname === '/api/v1/titles/countries') {
    result = { success: true, data: countries };
  } else if (pathname === '/api/v1/partners') {
    result = { success: true, data: partners };
  } else if (pathname === '/api/v1/footer') {
    result = { success: true, data: footerData };
  } else if (pathname === '/api/v1/footer/stats') {
    result = { success: true, data: footerData.stats };
  } else {
    // /api/v1/{type}/{id} - detail route
    const segments = pathname.split('/').filter(Boolean);
    if (segments.length === 4 && segments[0] === 'api' && segments[1] === 'v1') {
      const type = segments[2];
      const id = parseInt(segments[3]);
      const list = getListForType(type);
      if (list) {
        const item = list.find(i => i.id === id);
        if (item) {
          result = { success: true, data: item };
        }
      }
    }
  }

  if (result) {
    res.writeHead(200);
    res.end(JSON.stringify(result));
  } else {
    res.writeHead(404);
    res.end(JSON.stringify({ success: false, message: 'Endpoint not found' }));
  }
}

function handleTmdbProxy(urlPath, queryString, res) {
  if (!TMDB_BEARER && !TMDB_API_KEY) {
    res.writeHead(503, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ success: false, message: 'TMDB non configuré côté serveur (TMDB_API_KEY/TMDB_BEARER_TOKEN manquant)' }));
    return;
  }

  // Validation whitelist
  const allowedPrefixes = ['/trending/','/movie/','/tv/','/search/','/discover/','/genre/','/configuration','/find/'];
  const isAllowed = allowedPrefixes.some(p => urlPath.startsWith(p));
  if (!isAllowed) {
    res.writeHead(403, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ success: false, message: 'Endpoint TMDB non autorisé' }));
    return;
  }
  if (urlPath.includes('..')) {
    res.writeHead(400, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ success: false, message: 'Chemin invalide' }));
    return;
  }

  let url = `${TMDB_BASE_URL}${urlPath}${queryString ? '?' + queryString : ''}`;
  // Si on n'a que la clé v3, on l'ajoute en query
  if (!TMDB_BEARER && TMDB_API_KEY) {
    const sep = url.includes('?') ? '&' : '?';
    url += `${sep}api_key=${TMDB_API_KEY}`;
  }

  const headers = {};
  if (TMDB_BEARER) headers['Authorization'] = `Bearer ${TMDB_BEARER}`;

  const req = https.get(url, { headers, timeout: 8000 }, (tmdbRes) => {
    let data = '';
    tmdbRes.on('data', chunk => { data += chunk; });
    tmdbRes.on('end', () => {
      res.writeHead(tmdbRes.statusCode, { 'Content-Type': 'application/json' });
      res.end(data);
    });
  });
  req.on('error', (err) => {
    res.writeHead(502, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ success: false, message: 'Erreur proxy TMDB', error: err.message }));
  });
  req.on('timeout', () => {
    req.destroy();
    res.writeHead(504, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ success: false, message: 'Timeout TMDB' }));
  });
}

function handleStaticRequest(req, res) {
  const urlParts = req.url.split('?');
  const urlPath = urlParts[0];
  const queryString = urlParts[1] || '';

  // TMDB proxy: /tmdb/trending/movie/week
  if (urlPath.startsWith('/tmdb/')) {
    const tmdbPath = urlPath.replace('/tmdb', '');
    handleTmdbProxy(tmdbPath, queryString, res);
    return;
  }

  // Sécuriser le path — prévention traversal
  const normalizedPath = path.normalize(urlPath === '/' ? 'index.html' : urlPath.replace(/^\/+/, ''));
  let filePath = path.join(FRONTEND_DIR, normalizedPath);

  // Vérifier que le fichier reste dans FRONTEND_DIR
  if (!filePath.startsWith(FRONTEND_DIR)) {
    res.writeHead(403, { 'Content-Type': 'text/plain' });
    res.end('Forbidden');
    return;
  }

  try {
    if (!fs.existsSync(filePath) || fs.statSync(filePath).isDirectory()) {
      filePath = path.join(FRONTEND_DIR, 'index.html');
    }
  } catch {
    filePath = path.join(FRONTEND_DIR, 'index.html');
  }

  const ext = path.extname(filePath).toLowerCase();
  const contentType = MIME_TYPES[ext] || 'application/octet-stream';

  fs.readFile(filePath, (err, data) => {
    if (err) {
      res.writeHead(404, { 'Content-Type': 'text/plain' });
      res.end('Not Found');
      return;
    }
    res.writeHead(200, { 'Content-Type': contentType });
    res.end(data);
  });
}

const apiServer = http.createServer(handleApiRequest);
apiServer.listen(PORT_API, () => {
  console.log(`Mock API server running at http://localhost:${PORT_API}`);
});

const staticServer = http.createServer(handleStaticRequest);
staticServer.listen(PORT_STATIC, () => {
  console.log(`Static file server running at http://localhost:${PORT_STATIC}`);
  console.log(`Serving: ${FRONTEND_DIR}`);
});

console.log('\n--- Mock Server Started ---');
console.log('API endpoints available at http://localhost:8000/api/v1/*');
console.log('Frontend served at http://localhost:8080');
console.log('Press Ctrl+C to stop.\n');
