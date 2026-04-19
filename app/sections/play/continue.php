<div id="role-game-module"
     class="mx-auto w-full max-w-6xl rounded-b-xl border border-gray-200 shadow-sm p-4 bg-white text-sm leading-5">

  <div id="menu-view" class="space-y-3 md:space-y-0">
    <div class="md:grid md:grid-cols-[auto,1fr] md:items-center md:gap-3">

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <button data-choose="student"
          class="flex items-center justify-between rounded-lg px-4 py-3 bg-orange-100 hover:bg-orange-200 transition font-semibold cursor-pointer">
          <span>Зіграти самостійно</span>
          <svg class="size-4 opacity-70" viewBox="0 0 20 20" fill="currentColor"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 118.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        </button>
        <button data-choose="teacher"
          class="flex items-center justify-between rounded-lg px-4 py-3 bg-orange-100 hover:bg-orange-200 transition font-semibold cursor-pointer">
          <span>Проведені уроки</span>
          <svg class="size-4 opacity-70" viewBox="0 0 20 20" fill="currentColor"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707A1 1 0 118.707 5.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
        </button>
      </div>
    </div>
  </div>

  <div id="backbar" class="hidden mt-2 mb-3">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-3">
      <div class="flex flex-row justify-between w-full md:w-auto gap-3">
        <button id="back-btn"
          class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 bg-orange-100 hover:bg-orange-200 transition cursor-pointer w-max">
          <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path d="M12.707 15.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4A1 1 0 1112.707 7.293L9.414 10l3.293 3.293a1 1 0 010 1.414z"/></svg>
          Назад
        </button>
        <span id="role-pill" class="flex items-center px-2 py-1 rounded text-sm font-semibold text-white bg-[#689296cc] w-max">Роль</span>
      </div>

      <span id="teacher-hint" class="hidden md:text-base min-w-0 md:mx-auto"></span>

      <div id="input-wrap" class="min-w-0 w-full md:w-auto">
        <input id="session-code-input" type="text" placeholder="Код сесії (6 цифр)"
               class="hidden w-full md:w-64 rounded-lg border border-gray-200 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange-300" />
        <div id="code-error" class="hidden mt-1 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded px-2 py-1"></div>
      </div>

      <div id="action-wrap" class="w-full md:w-auto">
        <button id="confirm-code-btn"
                class="hidden w-full md:w-auto px-3 py-1.5 rounded-lg bg-orange-400 hover:bg-orange-300 text-white font-semibold transition">Підтвердити</button>
        <a id="open-game-btn" href="/game" target="_blank" rel="noopener"
           class="hidden w-full md:w-auto px-3 py-1.5 rounded-lg bg-orange-400 hover:bg-orange-300 text-white font-semibold transition">Відкрити гру</a>
      </div>
    </div>
  </div>

  <div id="student-view" class="hidden"></div>

  <div id="teacher-view" class="hidden space-y-3">
    <div class="overflow-x-auto rounded-lg border border-gray-200">
      <table class="min-w-full table-fixed text-[13px]">
        <thead class="bg-orange-100 text-xs uppercase">
          <tr>
            <th class="px-2 py-1.5 text-left w-40">Дата створення</th>
            <th class="px-2 py-1.5 text-left">Тема</th>
            <th class="px-2 py-1.5 text-left w-20">Гравців</th>
            <th class="px-2 py-1.5 text-left w-28">Код</th>
            <th class="px-2 py-1.5 text-left w-28"></th>
          </tr>
        </thead>
        <tbody id="sessions-tbody" class="divide-y divide-gray-200"></tbody>
      </table>
    </div>

    <div class="flex items-center justify-between pt-2">
      <span id="pager-info" class="text-xs text-gray-600">0 / 0</span>
      <div class="flex gap-2">
        <button id="pager-prev" class="px-2 py-1 rounded border border-gray-200 bg-orange-100 hover:bg-orange-200 text-xs transition">Попередня</button>
        <button id="pager-next" class="px-2 py-1 rounded border border-gray-200 bg-orange-100 hover:bg-orange-200 text-xs transition">Наступна</button>
      </div>
    </div>
  </div>
</div>

<script>
(() => {
  const root = document.getElementById('role-game-module');
  const menu = document.getElementById('menu-view');
  const backbar = document.getElementById('backbar');
  const backBtn = document.getElementById('back-btn');
  const rolePill = document.getElementById('role-pill');
  const studentV = document.getElementById('student-view');
  const teacherV = document.getElementById('teacher-view');

  const hint = document.getElementById('teacher-hint');
  const inputWrap = document.getElementById('input-wrap');
  const codeInput = document.getElementById('session-code-input');
  const confirmBtn = document.getElementById('confirm-code-btn');
  const openGameBtn = document.getElementById('open-game-btn');
  const codeErr = document.getElementById('code-error');

  const show = (el, v) => el && el.classList.toggle('hidden', !v);
  const setErr = (msg) => { codeErr.textContent = msg || ''; show(codeErr, !!msg); };
  const clearErr = () => setErr('');

  const toMenu = () => {
    show(menu, true); show(backbar, false);
    show(studentV, false); show(teacherV, false);
    clearErr(); codeInput.value = '';
  };

  const choose = (role) => {
    show(menu, false); show(backbar, true);
    clearErr();
    if (role === 'teacher') {
      rolePill.textContent = 'Вчитель';
      hint.textContent = 'Введіть код для переходу до гри, розпочатої з іншого пристрою';
      show(hint, true);
      show(inputWrap, true); show(codeInput, true);
      show(confirmBtn, true); show(openGameBtn, false);
      show(teacherV, true); show(studentV, false);
      if (!window.__sessionsLoaded) {
        window.__sessionsLoaded = true;
        loadSessions(teacherV?.dataset?.fid || '');
      }
    } else {
      rolePill.textContent = 'Учень';
      hint.textContent = 'Перейдіть прямо до гри. Ви можете зіграти гру і не на уроці, обравши «Гра без коду».';
      show(hint, true);
      show(inputWrap, false); show(codeInput, false);
      show(confirmBtn, false); show(openGameBtn, true);
      show(teacherV, false); show(studentV, false);
    }
  };

  root.querySelectorAll('[data-choose="student"]').forEach(b => b.addEventListener('click', () => choose('student')));
  root.querySelectorAll('[data-choose="teacher"]').forEach(b => b.addEventListener('click', () => choose('teacher')));
  backBtn.addEventListener('click', toMenu);

  // Інлайн перевірка коду
  const goPlay = () => {
    const code = (codeInput.value || '').trim();
    if (!/^\d{6}$/.test(code)) {
      setErr('Неправильний код: потрібно 6 цифр.');
      return;
    }
    clearErr();
    confirmBtn.disabled = true;
    fetch(`/api/sessions/check?code=${encodeURIComponent(code)}`)
      .then(r => r.ok ? r.json() : Promise.reject())
      .then(res => {
        if (res && res.ok) {
          window.location.href = `/play/${encodeURIComponent(code)}`;
        } else if (res && res.error === 'bad_code') {
          setErr('Неправильний код: потрібно 6 цифр.');
        } else if (res && res.error === 'session_not_found') {
          setErr('Сесія не існує. Перевірте код або створіть нову гру.');
        } else {
          setErr('Сталася помилка перевірки. Спробуйте ще раз.');
        }
      })
      .catch(() => setErr('Сталася мережна помилка. Спробуйте ще раз.'))
      .finally(() => { confirmBtn.disabled = false; });
  };

  confirmBtn.addEventListener('click', goPlay);
  codeInput.addEventListener('keydown', e => { if (e.key === 'Enter') goPlay(); });
  codeInput.addEventListener('input', () => { if (codeErr.textContent) clearErr(); });

  // Таблиця і пагінація
  const tbody = document.getElementById('sessions-tbody');
  const pagerInfo = document.getElementById('pager-info');
  const prevBtn = document.getElementById('pager-prev');
  const nextBtn = document.getElementById('pager-next');
  let sessions = [], page = 1, perPage = 5;

  const titleMap = {
    danylo: 'Король Данило',
    vitovt: 'З\'їзд монархів у Луцьку',
    orsha: 'Битва під Оршею',
    orlyk: 'Конституція Орлика', 
    unr:   'Від гетьманату до Директорії',
    khotyn:   'Хотинська битва 1621',
    kyiv: 'Ярмарок в Києві',
    lesya: 'Леся: Слово та свобода',
    plast: 'Вчися діяти: молодіжні організації поч. ХХ ст.',
  };

  const fmtDate = (iso) => {
    const d = new Date(iso);
    return isNaN(d) ? '—' : d.toLocaleString('uk-UA', { dateStyle: 'short', timeStyle: 'short' });
  };

  function renderPage() {
    const total = sessions.length;
    const pages = Math.max(1, Math.ceil(total / perPage));
    page = Math.min(Math.max(1, page), pages);
    const start = (page - 1) * perPage;
    const rows = sessions.slice(start, start + perPage);

    tbody.innerHTML = rows.length ? rows.map(it => {
      const slug = it.title || it.slug || '';
      const title = titleMap[slug] || slug || '—';
      const players = it.players ?? '—';
      const code = it.code ?? '';
      return `
        <tr class="hover:bg-orange-50">
          <td class="px-2 py-1.5 whitespace-nowrap">${fmtDate(it.createdAt)}</td>
          <td class="px-2 py-1.5"><div class="truncate" title="${String(title).replaceAll('"','&quot;')}">${title}</div></td>
          <td class="px-2 py-1.5 whitespace-nowrap">${players}</td>
          <td class="px-2 py-1.5 whitespace-nowrap font-mono">${code}</td>
          <td class="px-2 py-1.5 whitespace-nowrap">
            <a href="/play/${encodeURIComponent(code)}"
               class="px-2 py-1 rounded bg-orange-100 hover:bg-orange-200 text-xs border border-gray-200">Перейти</a>
          </td>
        </tr>`;
    }).join('') : `<tr><td colspan="5" class="px-2 py-3 text-[13px] text-gray-600">Немає ігор.</td></tr>`;

    const pg = Math.ceil(total / perPage);
    pagerInfo.textContent = `${rows.length ? page : 0} / ${rows.length ? pg : 0}`;
    prevBtn.disabled = page <= 1; nextBtn.disabled = page >= pg;
    [prevBtn, nextBtn].forEach(b => b.classList.toggle('opacity-50', b.disabled));
  }

  function loadSessions(fid) {
    if (!fid) { renderPage(); return; }
    fetch(`/api/teacher/sessions?fid=${encodeURIComponent(fid)}`)
      .then(r => r.ok ? r.json() : [])
      .then(data => { sessions = Array.isArray(data) ? data : (data.data || []); renderPage(); })
      .catch(() => { sessions = []; renderPage(); });
  }

  document.getElementById('pager-prev').addEventListener('click', () => { page--; renderPage(); });
  document.getElementById('pager-next').addEventListener('click', () => { page++; renderPage(); });

  window.loadSessions = loadSessions;
  toMenu();
})();
</script>
