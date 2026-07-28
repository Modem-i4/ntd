<main id="statsApp" class="grow bg-[#f7f5ef] text-[#253536]" data-endpoint="/api/stats/detailed/cached">
  <section class="stats-hero">
    <div class="stats-shell">
      <div class="stats-hero-row">
        <div>
          <p class="stats-eyebrow">Аналітика гри</p>
          <h1 class="stats-title">Статистика</h1>
          <p class="stats-lead">
            Динаміка гравців, уроків, сценаріїв і навчального прогресу за даними ігрових сесій.
          </p>
        </div>

        <div class="stats-period-controls" aria-label="Період групування">
          <button type="button" class="stats-group-btn" data-group="week">Тиждень</button>
          <button type="button" class="stats-group-btn" data-group="month">Місяць</button>
        </div>
      </div>
    </div>
  </section>

  <section class="stats-body">
    <div id="statsStatus" class="rounded-lg border border-[#d8d1bf] bg-white px-4 py-3 text-sm text-[#597f85] mb-4">
      Завантаження статистики...
    </div>

    <div id="statsContent" class="hidden">
      <div class="stats-summary-grid">
        <article class="stats-card">
          <div class="stats-card-label">Гравців</div>
          <div class="stats-card-value" data-summary="uniquePlayers">0</div>
        </article>
        <article class="stats-card">
          <div class="stats-card-label">Уроків</div>
          <div class="stats-card-value" data-summary="lessons">0</div>
        </article>
        <article class="stats-card">
          <div class="stats-card-label">Сесій</div>
          <div class="stats-card-value" data-summary="sessions">0</div>
        </article>
        <article class="stats-card">
          <div class="stats-card-label">Вчителів</div>
          <div class="stats-card-value" data-summary="uniqueTeachers">0</div>
        </article>
        <article class="stats-card">
          <div class="stats-card-label">Гравців на урок</div>
          <div class="stats-card-value" data-indicator="avgPlayersPerLesson">0</div>
        </article>
        <article class="stats-card lg:hidden">
          <div class="stats-card-label">Уроків на вчителя</div>
          <div class="stats-card-value" data-indicator="avgLessonsPerTeacher">0</div>
        </article>
      </div>
      <div class="stats-chart-grid">
        <section class="stats-panel stats-wide">
          <div class="stats-panel-head">
            <div>
              <h2 class="stats-panel-title">Використання</h2>
              <p class="stats-panel-subtitle">Застосування гри у різні періоди та накопичені результати</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
              <div class="stats-toggle" data-toggle-group="usage-value">
                <button type="button" class="stats-toggle-btn" data-usage-value-mode="period">Приріст</button>
                <button type="button" class="stats-toggle-btn" data-usage-value-mode="cumulative">Всього</button>
              </div>
              <div class="stats-toggle" data-toggle-group="usage">
                <button type="button" class="stats-toggle-btn" data-usage-mode="players">Гравці/сесії</button>
                <button type="button" class="stats-toggle-btn" data-usage-mode="teachers">Вчителі/уроки</button>
              </div>
              <div class="stats-legend" data-legend="usage"></div>
            </div>
          </div>
          <div class="stats-chart-wrap">
            <canvas id="usageChart" class="stats-chart" aria-label="Графік використання"></canvas>
          </div>
        </section>

        <section class="stats-panel stats-wide">
          <div class="stats-panel-head">
            <div>
              <h2 class="stats-panel-title">Сценарії в динаміці</h2>
              <p class="stats-panel-subtitle">Кількість сесій за сценаріями</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
              <div class="stats-toggle" data-toggle-group="scenario">
                <button type="button" class="stats-toggle-btn" data-scenario-mode="bar">Стовпчикова</button>
                <button type="button" class="stats-toggle-btn" data-scenario-mode="line">Лінійна</button>
              </div>
              <div class="stats-legend" data-legend="scenarioUsage"></div>
            </div>
          </div>
          <div class="stats-chart-wrap">
            <canvas id="scenarioUsageChart" class="stats-chart" aria-label="Графік сесій за сценаріями"></canvas>
          </div>
        </section>

        <section class="stats-panel stats-wide">
          <div class="stats-panel-head">
            <div>
              <h2 class="stats-panel-title">Час гри за сценаріями</h2>
              <p class="stats-panel-subtitle">Повні сесії: середній час гри та середній час відповідей на тести</p>
            </div>
            <div class="stats-legend" data-legend="scenarioDuration"></div>
          </div>
          <div class="stats-chart-wrap stats-chart-wrap-tall">
            <canvas id="scenarioDurationChart" class="stats-chart" aria-label="Гістограма часу гри за сценаріями"></canvas>
          </div>
        </section>

        <section class="stats-panel stats-wide">
          <div class="stats-panel-head">
            <div>
              <h2 class="stats-panel-title">Кількість ігор за сценаріями</h2>
              <p class="stats-panel-subtitle">Сесії за кожним сценарієм</p>
            </div>
          </div>
          <div id="scenarioSessionCards" class="grid grid-cols-2 gap-3 lg:grid-cols-3"></div>
        </section>

        <section class="stats-panel stats-wide">
          <div class="stats-panel-head">
            <div>
              <h2 class="stats-panel-title">Покращення за сценаріями</h2>
              <p class="stats-panel-subtitle">Різниця між post і pre за категоріями</p>
            </div>
            <div class="stats-legend" data-legend="improvement"></div>
          </div>
          <div class="stats-chart-wrap stats-chart-wrap-tall">
            <canvas id="improvementChart" class="stats-chart" aria-label="Стовпчикова діаграма покращення"></canvas>
          </div>
        </section>

        <section class="stats-panel stats-wide">
          <div class="stats-panel-head">
            <div>
              <h2 class="stats-panel-title">Активність вчителів</h2>
              <p class="stats-panel-subtitle">Нові, активні та накопичені</p>
            </div>
            <div class="stats-legend" data-legend="teacherActivity"></div>
          </div>
          <div class="stats-chart-wrap stats-chart-wrap-tall">
            <canvas id="teacherActivityChart" class="stats-chart" aria-label="Графік активності вчителів"></canvas>
          </div>
        </section>
      </div>

      <section class="stats-panel mt-5">
        <div class="stats-panel-head">
          <div>
            <h2 class="stats-panel-title">Деталізація покращень</h2>
            <p class="stats-panel-subtitle">Питання з найбільшим позитивним зсувом</p>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead class="border-b border-[#e2dccd] text-xs uppercase tracking-wide text-[#597f85]">
              <tr>
                <th class="whitespace-nowrap px-3 py-3 font-semibold">Сценарій</th>
                <th class="whitespace-nowrap px-3 py-3 font-semibold">Категорія</th>
                <th class="px-3 py-3 font-semibold">Питання</th>
                <th class="whitespace-nowrap px-3 py-3 text-right font-semibold">Pre</th>
                <th class="whitespace-nowrap px-3 py-3 text-right font-semibold">Post</th>
                <th class="whitespace-nowrap px-3 py-3 text-right font-semibold">Зміна</th>
                <th class="whitespace-nowrap px-3 py-3 text-right font-semibold">Учнів</th>
              </tr>
            </thead>
            <tbody id="improvementDetailsBody" class="divide-y divide-[#ece6d8]"></tbody>
          </table>
        </div>
      </section>
    </div>
  </section>
  <div id="chartTooltip" class="pointer-events-none fixed z-[80] hidden max-w-xs rounded-lg border border-[#d8d1bf] bg-white px-3 py-2 text-xs leading-5 text-[#253536] shadow-xl"></div>
</main>

<style>
  #statsApp {
    overflow-x: hidden;
  }

  #statsApp *,
  #statsApp *::before,
  #statsApp *::after {
    box-sizing: border-box;
  }

  .stats-hero {
    background: #597f85;
    color: white;
    padding: 9rem 0 2.5rem;
  }

  .stats-shell,
  .stats-body {
    margin-inline: auto;
    max-width: 80rem;
    padding-inline: 1rem;
    width: 100%;
  }

  .stats-body {
    padding-block: 2rem;
  }

  .stats-hero-row {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .stats-hero-row > div {
    min-width: 0;
  }

  .stats-eyebrow {
    color: rgb(255 255 255 / 0.75);
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
  }

  .stats-title {
    font-family: Ermilov, Montserrat, system-ui, sans-serif;
    font-size: clamp(2.25rem, 5vw, 4rem);
    line-height: 1;
    margin-top: 0.85rem;
  }

  .stats-lead {
    color: rgb(255 255 255 / 0.86);
    font-size: clamp(1rem, 1.6vw, 1.15rem);
    line-height: 1.6;
    margin-top: 1rem;
    max-width: 42rem;
    overflow-wrap: break-word;
    width: 100%;
  }

  .stats-period-controls {
    align-items: center;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .stats-group-btn {
    border: 1px solid rgb(255 255 255 / 0.45);
    border-radius: 8px;
    color: white;
    font-weight: 700;
    min-height: 2.5rem;
    padding: 0.5rem 0.9rem;
    transition: background-color 160ms ease, color 160ms ease, border-color 160ms ease;
  }

  .stats-group-btn:hover,
  .stats-group-btn[aria-pressed="true"] {
    background: white;
    border-color: white;
    color: #597f85;
    cursor: pointer;
  }

  .stats-toggle {
    align-items: center;
    background: #f7f5ef;
    border: 1px solid #e2dccd;
    border-radius: 8px;
    display: inline-flex;
    gap: 0.25rem;
    padding: 0.25rem;
  }

  .stats-toggle-btn {
    border-radius: 6px;
    color: #597f85;
    font-size: 0.78rem;
    font-weight: 800;
    min-height: 2rem;
    padding: 0.35rem 0.65rem;
  }

  .stats-toggle-btn[aria-pressed="true"] {
    background: white;
    color: #253536;
    box-shadow: 0 4px 14px rgb(50 45 30 / 0.08);
  }

  .stats-summary-grid {
    display: grid;
    gap: 0.75rem;
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .stats-chart-grid {
    display: grid;
    gap: 1.25rem;
    margin-top: 2rem;
  }

  .stats-card,
  .stats-panel {
    background: white;
    border: 1px solid #e2dccd;
    border-radius: 8px;
    box-shadow: 0 14px 35px rgb(50 45 30 / 0.06);
    min-width: 0;
  }

  .stats-card {
    padding: 1rem;
  }

  @media (min-width: 1024px) {
  .stats-card {
    min-height: 8.5rem;
  }
  }

  .stats-card-label {
    color: #687371;
    font-size: 0.85rem;
    font-weight: 700;
    line-height: 1.2;
  }

  .stats-card-value {
    color: #253536;
    font-family: Ermilov, Montserrat, system-ui, sans-serif;
    font-size: clamp(2rem, 4vw, 3.6rem);
    line-height: 1;
    margin-top: 1.15rem;
  }

  .stats-panel {
    padding: 1rem;
  }

  @media (min-width: 768px) {
    .stats-panel {
      padding: 1.25rem;
    }
  }

  .stats-panel-head {
    align-items: flex-start;
    display: flex;
    flex-wrap: wrap;
    gap: 0.8rem 1.25rem;
    justify-content: space-between;
    margin-bottom: 1rem;
  }

  .stats-panel-title {
    color: #253536;
    font-size: 1.15rem;
    font-weight: 800;
    line-height: 1.2;
  }

  .stats-panel-subtitle {
    color: #687371;
    font-size: 0.88rem;
    line-height: 1.45;
    margin-top: 0.25rem;
  }

  .stats-chart-wrap {
    height: 320px;
    min-height: 320px;
    position: relative;
    width: 100%;
  }

  .stats-chart-wrap-tall {
    height: 380px;
    min-height: 380px;
  }

  .stats-chart {
    display: block;
    height: 100%;
    width: 100%;
  }

  .stats-legend {
    align-items: center;
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem 0.75rem;
    max-width: 100%;
  }

  .stats-legend-item {
    align-items: center;
    color: #4d5a58;
    display: inline-flex;
    font-size: 0.78rem;
    font-weight: 700;
    gap: 0.35rem;
    line-height: 1;
    white-space: nowrap;
  }

  .stats-legend-button {
    border: 1px solid transparent;
    border-radius: 999px;
    cursor: pointer;
    padding: 0.38rem 0.55rem;
    transition: background 0.18s ease, border-color 0.18s ease, opacity 0.18s ease;
  }

  .stats-legend-button:hover,
  .stats-legend-button[aria-pressed="true"] {
    background: white;
    border-color: #e2dccd;
  }

  .stats-legend-button[aria-pressed="false"] {
    opacity: 0.58;
  }

  .stats-legend-swatch {
    border-radius: 999px;
    display: inline-block;
    height: 0.62rem;
    width: 0.62rem;
  }

  @media (max-width: 500px) {
    .stats-shell,
    .stats-body {
      margin-left: 0;
      margin-right: auto;
      max-width: 390px;
    }
  }

  @media (min-width: 640px) {
    .stats-shell,
    .stats-body {
      padding-inline: 1.5rem;
    }

    .stats-summary-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (min-width: 768px) {
    .stats-hero {
      padding-top: 10rem;
    }

    .stats-hero-row {
      align-items: flex-end;
      flex-direction: row;
      justify-content: space-between;
    }
  }

  @media (min-width: 1024px) {
    .stats-shell,
    .stats-body {
      padding-inline: 2rem;
    }

    .stats-body {
      padding-block: 2.5rem;
    }

    .stats-summary-grid {
      grid-template-columns: repeat(5, minmax(0, 1fr));
    }

    .stats-chart-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .stats-wide {
      grid-column: 1 / -1;
    }
  }
</style>

<script>
(() => {
  const app = document.getElementById('statsApp');
  if (!app) return;

  const endpoint = app.dataset.endpoint || '/api/stats/detailed';
  const statusEl = document.getElementById('statsStatus');
  const contentEl = document.getElementById('statsContent');
  const tooltipEl = document.getElementById('chartTooltip');
  const groupButtons = [...document.querySelectorAll('.stats-group-btn')];
  const usageButtons = [...document.querySelectorAll('[data-usage-mode]')];
  const usageValueButtons = [...document.querySelectorAll('[data-usage-value-mode]')];
  const scenarioButtons = [...document.querySelectorAll('[data-scenario-mode]')];
  const numberFormat = new Intl.NumberFormat('uk-UA');
  const decimalFormat = new Intl.NumberFormat('uk-UA', { maximumFractionDigits: 2 });
  const percentFormat = new Intl.NumberFormat('uk-UA', { maximumFractionDigits: 1, style: 'percent' });
  const validGroups = new Set(['week', 'month']);
  const url = new URL(window.location.href);
  const state = {
    group: validGroups.has(url.searchParams.get('group')) ? url.searchParams.get('group') : 'week',
    usageMode: 'players',
    usageValueMode: 'period',
    scenarioMode: 'bar',
    selectedScenarioSlug: null,
    data: null,
  };
  const chartHits = new Map();

  const palette = [
    '#597f85', '#d4573e', '#e8b84e', '#467a55', '#7769a6',
    '#c67044', '#2f4d5a', '#8f5b78', '#6f9492', '#b45d4b',
  ];

  const labels = {
    uniquePlayers: 'Гравці',
    uniquePlayersCumulative: 'Гравці',
    lessons: 'Уроки',
    lessonsCumulative: 'Уроки',
    sessions: 'Сесії',
    sessionsCumulative: 'Сесії',
    uniqueTeachers: 'Вчителі',
    uniqueTeachersCumulative: 'Вчителі',
    activeTeachers: 'Активні',
    newTeachers: 'Нові',
    teachersCumulative: 'Загалом',
    critical: 'Критичне мислення',
    knowledge: 'Громадянські компетенції',
    knowladge: 'Громадянські компетенції',
    narratives: 'Протидія міфам',
    surveys: 'Ініціативність',
  };

  const charts = [
    () => drawUsageChart(),
    () => drawScenarioUsageChart(),
    () => drawStackedCategoryBarChart(
      'scenarioDurationChart',
      state.data?.scenarioDurationStats || [],
      [
        ['gameSeconds', '#597f85', 'Сер. час гри'],
        ['answerSeconds', '#e8b84e', 'Сер. час відповідей'],
      ],
      'scenarioDuration',
      { valueFormatter: formatDuration }
    ),
    () => drawGroupedBarChart(
      'improvementChart',
      state.data?.improvementByScenario?.wide || [],
      state.data?.improvementByScenario?.categories || [],
      'improvement',
      {
        axisFormatter: formatPercentPoints,
        maxValue: 100,
        multiplier: 100,
        signedFormatter: formatSignedPercentPoints,
      }
    ),
    () => drawLineChart('teacherActivityChart', state.data?.teacherActivityTimeline?.rows || [], [
      ['activeTeachers', palette[0]],
      ['newTeachers', palette[1]],
      ['teachersCumulative', palette[2]],
    ], 'teacherActivity', { maxRoundStep: 50 }),
  ];

  function drawUsageChart() {
    const isCumulative = state.usageValueMode === 'cumulative';
    const series = state.usageMode === 'teachers'
      ? [
          [isCumulative ? 'uniqueTeachersCumulative' : 'uniqueTeachers', palette[3]],
          [isCumulative ? 'lessonsCumulative' : 'lessons', palette[1]],
        ]
      : [
          [isCumulative ? 'uniquePlayersCumulative' : 'uniquePlayers', palette[0]],
          [isCumulative ? 'sessionsCumulative' : 'sessions', palette[2]],
        ];

    drawLineChart('usageChart', state.data?.timeline?.rows || [], series, 'usage', { maxRoundStep: 50 });
  }

  function drawScenarioUsageChart() {
    const rows = state.data?.scenarioUsageTimeline?.wideSessions || state.data?.scenarioUsageTimeline?.wideLessons || [];
    const series = state.data?.scenarioUsageTimeline?.series || [];

    if (state.scenarioMode === 'line') {
      const selectedKey = series.includes(state.selectedScenarioSlug)
        ? state.selectedScenarioSlug
        : series[0];
      state.selectedScenarioSlug = selectedKey || null;

      drawLineChart(
        'scenarioUsageChart',
        rows,
        selectedKey
          ? [[selectedKey, palette[series.indexOf(selectedKey) % palette.length], scenarioLabel(selectedKey)]]
          : [],
        'scenarioUsage'
      );
      renderLegend(
        'scenarioUsage',
        series.map((key, index) => ({
          active: key === selectedKey,
          color: palette[index % palette.length],
          key,
          label: scenarioLabel(key),
        })),
        {
          onSelect: (key) => {
            state.selectedScenarioSlug = key;
            renderCharts();
          },
        }
      );
      return;
    }

    state.selectedScenarioSlug = null;
    drawStackedBarChart(
      'scenarioUsageChart',
      rows,
      series,
      'scenarioUsage'
    );
  }

  groupButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const nextGroup = button.dataset.group;
      if (!validGroups.has(nextGroup) || nextGroup === state.group) return;
      state.group = nextGroup;
      const nextUrl = new URL(window.location.href);
      nextUrl.searchParams.set('group', nextGroup);
      history.replaceState(null, '', nextUrl.toString());
      loadStats();
    });
  });

  usageButtons.forEach((button) => {
    button.addEventListener('click', () => {
      state.usageMode = button.dataset.usageMode || 'players';
      setToggleButtons();
      renderCharts();
    });
  });

  usageValueButtons.forEach((button) => {
    button.addEventListener('click', () => {
      state.usageValueMode = button.dataset.usageValueMode || 'period';
      setToggleButtons();
      renderCharts();
    });
  });

  usageValueButtons.forEach((button) => {
    button.addEventListener('click', () => {
      state.usageValueMode = button.dataset.usageValueMode || 'period';
      setToggleButtons();
      renderCharts();
    });
  });

  usageValueButtons.forEach((button) => {
    button.addEventListener('click', () => {
      state.usageValueMode = button.dataset.usageValueMode || 'period';
      setToggleButtons();
      renderCharts();
    });
  });

  scenarioButtons.forEach((button) => {
    button.addEventListener('click', () => {
      state.scenarioMode = button.dataset.scenarioMode || 'bar';
      setToggleButtons();
      renderCharts();
    });
  });

  document.querySelectorAll('canvas.stats-chart').forEach((canvas) => {
    canvas.addEventListener('mousemove', handleChartHover);
    canvas.addEventListener('mouseleave', hideTooltip);
  });

  window.addEventListener('resize', debounce(renderCharts, 120));
  loadStats();

  async function loadStats() {
    setGroupButtons();
    setToggleButtons();
    statusEl.classList.remove('hidden');
    statusEl.textContent = 'Завантаження статистики...';

    try {
      const response = await fetch(`${endpoint}?group=${encodeURIComponent(state.group)}`, {
        headers: { Accept: 'application/json' },
      });
      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const data = await response.json();
      if (!data.ok) throw new Error(data.detail || data.error || 'API error');

      state.data = data;
      renderSummary(data.summary || {});
      renderIndicators(data.indicators || {});
      renderScenarioSessionCards(data.scenarioSessionStats || []);
      contentEl.classList.remove('hidden');
      renderCharts();
      renderDetails(data.improvementDetails || data.improvementByScenarioDetails || []);
      statusEl.classList.add('hidden');
    } catch (error) {
      contentEl.classList.add('hidden');
      statusEl.classList.remove('hidden');
      statusEl.textContent = `Не вдалося завантажити статистику: ${error.message}`;
    }
  }

  function setGroupButtons() {
    groupButtons.forEach((button) => {
      button.setAttribute('aria-pressed', button.dataset.group === state.group ? 'true' : 'false');
    });
  }

  function setToggleButtons() {
    usageButtons.forEach((button) => {
      button.setAttribute('aria-pressed', button.dataset.usageMode === state.usageMode ? 'true' : 'false');
    });
    usageValueButtons.forEach((button) => {
      button.setAttribute('aria-pressed', button.dataset.usageValueMode === state.usageValueMode ? 'true' : 'false');
    });
    usageValueButtons.forEach((button) => {
      button.setAttribute('aria-pressed', button.dataset.usageValueMode === state.usageValueMode ? 'true' : 'false');
    });
    usageValueButtons.forEach((button) => {
      button.setAttribute('aria-pressed', button.dataset.usageValueMode === state.usageValueMode ? 'true' : 'false');
    });
    scenarioButtons.forEach((button) => {
      button.setAttribute('aria-pressed', button.dataset.scenarioMode === state.scenarioMode ? 'true' : 'false');
    });
  }

  function renderSummary(summary) {
    document.querySelectorAll('[data-summary]').forEach((el) => {
      el.textContent = numberFormat.format(Number(summary[el.dataset.summary] || 0));
    });
  }

  function renderIndicators(indicators) {
    document.querySelectorAll('[data-indicator]').forEach((el) => {
      el.textContent = formatDecimal(indicators[el.dataset.indicator] || 0);
    });
  }

  function renderScenarioSessionCards(rows) {
    const container = document.getElementById('scenarioSessionCards');
    if (!container) return;

    container.innerHTML = rows.map((row) => `
      <article class="stats-card">
        <div class="stats-card-label">${escapeHtml(scenarioTitle(row.scenarioSlug, row))}</div>
        <div class="stats-card-value">${numberFormat.format(Number(row.sessions || 0))}</div>
      </article>
    `).join('');
  }

  function renderCharts() {
    if (!state.data || contentEl.classList.contains('hidden')) return;
    charts.forEach((render) => render());
  }

  function renderDetails(rows) {
    const body = document.getElementById('improvementDetailsBody');
    if (!body) return;

    const bestRows = [...rows]
      .filter((row) => Number.isFinite(Number(row.improvement)))
      .filter((row) => Number(row.usersCount || 0) > 20)
      .sort((a, b) => Number(b.improvement) - Number(a.improvement))
      .slice(0, 12);

    body.innerHTML = bestRows.map((row) => {
      const question = escapeHtml(row.questionText || `Питання ${row.itemNumber || ''}`);
      return `
        <tr class="hover:bg-[#f7f5ef]">
          <td class="whitespace-nowrap px-3 py-3 font-semibold">${escapeHtml(row.scenarioName || scenarioLabel(row.scenarioSlug) || '')}</td>
          <td class="whitespace-nowrap px-3 py-3">${escapeHtml(metricLabel(row.category))}</td>
          <td class="min-w-[20rem] px-3 py-3">${question}</td>
          <td class="whitespace-nowrap px-3 py-3 text-right">${formatPercentCoefficient(row.pre)}</td>
          <td class="whitespace-nowrap px-3 py-3 text-right">${formatPercentCoefficient(row.post)}</td>
          <td class="whitespace-nowrap px-3 py-3 text-right font-semibold">${formatSignedPercentCoefficient(row.improvement)}</td>
          <td class="whitespace-nowrap px-3 py-3 text-right">${numberFormat.format(Number(row.usersCount || 0))}</td>
        </tr>
      `;
    }).join('') || `
      <tr>
        <td colspan="7" class="px-3 py-6 text-center text-[#687371]">Немає деталізованих даних для питань, які пройшло понад 20 учнів</td>
      </tr>
    `;
  }

  function drawLineChart(canvasId, rows, series, legendName, options = {}) {
    const canvas = document.getElementById(canvasId);
    const surface = prepareCanvas(canvas);
    if (!surface) return;

    const { ctx, width, height } = surface;
    const plot = { left: 54, right: 16, top: 18, bottom: 52 };
    const plotWidth = Math.max(1, width - plot.left - plot.right);
    const plotHeight = Math.max(1, height - plot.top - plot.bottom);
    const values = rows.flatMap((row) => series.map(([key]) => Number(row[key] || 0)));
    const rawMaxValue = Math.max(1, ...values);
    const maxValue = Number.isFinite(Number(options.maxRoundStep))
      ? Math.ceil(rawMaxValue / Number(options.maxRoundStep)) * Number(options.maxRoundStep)
      : niceMax(rawMaxValue);
    const hits = [];

    drawGrid(ctx, plot, width, height, 0, maxValue);
    renderLegend(legendName, series.map(([key, color, label]) => [label || metricLabel(key), color]));

    if (!rows.length) {
      chartHits.set(canvasId, []);
      drawEmpty(ctx, width, height);
      return;
    }

    rows.forEach((row, index) => {
      const x = plot.left + (rows.length === 1 ? plotWidth / 2 : (index / (rows.length - 1)) * plotWidth);
      hits.push({
        type: 'line',
        x,
        guideX: x,
        radius: Math.max(16, plotWidth / Math.max(1, rows.length) / 2),
        points: series.map(([key]) => ({
          y: plot.top + plotHeight - (Number(row[key] || 0) / maxValue) * plotHeight,
        })),
        html: tooltipHtml(row.periodLabel, series.map(([key, color, label]) => ({
          label: label || metricLabel(key),
          value: Number(row[key] || 0),
          color,
        }))),
      });
    });

    series.forEach(([key, color]) => {
      ctx.beginPath();
      rows.forEach((row, index) => {
        const x = plot.left + (rows.length === 1 ? plotWidth / 2 : (index / (rows.length - 1)) * plotWidth);
        const y = plot.top + plotHeight - (Number(row[key] || 0) / maxValue) * plotHeight;
        if (index === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
      });
      ctx.strokeStyle = color;
      ctx.lineWidth = 2.5;
      ctx.lineJoin = 'round';
      ctx.lineCap = 'round';
      ctx.stroke();

      rows.forEach((row, index) => {
        if (rows.length > 28 && index % 2 !== 0) return;
        const x = plot.left + (rows.length === 1 ? plotWidth / 2 : (index / (rows.length - 1)) * plotWidth);
        const y = plot.top + plotHeight - (Number(row[key] || 0) / maxValue) * plotHeight;
        ctx.beginPath();
        ctx.arc(x, y, 3, 0, Math.PI * 2);
        ctx.fillStyle = color;
        ctx.fill();
      });
    });

    chartHits.set(canvasId, hits);
    drawBarXAxisLabels(ctx, rows, plot, plotWidth, height);
  }

  function drawStackedBarChart(canvasId, rows, series, legendName) {
    const canvas = document.getElementById(canvasId);
    const surface = prepareCanvas(canvas);
    if (!surface) return;

    const { ctx, width, height } = surface;
    const plot = { left: 54, right: 16, top: 18, bottom: 52 };
    const plotWidth = Math.max(1, width - plot.left - plot.right);
    const plotHeight = Math.max(1, height - plot.top - plot.bottom);
    const maxValue = niceMax(Math.max(1, ...rows.map((row) => series.reduce((sum, key) => sum + Number(row[key] || 0), 0))));
    const hits = [];

    drawGrid(ctx, plot, width, height, 0, maxValue);
    renderLegend(legendName, series.map((key, index) => [scenarioLabel(key), palette[index % palette.length]]));

    if (!rows.length || !series.length) {
      chartHits.set(canvasId, []);
      drawEmpty(ctx, width, height);
      return;
    }

    const slot = plotWidth / rows.length;
    const barWidth = Math.min(34, Math.max(8, slot * 0.68));

    rows.forEach((row, rowIndex) => {
      const x = plot.left + rowIndex * slot + (slot - barWidth) / 2;
      let yCursor = plot.top + plotHeight;
      const values = [];

      series.forEach((key, index) => {
        const value = Number(row[key] || 0);
        values.push({
          label: scenarioLabel(key),
          value,
          color: palette[index % palette.length],
        });
        if (value <= 0) return;

        const barHeight = (value / maxValue) * plotHeight;
        yCursor -= barHeight;
        ctx.fillStyle = palette[index % palette.length];
        ctx.fillRect(x, yCursor, barWidth, barHeight);
      });

      hits.push({
        type: 'rect',
        x,
        y: plot.top,
        width: barWidth,
        height: plotHeight,
        guideX: x + barWidth / 2,
        html: tooltipHtml(row.periodLabel, [
          ...values.filter((item) => item.value > 0),
          { label: 'Разом', value: values.reduce((sum, item) => sum + item.value, 0), color: '#253536' },
        ]),
      });
    });

    chartHits.set(canvasId, hits);
    drawXAxisLabels(ctx, rows, plot, plotWidth, height);
  }

  function drawStackedCategoryBarChart(canvasId, rows, series, legendName, options = {}) {
    const canvas = document.getElementById(canvasId);
    const surface = prepareCanvas(canvas);
    if (!surface) return;

    const { ctx, width, height } = surface;
    const plot = { left: 54, right: 16, top: 18, bottom: 68 };
    const plotWidth = Math.max(1, width - plot.left - plot.right);
    const plotHeight = Math.max(1, height - plot.top - plot.bottom);
    const maxValue = niceMax(Math.max(1, ...rows.map((row) => series.reduce((sum, [key]) => sum + Number(row[key] || 0), 0))));
    const hits = [];

    drawGrid(ctx, plot, width, height, 0, maxValue);
    renderLegend(legendName, series.map(([, color, label]) => [label, color]));

    if (!rows.length || !series.length) {
      chartHits.set(canvasId, []);
      drawEmpty(ctx, width, height);
      return;
    }

    const slot = plotWidth / rows.length;
    const barWidth = Math.min(46, Math.max(14, slot * 0.62));

    rows.forEach((row, rowIndex) => {
      const x = plot.left + rowIndex * slot + (slot - barWidth) / 2;
      let yCursor = plot.top + plotHeight;
      const values = [];

      series.forEach(([key, color, label]) => {
        const value = Number(row[key] || 0);
        values.push({
          label,
          value,
          color,
          formatter: options.valueFormatter,
        });
        if (value <= 0) return;

        const barHeight = (value / maxValue) * plotHeight;
        yCursor -= barHeight;
        ctx.fillStyle = color;
        ctx.fillRect(x, yCursor, barWidth, barHeight);
      });

      hits.push({
        type: 'rect',
        x,
        y: plot.top,
        width: barWidth,
        height: plotHeight,
        guideX: x + barWidth / 2,
        html: tooltipHtml(row.scenarioName || scenarioLabel(row.scenarioSlug), [
          ...values,
          {
            label: 'Разом',
            value: values.reduce((sum, item) => sum + item.value, 0),
            color: '#253536',
            formatter: options.valueFormatter,
          },
        ]),
      });
    });

    chartHits.set(canvasId, hits);
    drawBarXAxisLabels(ctx, rows, plot, plotWidth, height, 'scenarioName');
  }

  function drawGroupedBarChart(canvasId, rows, series, legendName, options = {}) {
    const canvas = document.getElementById(canvasId);
    const surface = prepareCanvas(canvas);
    if (!surface) return;

    const { ctx, width, height } = surface;
    const plot = { left: 54, right: 16, top: 18, bottom: 68 };
    const plotWidth = Math.max(1, width - plot.left - plot.right);
    const plotHeight = Math.max(1, height - plot.top - plot.bottom);
    const multiplier = Number(options.multiplier || 1);
    const values = rows.flatMap((row) => series
      .map((key) => Number(row[key]) * multiplier)
      .filter(Number.isFinite));
    const minValue = Math.min(0, ...values);
    const maxValue = Math.max(1, ...values);
    const range = niceRange(minValue, maxValue);
    if (Number.isFinite(Number(options.maxValue))) {
      range.max = Number(options.maxValue);
    }
    const zeroY = valueToY(0, range.min, range.max, plot, plotHeight);
    const hits = [];

    drawGrid(ctx, plot, width, height, range.min, range.max, options.axisFormatter);
    renderLegend(legendName, series.map((key, index) => [metricLabel(key), palette[index % palette.length]]));

    if (!rows.length || !series.length) {
      chartHits.set(canvasId, []);
      drawEmpty(ctx, width, height);
      return;
    }

    ctx.strokeStyle = '#8d968f';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(plot.left, zeroY);
    ctx.lineTo(width - plot.right, zeroY);
    ctx.stroke();

    const slot = plotWidth / rows.length;
    const groupWidth = slot * 0.74;
    const barWidth = Math.max(5, Math.min(18, groupWidth / series.length));

    rows.forEach((row, rowIndex) => {
      const groupX = plot.left + rowIndex * slot + (slot - groupWidth) / 2;
      const tooltipItems = series
        .map((metricKey, metricIndex) => {
          const metricValue = Number(row[metricKey]);
          if (!Number.isFinite(metricValue)) return null;

          return {
            label: metricLabel(metricKey),
            value: metricValue * multiplier,
            color: palette[metricIndex % palette.length],
            signed: true,
            formatter: options.signedFormatter,
          };
        })
        .filter(Boolean);
      const averageImprovement = Number(row.averageImprovement);
      if (Number.isFinite(averageImprovement)) {
        tooltipItems.push({
          label: 'Середнє',
          value: averageImprovement * multiplier,
          color: '#253536',
          signed: true,
          formatter: options.signedFormatter,
        });
      }

      series.forEach((key, index) => {
        const rawValue = Number(row[key]);
        if (!Number.isFinite(rawValue)) return;
        const value = rawValue * multiplier;

        const x = groupX + index * barWidth;
        const displayValue = clamp(value, range.min, range.max);
        const y = valueToY(displayValue, range.min, range.max, plot, plotHeight);
        const top = Math.min(y, zeroY);
        const h = Math.max(1, Math.abs(zeroY - y));
        ctx.fillStyle = palette[index % palette.length];
        ctx.fillRect(x, top, Math.max(3, barWidth - 1), h);
        hits.push({
          type: 'rect',
          x,
          y: plot.top,
          width: Math.max(3, barWidth - 1),
          height: plotHeight,
          guideX: x + Math.max(3, barWidth - 1) / 2,
          html: tooltipHtml(row.scenarioName || scenarioLabel(row.scenarioSlug), tooltipItems),
        });
      });
    });

    chartHits.set(canvasId, hits);
    drawBarXAxisLabels(ctx, rows, plot, plotWidth, height, 'scenarioName');
  }

  function prepareCanvas(canvas) {
    if (!canvas) return null;

    const rect = canvas.getBoundingClientRect();
    if (rect.width < 10 || rect.height < 10) return null;

    const ratio = window.devicePixelRatio || 1;
    canvas.width = Math.floor(rect.width * ratio);
    canvas.height = Math.floor(rect.height * ratio);

    const ctx = canvas.getContext('2d');
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
    ctx.clearRect(0, 0, rect.width, rect.height);

    return { ctx, width: rect.width, height: rect.height };
  }

  function drawGrid(ctx, plot, width, height, minValue, maxValue, valueFormatter = formatAxis) {
    const steps = 4;
    const plotHeight = height - plot.top - plot.bottom;
    const plotWidth = width - plot.left - plot.right;

    ctx.strokeStyle = '#ece6d8';
    ctx.fillStyle = '#687371';
    ctx.lineWidth = 1;
    ctx.font = '12px Montserrat, system-ui, sans-serif';
    ctx.textAlign = 'right';
    ctx.textBaseline = 'middle';

    for (let i = 0; i <= steps; i += 1) {
      const ratio = i / steps;
      const y = plot.top + plotHeight * ratio;
      const value = maxValue - (maxValue - minValue) * ratio;

      ctx.beginPath();
      ctx.moveTo(plot.left, y);
      ctx.lineTo(plot.left + plotWidth, y);
      ctx.stroke();
      ctx.fillText(valueFormatter(value), plot.left - 10, y);
    }
  }

  function drawXAxisLabels(ctx, rows, plot, plotWidth, height, key = 'periodLabel') {
    const maxLabels = Math.max(2, Math.floor(plotWidth / 78));
    const step = Math.max(1, Math.ceil(rows.length / maxLabels));

    ctx.fillStyle = '#687371';
    ctx.font = '11px Montserrat, system-ui, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'top';

    rows.forEach((row, index) => {
      if (index % step !== 0 && index !== rows.length - 1) return;

      const x = plot.left + (rows.length === 1 ? plotWidth / 2 : (index / (rows.length - 1)) * plotWidth);
      const label = String(row[key] || '');
      ctx.save();
      ctx.translate(x, height - plot.bottom + 18);
      if (rows.length > 8) ctx.rotate(-Math.PI / 7);
      ctx.fillText(label, 0, 0);
      ctx.restore();
    });
  }

  function drawBarXAxisLabels(ctx, rows, plot, plotWidth, height, key = 'periodLabel') {
    const maxLabels = Math.max(2, Math.floor(plotWidth / 78));
    const step = Math.max(1, Math.ceil(rows.length / maxLabels));
    const slot = plotWidth / Math.max(1, rows.length);

    ctx.fillStyle = '#687371';
    ctx.font = '11px Montserrat, system-ui, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'top';

    rows.forEach((row, index) => {
      if (index % step !== 0 && index !== rows.length - 1) return;

      const x = plot.left + index * slot + slot / 2;
      const label = String(row[key] || '');
      ctx.save();
      ctx.translate(x, height - plot.bottom + 18);
      if (rows.length > 5) ctx.rotate(-Math.PI / 7);
      ctx.fillText(label, 0, 0);
      ctx.restore();
    });
  }

  function drawEmpty(ctx, width, height) {
    ctx.fillStyle = '#687371';
    ctx.font = '700 14px Montserrat, system-ui, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('Немає даних', width / 2, height / 2);
  }

  function renderLegend(name, items, options = {}) {
    const target = document.querySelector(`[data-legend="${name}"]`);
    if (!target) return;

    target.innerHTML = items.map((item) => {
      const normalized = Array.isArray(item)
        ? { label: item[0], color: item[1], key: item[0], active: true }
        : item;
      const content = `
        <span class="stats-legend-swatch" style="background:${normalized.color}"></span>
        ${escapeHtml(normalized.label)}
      `;

      if (typeof options.onSelect === 'function') {
        return `
          <button type="button"
                  class="stats-legend-item stats-legend-button"
                  data-legend-key="${escapeHtml(normalized.key)}"
                  aria-pressed="${normalized.active ? 'true' : 'false'}">
            ${content}
          </button>
        `;
      }

      return `
        <span class="stats-legend-item">
          ${content}
        </span>
      `;
    }).join('');

    if (typeof options.onSelect === 'function') {
      target.querySelectorAll('[data-legend-key]').forEach((button) => {
        button.addEventListener('click', () => options.onSelect(button.dataset.legendKey));
      });
    }
  }

  function handleChartHover(event) {
    const canvas = event.currentTarget;
    const hits = chartHits.get(canvas.id) || [];
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    const hit = findHit(hits, x, y);

    if (!hit) {
      hideTooltip();
      renderCharts();
      return;
    }

    renderCharts();
    drawHoverGuide(canvas, hit);

    tooltipEl.innerHTML = hit.html;
    tooltipEl.classList.remove('hidden');

    const offset = 14;
    const tooltipRect = tooltipEl.getBoundingClientRect();
    let left = event.clientX + offset;
    let top = event.clientY + offset;

    if (left + tooltipRect.width > window.innerWidth - 8) {
      left = event.clientX - tooltipRect.width - offset;
    }
    if (top + tooltipRect.height > window.innerHeight - 8) {
      top = event.clientY - tooltipRect.height - offset;
    }

    tooltipEl.style.left = `${Math.max(8, left)}px`;
    tooltipEl.style.top = `${Math.max(8, top)}px`;
  }

  function findHit(hits, x, y) {
    let nearestLineHit = null;
    let nearestLineDistance = Infinity;

    for (const hit of hits) {
      if (hit.type === 'rect') {
        if (x >= hit.x && x <= hit.x + hit.width && y >= hit.y && y <= hit.y + hit.height) {
          return hit;
        }
        continue;
      }

      const distance = Math.abs(x - hit.x);
      if (distance <= hit.radius && distance < nearestLineDistance) {
        nearestLineHit = hit;
        nearestLineDistance = distance;
      }
    }

    return nearestLineHit;
  }

  function drawHoverGuide(canvas, hit) {
    const ctx = canvas.getContext('2d');
    const rect = canvas.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;
    const x = Number.isFinite(hit.guideX) ? hit.guideX : hit.x;

    if (!ctx || !Number.isFinite(x)) return;

    ctx.save();
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
    ctx.strokeStyle = '#253536';
    ctx.globalAlpha = 0.35;
    ctx.lineWidth = 1;
    ctx.setLineDash([5, 5]);
    ctx.beginPath();
    ctx.moveTo(x, 0);
    ctx.lineTo(x, rect.height);
    ctx.stroke();
    ctx.restore();
  }

  function hideTooltip() {
    if (!tooltipEl) return;
    tooltipEl.classList.add('hidden');
  }

  function tooltipHtml(title, items) {
    const totalItems = items.filter((item) => item.label === 'Разом');
    const sortedItems = items
      .filter((item) => item.label !== 'Разом')
      .sort((a, b) => Number(b.value || 0) - Number(a.value || 0));

    const rows = [...sortedItems, ...totalItems].map((item) => `
      <div class="mt-1 flex items-center justify-between gap-4">
        <span class="inline-flex items-center gap-1.5">
          <span class="inline-block h-2 w-2 rounded-full" style="background:${item.color}"></span>
          ${escapeHtml(item.label)}
        </span>
        <strong>${formatTooltipValue(item)}</strong>
      </div>
    `).join('');

    return `
      <div class="font-bold">${escapeHtml(title || '')}</div>
      ${rows}
    `;
  }

  function niceMax(value) {
    if (value <= 4) return Math.ceil(value);
    const exponent = Math.floor(Math.log10(value));
    const fraction = value / Math.pow(10, exponent);
    const niceFraction = fraction <= 2 ? 2 : fraction <= 5 ? 5 : 10;
    return niceFraction * Math.pow(10, exponent);
  }

  function niceRange(minValue, maxValue) {
    const span = maxValue - minValue || 1;
    const padding = span * 0.12;
    return {
      min: Math.min(0, minValue - padding),
      max: maxValue + padding,
    };
  }

  function valueToY(value, minValue, maxValue, plot, plotHeight) {
    return plot.top + plotHeight - ((value - minValue) / (maxValue - minValue || 1)) * plotHeight;
  }

  function metricLabel(key) {
    return labels[key] || key;
  }

  function scenarioLabel(slug) {
    const scenario = state.data?.scenarios?.[slug];
    return scenario?.name || state.data?.scenarioUsageTimeline?.seriesLabels?.[slug] || slug;
  }

  function scenarioTitle(slug, row = {}) {
    const scenario = state.data?.scenarios?.[slug];
    return row.scenarioTitle || scenario?.title || row.scenarioName || scenarioLabel(slug);
  }

  function formatAxis(value) {
    if (Math.abs(value) < 10 && value % 1 !== 0) return decimalFormat.format(value);
    return numberFormat.format(Math.round(value));
  }

  function formatDecimal(value) {
    if (value === null || value === undefined || value === '') return '—';
    return decimalFormat.format(Number(value));
  }

  function formatSigned(value) {
    const number = Number(value);
    if (!Number.isFinite(number)) return '—';
    return `${number > 0 ? '+' : ''}${decimalFormat.format(number)}`;
  }

  function formatPercentPoints(value) {
    const number = Number(value);
    if (!Number.isFinite(number)) return '—';
    return `${numberFormat.format(Math.round(number))}%`;
  }

  function formatSignedPercentPoints(value) {
    const number = Number(value);
    if (!Number.isFinite(number)) return '—';
    return `${number > 0 ? '+' : ''}${numberFormat.format(Math.round(number))}%`;
  }

  function formatPercentCoefficient(value) {
    const number = Number(value);
    if (!Number.isFinite(number)) return '—';
    return formatPercentPoints(number * 100);
  }

  function formatSignedPercentCoefficient(value) {
    const number = Number(value);
    if (!Number.isFinite(number)) return '—';
    return formatSignedPercentPoints(number * 100);
  }

  function formatTooltipValue(item) {
    if (typeof item.formatter === 'function') return item.formatter(item.value);
    return item.signed ? formatSigned(item.value) : formatDecimal(item.value);
  }

  function formatDuration(seconds) {
    const total = Math.max(0, Math.round(Number(seconds || 0)));
    const hours = Math.floor(total / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const restSeconds = total % 60;

    if (hours > 0) return `${hours} год ${minutes} хв`;
    if (minutes > 0) return `${minutes} хв ${restSeconds} с`;
    return `${restSeconds} с`;
  }

  function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
  }

  function debounce(fn, wait) {
    let timer = null;
    return () => {
      window.clearTimeout(timer);
      timer = window.setTimeout(fn, wait);
    };
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }
})();
</script>
