<?php require __DIR__ . '/../../api/Enum/checkpoints.php'; ?>
<div id="session"
     class="mx-auto max-w-7xl px-2 md:px-4 py-6 md:py-8 text-zinc-900"
     data-join-base="/game"
     data-refresh="20000">

  <section class="rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200">
    <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-zinc-200">
      <div class="flex items-center justify-between px-3 md:px-4 py-3 md:py-4 md:col-span-1">
        <span class="text-zinc-500 me-2">Триває:</span>
        <span id="session-time" class="font-semibold tabular-nums">очікування гравців</span>
      </div>
      <div class="flex items-center justify-between px-3 md:px-4 py-3 md:py-4 md:col-span-2">
        <span class="text-zinc-500">Сценарій</span>
        <span id="session-scenario" class="font-semibold truncate max-w-[70%] text-right">—</span>
      </div>
      <div class="flex items-center justify-between px-3 md:px-4 py-3 md:py-4 md:col-span-1">
        <span class="text-zinc-500">Гравців</span>
        <span id="session-players" class="font-semibold">0</span>
      </div>
    </div>
  </section>

  <section class="mt-6 md:mt-8 grid gap-4 md:gap-6 lg:grid-cols-3">
    <div class="lg:col-span-3 rounded-2xl shadow-sm ring-1 ring-zinc-200 relative overflow-hidden">
        <div aria-hidden="true"
            class="absolute inset-0 bg-[url('/assets/hero-bg.webp')] bg-cover bg-center opacity-20 pointer-events-none select-none"></div>

        <div class="relative p-3 sm:p-4 md:p-5">
            <div class="flex items-stretch justify-between gap-2 sm:gap-4">
                <div class="flex-1 basis-0 grid place-items-center max-w-[38%]">
                <img id="img-left" alt="" loading="lazy" decoding="async"
                    class="w-full h-auto max-h-[60vh] object-contain select-none" />
                </div>

                <div id="center-stack" class="flex-[1.2] basis-0 flex flex-col items-center justify-center text-center px-2 md:px-4">
                <div id="code-line" class="font-semibold text-lg md:text-2xl lg:text-3xl leading-tight tracking-[0.18em] md:tracking-[0.26em]">
                    Код: <span id="code-print" class="font-mono">— — —</span>
                </div>
                <div id="qr-wrap" class="mt-3 md:mt-5 rounded-2xl bg-zinc-50 ring-1 ring-zinc-200 p-2 md:p-3">
                    <div id="qrcode"></div>
                </div>
                <a id="join-link" href="#" target="_blank" rel="noreferrer noopener"
                    class="mt-2 md:mt-3 break-all text-sky-600 hover:underline text-lg md:text-xl lg:text-2xl">—</a>
                </div>

                <div class="flex-1 basis-0 grid place-items-center max-w-[38%]">
                <img id="img-right" alt="" loading="lazy" decoding="async"
                    class="w-full h-auto max-h-[60vh] object-contain select-none" />
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3 grid gap-4 md:gap-6 md:grid-cols-2 items-start">
      <div class="rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200 p-3 sm:p-4 md:p-5">
        <div class="mb-2 md:mb-3 text-sm text-zinc-500">Пройдені етапи</div>
        <div class="overflow-hidden rounded-xl ring-1 ring-zinc-200">
          <table class="w-full text-sm">
            <thead class="bg-zinc-50">
              <tr class="text-left text-zinc-600">
                <th class="px-3 md:px-4 py-3 font-medium">Етап</th>
                <th class="px-3 md:px-4 py-3 font-medium text-right">Досягли</th>
              </tr>
            </thead>
            <tbody id="stages-tbody" class="divide-y divide-zinc-200 bg-white"></tbody>
          </table>
        </div>
      </div>
      <div class="rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200 p-3 sm:p-4 md:p-5">
        <div class="mb-2 md:mb-3 text-sm text-zinc-500">Таблиця лідерів</div>
        <div class="overflow-hidden rounded-xl ring-1 ring-zinc-200">
          <table class="w-full text-sm">
            <thead class="bg-zinc-50">
              <tr class="text-left text-zinc-600">
                <th class="px-3 md:px-4 py-3 font-medium">Гравець</th>
                <th class="px-3 md:px-4 py-3 font-medium text-right">Очки</th>
              </tr>
            </thead>
            <tbody id="leaders-tbody" class="divide-y divide-zinc-200 bg-white"></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
(() => {
  const $ = (s, c=document) => c.querySelector(s);
  const root = $('#session');
  const JOIN_BASE = (root.dataset.joinBase || '/play').replace(/\/+$/,'');
  const REFRESH_MS = parseInt(root.dataset.refresh || '20000', 10);
  const GAME_CODE = (location.pathname.match(/(\d{6})(?:\/)?$/)||[])[1] || '';

  const elTime = $('#session-time');
  const elScenario = $('#session-scenario');
  const elPlayers = $('#session-players');
  const elCode = $('#code-print');
  const elLink = $('#join-link');
  const tStages = $('#stages-tbody');
  const tLeaders = $('#leaders-tbody');
  const imgLeftEl  = $('#img-left');
  const imgRightEl = $('#img-right');
  const qrBox = $('#qrcode');
  const center = $('#center-stack');

  const SCENARIO_TITLES = { danylo:'Король Данило', vitovt:'Коронація Вітовта', orsha:'Битва під Оршею' };
  const SCENERY_IMAGES = {
    danylo:{ left:'/assets/chars/danylo.webp', right:'/assets/chars/shvarn.webp' },
    vitovt:{ left:'/assets/chars/yagello.webp', right:'/assets/chars/vitovt.webp' },
    orsha:{  left:'/assets/chars/krymets.webp',  right:'/assets/chars/ostrozky.webp' }
  };
  const DEFAULT_IMAGES = { left:'/assets/default-left.jpg', right:'/assets/default-right.jpg' };

  const STAGE_TITLES_BY_SCENARIO = <?= Scenario::getJson() ?>;
  let STAGE_TITLES = STAGE_TITLES_BY_SCENARIO._default;

  const prettyFromSlug = (slug) => SCENARIO_TITLES[slug] || (slug ? slug.replace(/[-_]+/g,' ').replace(/^./,c=>c.toUpperCase()) : '—');

  let startedAt = null, ticking = null, lastQrSize = 0, qr;
  const pad = (n) => String(n).padStart(2,'0');
  const setWaiting = () => { elTime.textContent = 'очікування гравців'; };
  const startTicker = () => {
    if (ticking) clearInterval(ticking);
    const tick = () => {
      if (!startedAt) return setWaiting();
      const s = Math.max(0, Math.floor((Date.now() - startedAt.getTime())/1000));
      const hh = Math.floor(s/3600), mm = Math.floor((s%3600)/60), ss = s%60;
      elTime.textContent = `${pad(hh)}:${pad(mm)}:${pad(ss)}`;
    };
    tick();
    ticking = setInterval(tick, 1000);
  };

  const computeQRSize = () => {
    const lh = imgLeftEl?.clientHeight || 0;
    const rh = imgRightEl?.clientHeight || 0;
    const ref = Math.max(lh, rh, 260);
    const desired = Math.min(Math.max(Math.floor(ref * 0.55), 220), 420);
    return desired;
  };

  const renderQR = (text) => {
    const size = computeQRSize();
    if (Math.abs(size - lastQrSize) < 12 && qr) return;
    qrBox.innerHTML = '';
    qr = new QRCode(qrBox, { text, width: size, height: size, correctLevel: QRCode.CorrectLevel.M });
    lastQrSize = size;
  };

  const syncCenterHeight = () => {
    const lh = imgLeftEl?.clientHeight || 0;
    const rh = imgRightEl?.clientHeight || 0;
    const target = Math.max(lh, rh);
    if (target > 0) center.style.minHeight = target + 'px';
  };

  const renderTables = (data) => {
    const counts = (data.stages && data.stages.counts) || {};
    const denom  = Math.max(Number(data.players_count||0), Number((data.stages && data.stages.total_players_with_progress)||0));
    tStages.innerHTML = '';
    const order = Object.keys(STAGE_TITLES);
    order.forEach((key) => {
      const name = STAGE_TITLES[key] || key;
      const num  = Number(counts[key] ?? 0);
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-3 md:px-4 py-3">${name}</td>
        <td class="px-3 md:px-4 py-3 text-right font-semibold">${denom ? `${num} / ${denom}` : num}</td>`;
      tStages.appendChild(tr);
    });
    Object.keys(counts).forEach((key) => {
      if (order.includes(key)) return;
      const name = key;
      const num  = Number(counts[key] ?? 0);
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="px-3 md:px-4 py-3">${name}</td>
        <td class="px-3 md:px-4 py-3 text-right font-semibold">${denom ? `${num} / ${denom}` : num}</td>`;
      tStages.appendChild(tr);
    });

    const leaders = (data.leaders || []).slice().sort((a,b)=> (b.score||0)-(a.score||0));
    tLeaders.innerHTML = '';
    if (!leaders.length) {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td class="px-3 md:px-4 py-4 text-zinc-500" colspan="2">Поки що немає результатів</td>`;
      tLeaders.appendChild(tr);
    } else {
      leaders.forEach((row, i) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="px-3 md:px-4 py-3">
            <span class="inline-grid place-items-center h-7 w-7 rounded-md bg-zinc-100 text-zinc-700 font-semibold mr-2">${i+1}</span>
            Гравець/гравчиня ${i+1}
          </td>
          <td class="px-3 md:px-4 py-3 text-right font-semibold">${Number(row.score||0)}</td>`;
        tLeaders.appendChild(tr);
      });
    }
  };

  const render = (data, slug) => {
    STAGE_TITLES = STAGE_TITLES_BY_SCENARIO[slug] || STAGE_TITLES_BY_SCENARIO._default;
    elScenario.textContent = prettyFromSlug(slug);
    const imgs = SCENERY_IMAGES[slug] || DEFAULT_IMAGES;
    imgLeftEl.src  = imgs.left;
    imgRightEl.src = imgs.right;
    elPlayers.textContent = data.players_count ?? 0;

    if (data.started_at) {
      const d = new Date(String(data.started_at).replace(' ','T'));
      if (!isNaN(d) && (!startedAt || d.getTime() !== startedAt.getTime())) { startedAt = d; startTicker(); }
    } else {
      startedAt = null;
      setWaiting();
      if (!ticking) startTicker();
    }

    const joinHref = `${location.origin}${JOIN_BASE}/${GAME_CODE}`;
    elCode.textContent = String(GAME_CODE).replace(/^(\d{3})(\d{3})$/,'$1 $2');
    elLink.textContent = joinHref; elLink.href = joinHref;

    syncCenterHeight();
    renderQR(joinHref);
    renderTables(data);
  };

  let timer = null, inFlight = null, slugOnce = null;

  const loadSlug = async () => {
    if (slugOnce) return slugOnce;
    slugOnce = fetch(`/api/games/${encodeURIComponent(GAME_CODE)}`, { headers:{ 'Accept':'application/json' }})
      .then(r => r.ok ? r.json() : null)
      .then(j => (j && (j.scenario_slug || j.slug)) || '')
      .catch(() => '');
    return slugOnce;
  };

  const schedule = () => { if (timer) clearTimeout(timer); timer = setTimeout(loadNow, REFRESH_MS); };

  const loadNow = async () => {
    try {
      if (inFlight) inFlight.abort();
      inFlight = new AbortController();
      const [slug, statsRes] = await Promise.all([
        loadSlug(),
        fetch(`/api/games/stats?game_code=${encodeURIComponent(GAME_CODE)}`, { signal: inFlight.signal, headers:{'Accept':'application/json'} })
      ]);
      if (!statsRes.ok) throw new Error(`HTTP ${statsRes.status}`);
      const stats = await statsRes.json();
      render(stats, slug);
    } catch (_) {
    } finally {
      inFlight = null; schedule();
    }
  };

  const reflow = () => {
    syncCenterHeight();
    if (elLink.href) renderQR(elLink.href);
  };

  imgLeftEl.addEventListener('load', reflow, { once:false });
  imgRightEl.addEventListener('load', reflow, { once:false });
  document.addEventListener('visibilitychange', () => { if (!document.hidden) loadNow(); });
  window.addEventListener('pageshow', () => loadNow());
  window.addEventListener('resize', () => reflow());

  if (!/^\d{6}$/.test(GAME_CODE)) { console.warn('Очікуються 6 цифр у URL'); return; }
  loadNow();
})();
</script>
