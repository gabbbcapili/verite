
const PRECACHE = 'precache-v1';
const RUNTIME = 'runtime';
const urlStartsWith = '/auditForm';

// A list of local resources we always want to be cached.
const PRECACHE_URLS = [
  '/css/base/core/menu/menu-types/vertical-menu.css',
  '/css/base/themes/bordered-layout.css',
  '/css/base/themes/dark-layout.css',
  '/css/base/themes/semi-dark-layout.css',
  '/css/core.css',
  '/css/overrides.css',
  '/css/style.css',
  '/images/ico/logo-v.png',
  '/js/core/app-menu.js',
  '/js/core/app.js',
  '/js/core/scripts.js',
  '/js/scripts/customizer.js',
  '/js/scripts/forms-validation/form-normal.js',
  '/js/scripts/print/printThis.js',
  '/js/scripts/tables/table-question.js',
  '/livewire/livewire.js?id=de3fca26689cb5a39af4',
  '/vendors/css/animate/animate.min.css',
  '/vendors/css/bootstrap-extended.css',
  '/vendors/css/extensions/sweetalert2.min.css',
  '/vendors/css/forms/select/select2.min.css',
  '/vendors/css/vendors.min.css',
  '/vendors/js/alpinejs/alpine.js',
  '/vendors/js/extensions/polyfill.min.js',
  '/vendors/js/extensions/sweetalert2.all.min.js',
  '/vendors/js/forms/select/select2.full.min.js',
  '/vendors/js/jquery/jquery-ui.js',
  '/vendors/js/ui/jquery.sticky.js',
  '/vendors/js/vendors.min.js',
];

self.addEventListener('message', event => {
  const currentUrl = event.data.current_url;
  console.log(currentUrl);
  if (currentUrl && !PRECACHE_URLS.includes(currentUrl)) {
    // PRECACHE_URLS.push(currentUrl);
    caches.open(RUNTIME)
      .then(cache => {
        cache.addAll([currentUrl]);
      });
    caches.open(PRECACHE)
      .then(cache => {
        cache.addAll(PRECACHE_URLS);
      });
  }
});


// The install handler takes care of precaching the resources we always need.
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(PRECACHE)
      .then(cache => cache.addAll(PRECACHE_URLS))
      .then(self.skipWaiting())
  );
});

// The activate handler takes care of cleaning up old caches.
self.addEventListener('activate', event => {
  const currentCaches = [PRECACHE, RUNTIME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
    }).then(cachesToDelete => {
      return Promise.all(cachesToDelete.map(cacheToDelete => {
        return caches.delete(cacheToDelete);
      }));
    }).then(() => self.clients.claim())
  );
});

// The fetch handler serves responses for same-origin resources from a cache.
// If no response is found, it populates the runtime cache with the response
// from the network before returning it to the page.
self.addEventListener('fetch', event => {
  // Skip cross-origin requests, like those for Google Analytics.
  if (event.request.method !== 'GET') {
    return;
  }
  var requestUrl = new URL(event.request.url);
  if (event.request.url.startsWith(self.location.origin)) {
    event.respondWith(
      (async function () {
        try {
          // return await fetch(event.request);
          return await fetch(event.request).then(response => {
            return caches.open(RUNTIME).then(cache => {
              // Put a copy of the response in the runtime cache.
              if (requestUrl.pathname.startsWith(urlStartsWith)) {
                return cache.put(event.request, response.clone()).then(() => {
                  return response;
                });
              }else{
                return response;
              }
            });
          });
        } catch (err) {
          return caches.match(event.request);
          caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) {
              return cachedResponse;
            }
          })
        }
      })()

    );
  }
});
