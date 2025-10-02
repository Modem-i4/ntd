<?php
$scenarios = [
  ['slug'=>'danylo','title'=>'Король Данило','excerpt'=>
'А що було, коли Данило став королем? Династійні шлюби, спроба скликати хрестовий похід, настанови синам?
<br/>
<br/>В один рік Папа Римський видав дві корони: володарю Русі Данилу та першому королю Литви Міндовгу. Ми прибуваємо як шлюбний делегат, щоб дві держави, які щойно прилучилися до кола європейських держав, скріпили союз одруженням!
<br/>Ви допоможете королю розібратися з настроями бояр, порадитесь з братом Васильком і побачите, як дипломатія та родинні союзи зміцнюють державу не менше, ніж меч. Через діалоги й вибори розкривається нелегка доля Данила і Василька та амбіції Лева, історія становлення Королівства Руського і пошуків союзів проти Орди.'],
  ['slug'=>'vitovt','title'=>'Коронація Вітовта','excerpt'=>'
<b>Лист зник!</b>
<br/>Луцьк, 1429 рік. Один з найбільших з\'їздів монархів свого часу кипить у стінах замку.
<br/>Ви — радник Вітовта: у ваших руках обривок листа від Папи Римського і вибір, що здатен змінити долю держави. Вам належить розслідувати змову у замку: у розмовах з Вітовтом, монахом-літописцем, королем Ягайлом та амбітним Свидригайлом; зважити домовленості після Грюнвальда та між двома уніями: Кревською та Городельською.
<br/>На кону — самостійність ВКЛ, права русинів і питання: хто з володарів матиме владу? В сценарії ми дізнаємося історію звільнення Русі Гедиміном, правління Ольгерда, освоєння Вітовтом Причорномор\'я, Грюнвальдську битву та навіть вчинки Свидригайла, що унезалежнювали Русь.
<br/>Фінал — твоє рішення, що дасть політичний сигнал всім гостям коронації.
  '],
  ['slug'=>'orsha','title'=>'Битва під Оршею','excerpt'=>'
<b>«Нас — 15 тисяч, а їх — 40! Що ж буде?»</b>
<br/>Ви прибуваєте до табору як посланець зі столиці ВКЛ, Вільна — просто перед вирішальним боєм під проводом Костянтина Острозького.
<br/>Через розмови з воїнами, місцевим князем і самим гетьманом дізнайтеся про біль поразки на Ведроші, про полон Острозького, про амбіції москви: «захист православ\'я», «третій Рим», «володар всєя Русі» — і як ці амбіції ламає русин, знову очоливши своє військо!
<br/>Ми довідаємося про весь земний шлях Острозького, хроніку московсько-Литовських воєн та взаємин з татарами, спостерігаємо тактичний геній полководця у бою!
<br/>Фінал — тріумф Острозького у Вільні, значення перемоги та обітницю гетьмана стати найбільшим меценатом своїх часів, а ще – підтримку православ\'я, яке московити йшли «захищати».
<br/>Це історія про мужність і розум, героїзм та вірність, що визначили долю всього ВКЛ та самої Русі.
  '],
];
$IMG_BASE    = '/assets/scenarios/';
$METHOD_BASE = '/methods/';
?>
<link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css">

<div
  x-data="{
    idx: 0,
    loadingSlug: null,
    err: null,
    async startGame(slug) {
      if (this.loadingSlug) return;
      this.err = null;
      this.loadingSlug = slug;
      try {
        const fid = await (window.__fidPromise || Promise.resolve(null)).catch(() => null);
        const r = await fetch('/api/games/create', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ scenario_slug: slug, fid })
        });
        const ctype = r.headers.get('content-type') || '';
        let code = '';
        if (ctype.includes('application/json')) {
          const j = await r.json();
          code = j.code || j?.data?.code || j?.game?.code || '';
        } else {
          const t = await r.text();
          const m = t.match(/\b\d{6}\b/);
          code = m ? m[0] : '';
        }
        if (!r.ok || !code) {
          throw new Error('Не вдалося створити сесію');
        }
        window.location.assign(`/play/${code}`);
      } catch (e) {
        this.err = e.message || 'Помилка створення сесії';
      } finally {
        this.loadingSlug = null;
      }
    }
  }"
  class="mx-auto max-w-6xl px-4 py-8 grid gap-8 md:grid-cols-2"
>
  <div class="relative">
    <div class="swiper rounded-2xl overflow-hidden bg-amber-100">
      <div class="swiper-wrapper">
        <?php foreach ($scenarios as $k => $s): ?>
          <div class="swiper-slide grid place-items-center min-h-[360px] md:min-h-[520px]">
            <img
              src="<?= $IMG_BASE . $s['slug'] ?>.webp"
              alt="<?= htmlspecialchars($s['title']) ?>"
              class="max-h-[70vh] w-auto h-auto object-contain select-none"
              decoding="async"
              <?= $k === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy" fetchpriority="low"' ?>
            >
          </div>
        <?php endforeach; ?>
      </div>

      <div class="swiper-button-prev !left-3 !text-black !bg-white/95 !rounded-full !w-12 !h-12 !shadow-lg after:!text-xl"></div>
      <div class="swiper-button-next !right-3 !text-black !bg-white/95 !rounded-full !w-12 !h-12 !shadow-lg after:!text-xl"></div>
      <div class="swiper-pagination !bottom-3"></div>
    </div>

    <div class="mt-3 hidden md:flex gap-3">
      <?php foreach ($scenarios as $i => $s): ?>
        <button type="button"
                @click="window.__scSwiper.slideToLoop(<?= $i ?>)"
                :class="idx===<?= $i ?> ? 'ring-2 ring-orange-500' : 'ring-1 ring-black/10'"
                class="relative w-[88px] h-[108px] rounded-md overflow-hidden bg-white hover:brightness-105 transition">
          <img
            src="<?= $IMG_BASE . $s['slug'] ?>.webp"
            alt="<?= htmlspecialchars($s['title']) ?>"
            class="absolute inset-0 w-full h-full object-cover"
            decoding="async"
            <?= $i === 0 ? 'loading="eager" fetchpriority="low"' : 'loading="lazy" fetchpriority="low"' ?>
          >
        </button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="relative min-h-[320px]">
    <?php foreach ($scenarios as $i => $s): ?>
      <section
        x-show="idx===<?= $i ?> "
        x-transition.opacity.duration.250ms
        class="absolute inset-0 space-y-5"
        style="display:none"
      >
        <span class="inline-block rounded-md bg-orange-600 px-3 py-1 text-sm font-semibold text-white">Сценарії</span>
        <h2 class="text-4xl font-extrabold"><?= htmlspecialchars($s['title']) ?></h2>
        <p class="text-md leading-relaxed max-w-prose"><?= $s['excerpt'] ?></p>

        <template x-if="err">
          <div class="rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm" x-text="err"></div>
        </template>

        <div class="mt-2 grid max-w-sm gap-3">
          <button
            type="button"
            @click="startGame('<?= $s['slug'] ?>')"
            :disabled="loadingSlug==='<?= $s['slug'] ?>'"
            class="rounded-lg bg-orange-400 px-5 py-3 text-center font-bold text-black hover:bg-orange-300 disabled:opacity-60 disabled:cursor-not-allowed transition"
          >
            <span x-show="loadingSlug!=='<?= $s['slug'] ?>'">РОЗПОЧАТИ ГРУ</span>
            <span x-show="loadingSlug==='<?= $s['slug'] ?>' " class="inline-flex items-center gap-2">
              <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4A4 4 0 0 0 8 12H4z"></path>
              </svg>
              Стартує…
            </span>
          </button>

          <a href="<?= $METHOD_BASE . $s['slug'] ?>"
             class="rounded-lg bg-orange-100 px-5 py-3 text-center font-bold text-black hover:bg-orange-200 transition">
            МЕТОДИЧНІ РЕКОМЕНДАЦІЇ
          </a>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
</div>

<script>
  window.__fidPromise = (async () => {
    try {
      const { initializeApp } = await import('https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js');
      const { getInstallations, getId } = await import('https://www.gstatic.com/firebasejs/10.14.1/firebase-installations.js');
      const app = initializeApp({
        apiKey: 'AIzaSyBGr2QcVpt_SOebDS0PUokUhnAzTSyrYWc',
        authDomain: 'nova-tradition.firebaseapp.com',
        projectId: 'nova-tradition',
        appId: '1:656455926039:web:564db0974578c7c1d0f752'
      });
      return await getId(getInstallations(app));
    } catch (e) {
      return null;
    }
  })();
</script>
<script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
<script src="https://unpkg.com/swiper@11/swiper-bundle.min.js"></script>
<script>
  const sw = new Swiper('.swiper', {
    loop: true,
    speed: 500,
    spaceBetween: 0,
    grabCursor: true,
    keyboard: { enabled: true },
    mousewheel: { forceToAxis: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    pagination: { el: '.swiper-pagination', clickable: true },
    on: {
      realIndexChange(s) {
        const root = document.querySelector('[x-data]');
        if (root && root._x_dataStack) root._x_dataStack[0].idx = s.realIndex;
      }
    }
  });
  window.__scSwiper = sw;
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') sw.slidePrev();
    if (e.key === 'ArrowRight') sw.slideNext();
  });
</script>
