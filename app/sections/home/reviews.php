<?php
$reviews = [
  [
    'name'   => 'Катерина Яворська',
    'from'   => 'Дніпровський ліцей №54',
    'review' => "Колеги! Рекомендую вам «Нову Традицію» – це крутезна освітня онлайн-гра! Історичні теми для учнів 7-10 класів, а ще можливість повторити матеріал для 11-класників перед НМТ! Ми з учнями вже активно граємо і в закладі і в онлайн форматі. Наразі вже 48 моїх учнів «потестили» ігри «Король Данило» та «Конституція Орлика». Чекаємо з дітьми на нові сценарії ☺️",
  ],
  [
    'name'   => 'Оксана Кравченко',
    'from'   => 'Ямпільський ліцей №2, Сумська область',
    'review' => "З 9 класом протестили цікаву гру «Нова Традиція», а саме – «Конституція Пилипа Орлика».\nВикористовувала формат перевернутого класу, щоб було легше вивчати  на уроках основ правознавства нову тему, пов'язану з конституційним правом. Гра легка, цікава, з великою кількістю правових термінів, які ми вивчаємо. Якщо ви в пошуках чогось нового на уроках та в позаурочний час, ця гра – це саме те, що треба.",
  ],
  [
    'name'   => 'Ольга Веріженко',
    'from'   => 'Харківський ліцей №104',
    'review' => "Історія у форматі гри! Учні нашого ліцею долучилися до участі в історичній грі «Нова традиція». Цей захопливий освітній проєкт об’єднав учнів навколо пізнання минулого України у цікавій, інтерактивній формі. Під час гри школярі не лише перевірили свої знання, а й проявили кмітливість, логіку та вміння працювати в команді. Атмосфера змагання і співпраці зробила урок історії по-справжньому натхненним!\n📚 «Нова традиція» — це новий підхід до вивчення історії, шлях до глибшого усвідомлення своїх коренів і гордості за Україну.",
  ],
  [
    'name'   => 'Тетяна Руденко',
    'from'   => 'Розаліївський ліцей, Київська область',
    'review' => "💫 Гра перетворила урок історії на захопливу подорож у минуле, де знання здобуваються через командну роботу, змагання та інтерес до історії рідної землі.\nЩиро дякуємо Команді ГО «Нова Традиція» за співпрацю, можливість долучитися до пілотного тестування гри та за натхнення до сучасного формату навчання",
  ],
  [
    'name'   => 'Марія Бєлова',
    'from'   => 'Піддубцівський ліцей, Волинська область',
    'review' => "Учні 6 класу взяли участь у захопливому уроці про Данила Галицького, під час якого вивчали історію за допомогою гейміфікації — комп’ютерної гри, створеної громадською організацією «Нова традиція».\nДіти з цікавістю проходили квести, розгадували завдання та відкривали для себе постать видатного князя у новому форматі. Перші переможці отримали найвищі бали — це стало справжнім стимулом для гри та ще більше зацікавило учнів до вивчення історії. Висловлюємо щиру подяку за співпрацю, ініціативність та вагомий внесок у популяризацію історії України серед молоді.",
  ],
];

$teacherHero = function(string $wrapClass = '', string $videoClass = '') { ?>
  <div class="<?= $wrapClass ?>">
    <video
      class="<?= $videoClass ?>"
      preload="none"
      playsinline
      autoplay
      muted
      loop
      disablepictureinpicture
      disableremoteplayback
    >
      <source src="/assets/misc/teacher-animation.mp4" type="video/mp4" />
    </video>

    <div class="absolute inset-0">
      <img src="/assets/decor/script.png" alt="" aria-hidden="true"
        class="abs-center left-[20%] top-[27%] w-[14%] drop-shadow-md animate-floaty-rotate [animation-delay:1] hover-scale" />
      <img src="/assets/decor/red-dot.png" alt="" aria-hidden="true"
        class="abs-center left-[58%] top-[10%] w-[9%] animate-floaty-rotate [animation-delay:2s]" />
      <img src="/assets/decor/book.png" alt="" aria-hidden="true"
        class="abs-center left-[84%] top-[40%] w-[16%] drop-shadow-md animate-floaty-rotate [animation-delay:1s] hover-scale" />
      <img src="/assets/decor/blue-dot.png" alt="" aria-hidden="true"
        class="abs-center left-[23%] top-[53%] w-[9%] animate-floaty-rotate [animation-delay:3s]" />
    </div>
  </div>
<?php };
?>

<link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css">

<section id="reviews-section" class="mx-auto max-w-6xl px-4 py-10 md:py-14 overflow-x-hidden md:overflow-visible">
  <div class="grid grid-cols-[1fr_auto] items-center gap-4 md:block">
    <h2 class="min-w-0 text-3xl sm:text-5xl font-black uppercase leading-none text-center z-10">
      ВІДГУКИ
      <span class="hidden md:inline">ВЧИТЕЛІВ_ЬОК</span>
    </h2>

    <?php $teacherHero(
      'relative md:hidden select-none w-36 sm:w-44 aspect-square', 
      'absolute inset-0 w-full h-full object-contain'
      ); ?>
  </div>

  <div class="-mt-8 md:mt-10 grid gap-8 md:grid-cols-2">
    <div class="relative w-full max-w-full min-w-0">
      <button type="button" class="reviews-prev absolute z-10 top-1/2 -translate-y-1/2 -left-1 md:-left-2 lg:-left-9 w-10 h-10 md:w-12 md:h-12 grid place-items-center text-orange-500 hover:text-orange-400 transition" aria-label="Попередній відгук">
        <svg viewBox="0 0 24 24" class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
          <path d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>

      <button type="button" class="reviews-next absolute z-10 top-1/2 -translate-y-1/2 -right-1 md:-right-2 lg:-right-9 w-10 h-10 md:w-12 md:h-12 grid place-items-center text-orange-500 hover:text-orange-400 transition" aria-label="Наступний відгук">
        <svg viewBox="0 0 24 24" class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 5l7 7-7 7"></path>
        </svg>
      </button>

      <div class="swiper reviews-swiper block !w-full !max-w-full min-w-0 pt-5 overflow-hidden">
        <div class="swiper-wrapper pt-5">
          <?php foreach ($reviews as $r): ?>
            <div class="swiper-slide !w-full min-w-0 max-w-full">
              <article class="relative w-full max-w-full min-w-0 left-1">
                <div class="absolute -top-5 -left-1 z-10 text-white font-extrabold text-xl sm:text-2xl px-12 pb-8 pt-4 max-w-full break-words bg-[url('/assets/misc/banner-bg.webp')] bg-no-repeat bg-[length:100%_100%]">
                  <?= htmlspecialchars($r['name'] ?? '') ?>
                </div>

                <div class="w-full max-w-full min-w-0 pt-14 sm:pt-16 pb-10 px-7 sm:px-10 md:px-12 text-black bg-[url('/assets/misc/note-bg.webp')] bg-no-repeat bg-[length:100%_100%]">
                  <h3 class="text-xl sm:text-2xl font-black text-center max-w-full break-words">
                    <?= htmlspecialchars($r['from'] ?? '') ?>
                  </h3>
                  <p class="text-base sm:text-lg leading-relaxed py-2 max-w-full break-words">
                    <?= htmlspecialchars($r['review'] ?? '') ?>
                  </p>
                </div>
              </article>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="reviews-pagination mt-4 flex justify-center"></div>
      </div>
    </div>

    <div class="hidden md:block">
      <?php $teacherHero(
        'relative origin-left -top-3 select-none w-full md:max-w-[620px] aspect-square max-h-[200px] md:max-h-full', 
        'absolute inset-0 h-full mx-auto'
        ); ?>
    </div>
  </div>
</section>

<script src="https://unpkg.com/swiper@11/swiper-bundle.min.js"></script>
<script>
  const root = document.querySelector('#reviews-section');
  const swiperEl = root.querySelector('.reviews-swiper');

  function lockSwiperWidth() {
    const w = Math.round(swiperEl.parentElement.getBoundingClientRect().width);
    swiperEl.style.width = w + 'px';
  }

  lockSwiperWidth();

  const reviewsSwiper = new Swiper('#reviews-section .reviews-swiper', {
    loop: true,
    speed: 500,
    spaceBetween: 0,
    grabCursor: true,
    slidesPerView: 1,
    autoHeight: true,
    keyboard: { enabled: true },
    observer: true,
    observeParents: true,
    navigation: { nextEl: '#reviews-section .reviews-next', prevEl: '#reviews-section .reviews-prev' },
    pagination: {
      el: '#reviews-section .reviews-pagination',
      clickable: true,
      renderBullet: (index, className) => {
        return `<span class="${className} !w-3 !h-3 !mx-1 !bg-orange-500 !opacity-40 [&.swiper-pagination-bullet-active]:!opacity-100"></span>`;
      },
    },
  });

  window.__reviewsSwiper = reviewsSwiper;

  window.addEventListener('resize', () => {
    lockSwiperWidth();
    reviewsSwiper.update();
  });

  window.addEventListener('load', () => {
    lockSwiperWidth();
    reviewsSwiper.update();
  });
</script>
