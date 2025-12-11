/* training.js — простий, мінімальний */

const q  = (s, r = document) => r.querySelector(s);
const qa = (s, r = document) => Array.from(r.querySelectorAll(s));

const contentTitle = q('#contentTitle');
const contentBody  = q('#contentBody');

const searchInput       = q('#searchInput');
const navDesktop        = q('#navList');
const mobileMenu        = q('#mobileMenu');
const openMenuBtn       = q('#openMenuBtn');
const closeMenuBtn      = q('#closeMenuBtn');
const navMobile         = q('#navListMobile');
const searchInputMobile = q('#searchInputMobile');
const mainArticle       = q('#mainArticle');
const mainSection       = q('#mainSection');

const chaptersBase = mainSection?.getAttribute('data-chapters-base');

// ---- Проста анімація мобільного меню (виїзд збоку + затемнення) ----
const overlay = mobileMenu?.querySelector(':scope > .absolute');
const panel   = mobileMenu?.querySelector(':scope > .relative');

if (overlay) overlay.classList.add('opacity-0','transition-opacity','duration-200','ease-out');
if (panel)   panel.classList.add('-translate-x-full','transition-transform','duration-200','ease-out');

function setMobileMenu(open) {
  if (!mobileMenu) return;
  if (open) {
    mobileMenu.classList.remove('hidden');
    openMenuBtn?.setAttribute('aria-expanded','true');
    document.documentElement.classList.add('overflow-hidden');
    document.body.classList.add('overflow-hidden');
    requestAnimationFrame(() => {
      overlay?.classList.add('opacity-100');
      panel?.classList.remove('-translate-x-full');
      panel?.classList.add('translate-x-0');
    });
    openMenuBtn?.classList.add('hidden');
  } else {
    overlay?.classList.remove('opacity-100');
    panel?.classList.remove('translate-x-0');
    panel?.classList.add('-translate-x-full');
    const onEnd = () => {
      mobileMenu.classList.add('hidden');
      panel?.removeEventListener('transitionend', onEnd);
      openMenuBtn?.classList.remove('hidden');
      openMenuBtn?.setAttribute('aria-expanded','false');
      document.documentElement.classList.remove('overflow-hidden');
      document.body.classList.remove('overflow-hidden');
    };
    panel?.addEventListener('transitionend', onEnd);
  }
}

openMenuBtn?.addEventListener('click', () => setMobileMenu(true));
closeMenuBtn?.addEventListener('click', () => setMobileMenu(false));
overlay?.addEventListener('click', () => setMobileMenu(false));
document.addEventListener('keydown', e => {
  if (e.key === 'Escape' && !mobileMenu?.classList.contains('hidden')) setMobileMenu(false);
});

// ---- Пошук (категорія -> весь список; інакше лише збіги пунктів) ----
function applyFilter(root, query) {
  const qv = (query || '').trim().toLowerCase();
  const groups = qa('.category-group', root);
  let any = false;

  groups.forEach(g => {
    const catName = g.dataset.catName || '';
    const items = qa('.category-item', g);
    items.forEach(li => li.style.display = 'none');

    if (!qv || catName.includes(qv)) {
      g.style.display = '';
      items.forEach(li => li.style.display = '');
      any = true;
      return;
    }

    let matched = 0;
    items.forEach(li => {
      const t = li.dataset.itemTitle || '';
      if (t.includes(qv)) { li.style.display = ''; matched++; }
    });
    g.style.display = matched ? '' : 'none';
    if (matched) any = true;
  });

  if (!any) {
    if (!q('.no-results', root)) {
      const p = document.createElement('p');
      p.className = 'no-results text-sm text-gray-500 px-2';
      p.textContent = 'Нічого не знайдено.';
      root.appendChild(p);
    }
  } else {
    q('.no-results', root)?.remove();
  }
}
searchInput?.addEventListener('input', () => applyFilter(navDesktop, searchInput.value));
searchInputMobile?.addEventListener('input', () => applyFilter(navMobile,  searchInputMobile.value));
applyFilter(navDesktop, ''); applyFilter(navMobile, '');

// ---- Плоский список розділів + мапа title ----
const navLinks = qa('#navList .category-item a[data-slug]');
const order = navLinks.map(a => ({ slug: a.getAttribute('data-slug'), width: a.getAttribute('data-width'), title: a.textContent.trim() }));
const titleBySlug = order.reduce((acc, x) => { acc[x.slug] = x.title; return acc; }, {});
const widthBySlug = order.reduce((acc, x) => { acc[x.slug] = x.width; return acc; }, {});

function setActive(slug) {
  qa('.category-item a').forEach(a => a.classList.remove('bg-[#597f85]/20'));
  const links = qa(`.category-item a[data-slug="${CSS.escape(slug)}"]`);
  links.forEach(a => a.classList.add('bg-[#597f85]/20'));
}

function updateUrl(slug) {
  const targetSlug = (slug === 'intro') ? '' : slug;
  const first = location.pathname.split('/').filter(Boolean)[0];
  history.pushState(null, '', `/${first}/${targetSlug ? targetSlug + '/' : ''}${location.search}${location.hash}`);
}

// ---- Завантаження контенту + "наступний розділ" ----
async function loadLessonBySlug(slug) {
  contentTitle.textContent = titleBySlug[slug] || 'Матеріал';
  contentBody.innerHTML = `
    <div class="animate-pulse space-y-3">
      <div class="h-4 w-2/3 rounded bg-gray-200"></div>
      <div class="h-4 w-1/2 rounded bg-gray-200"></div>
      <div class="h-4 w-5/6 rounded bg-gray-200"></div>
      <div class="h-48 w-full rounded bg-gray-200 mt-4"></div>
    </div>
  `;

  try {
    const res = await fetch(`/api/training?slug=${encodeURIComponent(slug)}&base=${chaptersBase}`, { headers: { 'Accept': 'application/json' }});
    if (!res.ok) throw new Error();
    const data = await res.json();

    contentTitle.textContent = titleBySlug[slug] || 'Матеріал';
    contentBody.innerHTML = data.content || '<p>Порожньо.</p>';
    mainArticle.classList.remove('max-w-2xl','max-w-3xl','max-w-4xl','max-w-5xl');
    mainArticle.classList.add(widthBySlug[slug]);

    const i = order.findIndex(x => x.slug === slug);
    const next = i >= 0 ? order[i + 1] : null;
    if (next) {
      const wrap = document.createElement('div');
      wrap.className = 'mt-8 flex justify-center';
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'inline-flex items-center rounded-lg bg-[#597f85] px-4 py-2 text-white hover:bg-[#597f85]/90 focus:outline-none focus:ring-2 focus:ring-[#597f85]/50';
      btn.textContent = `Перейти до “${next.title}”`;
      btn.addEventListener('click', () => {
        setActive(next.slug);
        updateUrl(next.slug);
        loadLessonBySlug(next.slug);
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
      wrap.appendChild(btn);
      contentBody.appendChild(wrap);
        renderFAQ();
        window.renderCertificates?.();
    }
  } catch {
    contentTitle.textContent = titleBySlug[slug] || 'Помилка завантаження';
    contentBody.innerHTML = `<p>Не вдалося завантажити матеріал.</p>`;
  }
}

// ---- Закрити меню і лише потім зробити дію (для коректного скролу) ----
function closeMenuThen(cb) {
  if (!mobileMenu || mobileMenu.classList.contains('hidden')) {
    cb();
    return;
  }
  const onEnd = () => {
    panel?.removeEventListener('transitionend', onEnd);
    cb();
  };
  panel?.addEventListener('transitionend', onEnd);
  setMobileMenu(false);
}

// ---- Клік по пункту меню ----
function handleNavClick(e) {
  const a = e.target.closest('a[data-slug]');
  if (!a) return;
  e.preventDefault();
  const slug = a.getAttribute('data-slug');

  closeMenuThen(() => {
    setActive(slug);
    updateUrl(slug);
    loadLessonBySlug(slug);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
navDesktop?.addEventListener('click', handleNavClick);
navMobile?.addEventListener('click', handleNavClick);

window.addEventListener('popstate', () => {
  const page = window.location.pathname.split('/').filter(Boolean)[1] ?? order[0].slug;
  setActive(page);
  loadLessonBySlug(page);
});

// ---- Автовідкриття першого елемента при старті ----
if (order.length) {
  const page = window.location.pathname.split('/').filter(Boolean)[1] ?? order[0].slug;
  setActive(page);
  loadLessonBySlug(page);
}
