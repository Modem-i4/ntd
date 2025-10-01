<header id="site-header"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300 ease-out py-6 md:py-8">
  <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-4">
      <a href="/" class="flex items-center gap-3 shrink-0">
        <img src="/assets/ntd-logo-full.png" alt="Нова Традиція" class="h-20">
      </a>
      <nav class="hidden md:flex items-center gap-10 text-white font-semibold text-xl">
        <a href="/" class="hover:opacity-90">ГОЛОВНА</a>
        <a href="/game" class="hover:opacity-90" target="_blank">ДО ГРИ!</a>
        <a href="/training" class="hover:opacity-90">СТВОРЕННЯ СЦЕНАРІЮ</a>
      </nav>
      <button id="nav-toggle"
          class="md:hidden relative h-9 w-9 grid place-items-center rounded-md
                    transition-colors focus:outline-none focus:ring-2 focus:ring-white/60"
              aria-expanded="false" aria-controls="mobile-menu" aria-label="Відкрити меню">
        <span class="sr-only">Меню</span>
        <span class="relative inline-flex h-6 w-8 items-center justify-center">
          <span id="bar1" class="absolute left-1/2 top-1/2 -translate-x-1/2 block h-[2px] w-7 bg-white transition-all duration-300 origin-center"></span>
          <span id="bar2" class="absolute left-1/2 top-1/2 -translate-x-1/2 block h-[2px] w-7 bg-white transition-all duration-300 origin-center"></span>
          <span id="bar3" class="absolute left-1/2 top-1/2 -translate-x-1/2 block h-[2px] w-7 bg-white transition-all duration-300 origin-center"></span>
        </span>
      </button>

    </div>
  </div>
  <div id="mobile-menu"
       class="md:hidden max-h-0 overflow-hidden opacity-0
              transition-[max-height,opacity] duration-300 ease-out">
    <nav class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8 py-3">
      <ul class="flex flex-col gap-2 text-white text-lg font-semibold">
        <li><a class="block py-2" href="/">ГОЛОВНА</a></li>
        <li><a class="block py-2" href="/game" target="_blank">ДО ГРИ!</a></li>
        <li><a class="block py-2" href="/training">СТВОРЕННЯ СЦЕНАРІЮ</a></li>
      </ul>
    </nav>
  </div>
</header>
