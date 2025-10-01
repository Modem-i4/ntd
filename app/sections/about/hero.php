<section id="hero"
  class="relative isolate h-[560px] w-full overflow-clip
         pt-[calc(var(--header-h)*2)]
         bg-[url(/assets/hero-bg.webp)] bg-cover bg-center bg-no-repeat">

  <!-- м’який градієнт для контрасту тексту -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-black/10 to-black/35 pointer-events-none"></div>

  <!-- ДЕКОР переднього плану (якщо треба) -->
  <!-- <img src="/assets/hero-fg.webp" alt="" aria-hidden="true"
       class="absolute bottom-0 left-1/2 -translate-x-1/2 h-2/3 max-w-none animate-floaty-max opacity-70" /> -->

  <!-- Заголовок -->
  <h1 class="relative z-10 text-center text-white font-ermilov
             text-[28px] leading-tight sm:text-4xl md:text-5xl lg:text-6xl
             drop-shadow-[0_2px_10px_rgba(0,0,0,.35)] px-4">
    <span>Навчальна гра, що захоплює історією</span>
  </h1>

  <!-- Персонажі: спільний контейнер для mobile + desktop -->
  <div class="absolute inset-x-0 bottom-24 md:bottom-0 pointer-events-none select-none" aria-hidden="true">
    <div class="relative w-[90%] mx-auto">
      <!-- Лівий блок -->
      <div class="absolute left-3 bottom-0 flex items-end gap-2 md:left-6 lg:left-10">
        <img src="/assets/chars/Shvarn.webp"  alt=""
            class="h-46 md:h-64 lg:h-72 drop-shadow-[10px_10px_30px_rgba(255,255,255,1)]" />
        <img src="/assets/chars/Danylo.webp"  alt=""
            class="h-50 md:h-72 lg:h-80 drop-shadow-[10px_10px_30px_rgba(255,255,255,1)]" />
      </div>

      <!-- Правий блок -->
      <div class="absolute right-3 bottom-0 flex items-end md:right-6 lg:right-10">
        <img src="/assets/chars/Ostrozky.webp" alt=""
            class="h-46 md:h-72 lg:h-80 drop-shadow-[10px_10px_30px_rgba(255,255,255,1)]" />
        <img src="/assets/chars/Krymets.webp"  alt=""
            class="h-50 md:h-64 lg:h-72 drop-shadow-[10px_10px_30px_rgba(255,255,255,1)]" />
      </div>
    </div>
  </div>


  <!-- CTA-стрічка -->
  <a href="#about"
     class="absolute left-1/2 -translate-x-1/2 bottom-6 md:bottom-10 z-10
            w-[300px] h-20 md:w-[640px] md:h-28
            bg-[url('/assets/banner-bg.webp')] bg-no-repeat bg-center bg-contain
            text-white font-ermilov tracking-wide transition-transform duration-300 hover:scale-105 focus:scale-105">
    <div class="mt-5 md:mt-7 text-center text-xl md:text-3xl select-none">
      Ознайомитися
    </div>
  </a>
</section>
