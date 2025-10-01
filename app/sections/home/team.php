<section id="team" class="relative py-16 max-w-[100rem] mx-auto">
  <h2 class="text-center text-3xl md:text-5xl font-extrabold tracking-tight pb-10">НАША КОМАНДА</h2>
  <div class="md:hidden absolute top-46 flex justify-between w-full mb-6">
    <button id="team-prev" class="h-12 w-12 text-black/80 active:scale-95 flex items-center justify-center">
      <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none">
        <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <button id="team-next" class="h-12 w-12 text-black/80 active:scale-95 flex items-center justify-center">
      <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none">
        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>


  <div id="team-track"
       class="pair-gap flex overflow-x-auto no-scrollbar snap-x snap-mandatory px-4 [scroll-padding-left:1rem]
              md:grid md:grid-cols-7 md:gap-x-10 md:gap-y-16 md:overflow-visible md:px-0">

    <?php
      $team = [
        ["img" => "/assets/team/Vasyl.webp",  "name" => "Василь Чухілевич",  "role" => "Голова ГО, менеджер з розробки"],
        ["img" => "/assets/team/Andrii.webp", "name" => "Андрій Риков",      "role" => "Заступник Голови, історик-методист"],
        ["img" => "/assets/team/Yasya.webp",  "name" => "Ярослава Сидорчук", "role" => "Співзасновниця ГО, асистентка"],
        ["img" => "/assets/team/Sofia.webp",  "name" => "Софія Балагура",    "role" => "Комунікаційна менеджерка"],
        ["img" => "/assets/team/Anya.webp",   "name" => "Анна Коваленко",    "role" => "Дизайнерка-ілюстраторка"],
        ["img" => "/assets/team/Taras.webp",  "name" => "Тарас Хведенчук",   "role" => "Вчитель НУШ, історик"],
      ];

      $card = function($p) {
        ?>
        <article class="shrink-0 snap-start basis-1/2 px-3 md:basis-auto md:px-0 md:col-span-2">
          <div class="mx-auto w-full max-w-64">
            <div class="img-container">
              <div class="mx-auto w-full aspect-square max-w-42 md:max-w-64 rounded-full p-[10px] bg-gradient-to-br from-rose-200 to-emerald-200">
                <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>" class="block h-full w-full rounded-full object-cover">
              </div>
            </div>
            <h3 class="mt-4 text-center text-xl font-extrabold"><?= $p['name'] ?></h3>
            <p class="text-center text-sm text-black/70"><?= $p['role'] ?></p>
          </div>
        </article>
        <?php
      };

      foreach (array_slice($team, 0, 3) as $p) $card($p);
    ?>

    <div class="hidden md:block relative md:col-span-1 md:col-start-7">
      <img src="/assets/decor/wheel.webp" alt="" 
        class="absolute -left-1 bottom-40 w-24 xl:w-30 drop-shadow-md opacity-95 animate-floaty-rotate [animation-delay:2s] hover-scale">
      <img src="/assets/decor/bowl.webp"  alt="" 
        class="absolute left-8 bottom-2 w-20 xl:w-24 drop-shadow-md opacity-95 animate-floaty-rotate [animation-delay:2s] hover-scale">
    </div>

    <div class="hidden md:block relative md:col-span-1 md:col-start-1">
      <img src="/assets/decor/pickaxe.webp" alt="" 
        class="absolute left-0 top-4 w-40 xl:w-48 drop-shadow-md opacity-95 animate-floaty-rotate [animation-delay:2s] hover-scale">
      <img src="/assets/decor/jug.webp"     alt="" 
        class="absolute -right-5 top-40 w-20 xl:w-24 drop-shadow-md opacity-95 animate-floaty-rotate [animation-delay:2s] hover-scale">
    </div>

    <?php foreach (array_slice($team, 3, 3) as $p) $card($p); ?>
  </div>
  </div>
</section>

<script>
(() => {
  const track = document.getElementById('team-track');
  const prev  = document.getElementById('team-prev');
  const next  = document.getElementById('team-next');

  const setNavVis = () => {
    const isMobile = matchMedia('(max-width: 767px)').matches;
    [prev, next].forEach(b => b && (b.style.display = isMobile ? '' : 'none'));
  };
  setNavVis();
  addEventListener('resize', setNavVis);

  const step = () => track.clientWidth;

  prev?.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
  next?.addEventListener('click', () => track.scrollBy({ left:  step(), behavior: 'smooth' }));
})();
</script>
