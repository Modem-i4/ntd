<?php $year = date('Y'); ?>
<footer class="relative isolate text-white">
  <div class="absolute inset-0 -z-10 bg-[linear-gradient(90deg,#597f85_0%,#6f9492_35%,#8eada2_65%,#bccaba_100%)]"></div>

  <div class="mx-auto w-full max-w-7xl px-4 md:px-6">
    <div class="flex flex-col items-center gap-8 py-12 md:grid md:grid-cols-3 md:items-center md:gap-6 md:py-8">

      <img src="/assets/ntd-logo-full.png" alt="NTD logo" class="h-20 md:h-auto"/>

      <div class="text-center">
        <a href="mailto:nova.tradition@gmail.com" class="block font-extrabold text-xl md:text-lg hover:opacity-90">
          nova.tradition@gmail.com
        </a>
        <a href="tel:+380731392642" class="block font-extrabold text-xl md:text-lg hover:opacity-90 mt-2 md:mt-1">
          +380731392642
        </a>
      </div>

      <div class="md:text-right text-center">
        <div class="font-extrabold text-3xl md:text-lg">Слідкуй за нами!</div>
        <div class="mt-4 md:mt-3 flex md:justify-end justify-center gap-6 md:gap-4">
          <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="grid place-items-center rounded-full bg-white/10 hover:bg-white/20 transition h-12 w-12 md:h-9 md:w-9">
            <img src="/assets/fb-white.svg" alt="facebook"/>
          </a>
          <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="grid place-items-center rounded-[28%] bg-white/10 hover:bg-white/20 transition h-12 w-12 md:h-9 md:w-9">
            <img src="/assets/insta.svg" alt="instagram"/>
          </a>
        </div>
      </div>
    </div>

    <div class="pb-10 md:pb-6">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <p class="text-center md:text-left text-white/90 text-base md:text-xs">
          © <?= $year ?> ГО «НОВА ТРАДИЦІЯ»
        </p>
        <p class="flex items-center justify-center md:justify-end text-white/90 text-base md:text-xs gap-1">
          Створено разом з <img src="/assets/EveryDev.png" alt="EveryDev" class="h-4"/>
        </p>
      </div>
    </div>

  </div>
</footer>