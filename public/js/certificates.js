(() => {
  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];
  const escapeHtml = s => String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  const formatDate = iso => { if (!iso) return ''; const d = new Date(iso); if (isNaN(d)) return iso; return `${String(d.getDate()).padStart(2,'0')}.${String(d.getMonth()+1).padStart(2,'0')}.${d.getFullYear()}`; };
  const ectsToHours = x => (isFinite(+x) ? Math.round(+x * 30) : '');
  const debounce = (fn, t=350) => { let id; return (...a) => { clearTimeout(id); id=setTimeout(()=>fn(...a),t); }; };

  window.renderCertificates = function renderCertificates() {
    const API = '/api/certs';
    const search = $('#search'), tbody = $('#tbody'), status = $('#status'), pager = $('#pager'), wrap = $('#tableWrap'), article = $('#mainArticle');
    if (!search || !tbody || !status || !pager || !wrap) return;
    if (wrap.dataset.certInit) return;
    wrap.dataset.certInit = '1';

    const state = { p: 1, per_page: 10, s: '', aborter: null };

    function renderRows(items) {
      if (!items || !items.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-10 text-center text-gray-600">Сертифікатів за цими даними не знайдено</td></tr>`;
        return;
      }
      tbody.innerHTML = items.map(it => {
        const c = it.course || {};
        const courseCell = c.url
          ? `<a href="${escapeHtml(c.url)}" target="_blank" rel="noopener" class="text-[#698b91] hover:underline">${c.title ?? ''}</a>`
          : escapeHtml(c.title ?? '');
        const downloadBtn = `
          <a href="/certs/pdf/${escapeHtml(it.id)}"
             target="_blank" rel="noopener"
             class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-2 py-1 hover:bg-gray-50"
             aria-label="Завантажити">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M12 3a1 1 0 0 1 1 1v8.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-4.005 4.005a1.25 1.25 0 0 1-1.414 0L7.283 11.707a1 1 0 0 1 1.414-1.414L11 12.586V4a1 1 0 0 1 1-1z"/>
              <path d="M5 15a1 1 0 0 1 1 1v2h12v-2a1 1 0 1 1 2 0v3a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1z"/>
            </svg>
          </a>`;

        return `
          <tr class="border-b last:border-0 hover:bg-gray-50">
            <td class="px-4 py-3">${escapeHtml(it.name)}</td>
            <td class="px-4 py-3">${courseCell}</td>
            <td class="px-4 py-3">${ectsToHours(c.ects)} (${c.ects} ЄКТС)</td>
            <td class="px-4 py-3">${escapeHtml(it.id)}</td>
            <td class="px-4 py-3">
              <div class="flex items-center justify-between gap-3">
                <span class="whitespace-nowrap">${formatDate(it.issued_at)}</span>
                ${downloadBtn}
              </div>
            </td>
          </tr>`;
      }).join('');
    }

    function renderPager(page, pages) {
      if ((pages|0) <= 1) { pager.innerHTML = ''; pager.classList.add('hidden'); return; }
      pager.classList.remove('hidden');
      const btn = (label, p, active=false, disabled=false) => `
        <button data-page="${p}" ${disabled ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg border text-sm ${disabled ? '!cursor-default' : 'cursor-pointer'}
          ${active ? 'bg-[#698b91] text-white border-[#698b91]' : 'bg-white border-gray-300 text-gray-800 hover:bg-gray-50'}
          ${disabled ? 'opacity-50 cursor-not-allowed' : ''}">${label}</button>`;
      let html = '';
      html += btn('«', 1, false, page === 1);
      html += btn('‹', Math.max(1, page - 1), false, page === 1);
      const start = Math.max(1, page - 2), end = Math.min(pages, page + 2);
      for (let p = start; p <= end; p++) html += btn(p, p, p === page, false);
      html += btn('›', Math.min(pages, page + 1), false, page === pages);
      html += btn('»', pages, false, page === pages);
      pager.innerHTML = html;
    }

    async function fetchList() {
      const qs = new URLSearchParams({ p: String(state.p), per_page: String(state.per_page) });
      if (state.s) qs.set('s', state.s);
      try { state.aborter?.abort(); } catch {}
      state.aborter = ('AbortController' in window) ? new AbortController() : null;
      const res = await fetch(`${API}?${qs.toString()}`, { signal: state.aborter?.signal, headers: { Accept: 'application/json' } });
      if (!res.ok) throw new Error();
      return res.json();
    }

    function showStatus(msg) { status.textContent = msg; status.classList.remove('hidden'); }
    function hideStatus() { status.textContent = ''; status.classList.add('hidden'); }

    async function load() {
      try {
        hideStatus();
        const data = await fetchList();
        renderRows(data.items || []);
        renderPager(data.page || 1, data.pages || 1);
      } catch {
        showStatus('Сталася помилка під час завантаження.');
      }
    }

    pager.addEventListener('click', e => {
      const btn = e.target.closest('button[data-page]');
      if (!btn) return;
      const next = Number(btn.getAttribute('data-page'));
      if (!isNaN(next) && next !== state.p) { state.p = next; load(); article.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });

    search.addEventListener('input', debounce(() => {
      state.s = search.value.trim();
      state.p = 1;
      load();
    }));
    
    const url = new URL(location.href);
    const qsS = url.searchParams.get('s');
    if (qsS !== null) {
      const val = qsS.trim();
      search.value = val;
      state.s = val;
      state.p = 1;
      url.searchParams.delete('s');
      const rest = url.searchParams.toString();
      history.replaceState(null, '', `${url.pathname}${rest ? `?${rest}` : ''}${url.hash}`);
    }
    load();
  };
})();