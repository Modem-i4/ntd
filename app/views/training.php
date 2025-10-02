<?php
// ===== Навігація: slug-и як ключі =====
$categories = [
  'intro' => [
    'title' => 'Завдання',
    'items' => [
      'hello'   => ['title' => 'Познайомимось!'],
      'chars'   => ['title' => 'Персонажі'],
      'quests'   => ['title' => 'Квести'],
    ],
  ],
  'myth' => [
    'title' => 'Міф для розвінчання',
    'items' => [
      'rus' => ['title' => 'Русь Відроджена'],
    ],
  ],
  'encyclopedia' => [
    'title' => 'Енциклопедія для сценарію',
    'items' => [
      'selyany-status'  => ['title' => 'Селянство: статус, устрій, повинності'],
      'mista'    => ['title' => 'Міста та міське самоврядування'],
      'remesla'  => ['title' => 'Ремесла і цехи'],
      'torgivlya'       => ['title' => 'Торгівля'],
      'shlyahta'  => ['title' => 'Шляхта'],
      'dukhov'          => ['title' => 'Духовенство'],
      'opir'            => ['title' => 'Народний опір'],
      'promysly'        => ['title' => 'Сільське господарство та промисли'],
      'terminy'         => ['title' => 'Терміни'], 
    ],
  ],
];
$heroTitle = "Тренінг зі створення сценарію для гри «Нова Традиція»"
?>
<?php require __DIR__ . '/../parts/hero.php'; ?>
<main class="grow">
  <div class="grid grid-cols-1 md:grid-cols-12 gap-0">
    <!-- Ліва панель (Desktop) -->
    <aside class="hidden md:flex md:col-span-4 lg:col-span-3 xl:col-span-3 md:flex-col md:border-r md:border-gray-200 bg-white">
      <!-- Пошук -->
      <div class="sticky top-0 z-10 border-b border-gray-200 bg-white/90 backdrop-blur">
        <label class="sr-only" for="searchInput">Пошук</label>
        <div class="p-3">
          <input id="searchInput" type="search" placeholder="Пошук у навчанні…"
                 class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#597f85]"
                 autocomplete="off" />
        </div>
      </div>

      <!-- Навігація -->
      <nav id="navList" class="flex-1 overflow-y-auto p-2 space-y-4" aria-label="Категорії навчання">
        <?php foreach ($categories as $catSlug => $cat): ?>
          <section class="category-group"
                   data-cat-slug="<?= htmlspecialchars($catSlug) ?>"
                   data-cat-name="<?= htmlspecialchars(mb_strtolower($cat['title'] ?? '', 'UTF-8')) ?>">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-600 px-2">
              <?= htmlspecialchars($cat['title'] ?? '') ?>
            </h3>
            <ul class="mt-1 space-y-1">
              <?php foreach (($cat['items'] ?? []) as $itemSlug => $item): ?>
                <li class="category-item"
                    data-item-title="<?= htmlspecialchars(mb_strtolower($item['title'] ?? '', 'UTF-8')) ?>"
                    data-item-slug="<?= htmlspecialchars($itemSlug) ?>">
                  <a href="/api/training?slug=<?= rawurlencode($itemSlug) ?>"
                     class="block rounded-lg px-2.5 py-2 hover:bg-[#597f85]/10 hover:text-[#597f85] focus:bg-[#597f85]/20 focus:outline-none focus:ring-2 focus:ring-[#597f85]"
                     data-slug="<?= htmlspecialchars($itemSlug) ?>">
                    <span class="truncate"><?= htmlspecialchars($item['title'] ?? '') ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endforeach; ?>
      </nav>
    </aside>

    <!-- Контент (права частина) -->
    <section id="contentArea" role="main" class="md:col-span-8 lg:col-span-9 xl:col-span-9 min-h-[60vh] bg-white">
      <!-- Кнопка відкриття меню (Mobile) -->
      <div class="md:hidden">
        <button id="openMenuBtn"
                class="fixed bottom-0 left-0 right-0 z-40 mx-4 mb-4 rounded-full bg-[#597f85] px-5 py-3 text-white shadow-lg focus:outline-none focus:ring-4 focus:ring-[#597f85]/50"
                aria-controls="mobileMenu" aria-expanded="false">
          Меню тренінгу
        </button>
      </div>

      <article class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8">
        <header>
          <h1 id="contentTitle" class="text-2xl font-bold tracking-tight text-[#678f94]">Познайомимось!</h1>
        </header>
        <div id="contentBody" class="prose max-w-none">
          <?php require __DIR__ . '/../sections/training/hello.php'; ?>
        </div>
      </article>
    </section>

    <!-- Мобільне повноекранне меню -->
  <div id="mobileMenu" class="md:hidden fixed inset-0 z-50 hidden">
    <!-- затемнення -->
    <div data-overlay class="absolute inset-0 bg-black/40 backdrop-blur-sm opacity-0 transition-opacity duration-200 ease-out"></div>

    <!-- панель -->
    <div data-panel class="relative z-10 flex h-full w-full flex-col bg-white translate-y-4 transition-transform duration-200 ease-out">
      <!-- Хедер з пошуком -->
      <div class="border-b border-gray-200 p-3">
        <label class="sr-only" for="searchInputMobile">Пошук</label>
        <input id="searchInputMobile" type="search" placeholder="Пошук у навчанні…"
              class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#597f85]"
              autocomplete="off" />
      </div>
      <!-- Список -->
      <nav id="navListMobile" class="flex-1 overflow-y-auto p-2 space-y-4 pb-24" aria-label="Категорії навчання (мобільне)">
        <?php foreach ($categories as $catSlug => $cat): ?>
          <section class="category-group"
                  data-cat-slug="<?= htmlspecialchars($catSlug) ?>"
                  data-cat-name="<?= htmlspecialchars(mb_strtolower($cat['title'] ?? '', 'UTF-8')) ?>">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-600 px-2">
              <?= htmlspecialchars($cat['title'] ?? '') ?>
            </h3>
            <ul class="mt-1 space-y-1">
              <?php foreach (($cat['items'] ?? []) as $itemSlug => $item): ?>
                <li class="category-item"
                    data-item-title="<?= htmlspecialchars(mb_strtolower($item['title'] ?? '', 'UTF-8')) ?>"
                    data-item-slug="<?= htmlspecialchars($itemSlug) ?>">
                  <a href="/api/training?slug=<?= rawurlencode($itemSlug) ?>"
                    class="block rounded-lg px-2.5 py-2 hover:bg-[#597f85]/10 hover:text-[#597f85] focus:bg-[#597f85]/20 focus:outline-none focus:ring-2 focus:ring-[#597f85]"
                    data-slug="<?= htmlspecialchars($itemSlug) ?>">
                    <span class="truncate"><?= htmlspecialchars($item['title'] ?? '') ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </section>
        <?php endforeach; ?>
      </nav>
    </div>

    <!-- Кнопка ЗАКРИТИ -->
    <button id="closeMenuBtn"
            class="fixed bottom-0 left-0 right-0 z-[60] mx-4 mb-4 rounded-full bg-[#597f85] px-5 py-3 text-white shadow-lg focus:outline-none focus:ring-4 focus:ring-[#597f85]/50">
      Закрити меню
    </button>
  </div>

  <!-- JS -->
  <script type="module" src="/js/training.js"></script>
</main>
