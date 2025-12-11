<section class="bg-white lg:py-20" id="results">
  <div class="max-w-[90rem] mx-auto px-4 lg:px-8">
    <!-- Ряд: колодязь + цифри + кружечки -->
    <div class="mt-8 lg:mt-10 flex justify-between gap-6 items-stretch">
      <!-- Колодязь (тільки lg+) -->
      <div class="hidden lg:block flex-shrink-0 w-80 xl:w-96 self-end">
        <img
          src="/assets/decor/well.webp"
          alt="Колодязь"
          class="w-full h-auto"
        >
      </div>

      <!-- Центральна колонка -->
      <div class="flex flex-col flex-1">
<?php
$stats_num = $stats['numeric'];
$stats_tests = $stats['improvement']['tests'];
function toPerc($stats,$param) { return (int) ($stats[$param]['improvement']*100); }
 ?>
        <!-- заголовок: зверху колонки -->
        <h2 class="text-center text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-ermilov tracking-tight">
          РЕЗУЛЬТАТИ ГРИ  
        </h2>

        <!-- блок з цифрами + підписом -->
        <div class="mt-6 lg:flex-1 lg:flex lg:flex-col lg:justify-center lg:items-center">
          <div class="grid grid-cols-2 gap-x-12 gap-y-10 text-center px-12 lg:px-0">
            <div>
              <div class="text-4xl md:text-5xl lg:text-6xl font-ermilov"><?= $stats_num['sessions'] ?? 100 ?></div>
              <div class="mt-1 text-base md:text-lg leading-snug">
                Ігрові<br>сесії
              </div>
            </div>
            <div>
              <div class="text-4xl md:text-5xl lg:text-6xl font-ermilov"><?= $stats_num['uniquePlayers'] ?? 100 ?></div>
              <div class="mt-1 text-base md:text-lg leading-snug">
                Гравців та<br>гравчинь
              </div>
            </div>
            <div>
              <div class="text-4xl md:text-5xl lg:text-6xl font-ermilov"><?= $stats_num['lessons'] ?? 100 ?></div>
              <div class="mt-1 text-base md:text-lg leading-snug">
                Проведено<br>уроків
              </div>
            </div>
            <div>
              <div class="text-4xl md:text-5xl lg:text-6xl font-ermilov"><?= $stats_num['uniqueTeachers'] ?? 100 ?></div>
              <div class="mt-1 text-base md:text-lg leading-snug">
                Вчителів та<br>вчительок
              </div>
            </div>
          </div>

          <p class="mt-6 text-center text-xs sm:text-sm text-gray-500 hidden lg:block">
            статистика оновлюється щогодини автоматично
          </p>
        </div>
      </div>

      <!-- Кружечки: десктоп -->
      <div class="hidden lg:block flex-shrink-0 w-80 xl:w-96 isolate self-end pt-20 my-auto">
        <div class="relative w-full h-72">
          <!-- верхній кружечок -->
          <div class="absolute top-1 xl:-top-20 left-1/2 -translate-x-1/2
                      lg:w-40 lg:h-40 xl:w-52 xl:h-52
                      rounded-full bg-[#FFF4DD]/80 mix-blend-multiply
                      flex flex-col items-center justify-center text-center px-4">
            <div class="text-sm lg:text-base xl:text-lg font-semibold leading-tight">
              Інтерес до<br>предмету:
            </div>
            <div class="mt-1 text-2xl lg:text-3xl xl:text-5xl font-ermilov">+45%</div>
          </div>

          <!-- лівий нижній -->
          <div class="absolute bottom-6 left-2 xl:left-0
                      lg:w-40 lg:h-40 xl:w-52 xl:h-52
                      rounded-full bg-[#FCE1D4]/80 mix-blend-multiply
                      flex flex-col items-center justify-center text-center px-4">
            <div class="text-sm lg:text-base xl:text-lg font-semibold leading-tight">
              Критичне<br>мислення:
            </div>
            <div class="mt-1 text-2xl lg:text-3xl xl:text-5xl font-ermilov">+<?= toPerc($stats_tests,'critical') ?>%</div>
          </div>

          <!-- правий нижній -->
          <div class="absolute bottom-0 right-2 xl:right-0
                      lg:w-40 lg:h-40 xl:w-52 xl:h-52
                      rounded-full bg-[#D9F2E5]/80 mix-blend-multiply
                      flex flex-col items-center justify-center text-center px-4">
            <div class="text-sm lg:text-base xl:text-lg font-semibold leading-tight">
              Протидія<br>міфам:
            </div>
            <div class="mt-1 text-2xl lg:text-3xl xl:text-5xl font-ermilov">+<?= toPerc($stats_tests,'narratives')  ?>%</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Кружечки: мобілка (під цифрами) -->
    <div class="mt-10 flex justify-center items-end -space-x-4 lg:hidden isolate">
      <div class="w-32 h-32 sm:w-36 sm:h-36 md:w-40 md:h-40 rounded-full bg-[#FCE1D4]/80 mix-blend-multiply flex flex-col items-center justify-center text-center px-3">
        <div class="text-sm md:text-base font-semibold leading-tight">
          Критичне<br>мислення:
        </div>
        <div class="mt-1 text-2xl md:text-3xl font-ermilov">+<?= toPerc($stats_tests,'critical')  ?>%</div>
      </div>

      <div class="w-32 h-32 sm:w-36 sm:h-36 md:w-40 md:h-40 rounded-full bg-[#D9F2E5]/80 mix-blend-multiply flex flex-col items-center justify-center text-center px-3 z-10">
        <div class="text-sm md:text-base font-semibold leading-tight">
          Протидія<br>міфам:
        </div>
        <div class="mt-1 text-2xl md:text-3xl font-ermilov">+<?= toPerc($stats_tests,'narratives') ?>%</div>
      </div>

      <div class="w-32 h-32 sm:w-36 sm:h-36 md:w-40 md:h-40 rounded-full bg-[#FFF4DD]/80 mix-blend-multiply flex flex-col items-center justify-center text-center px-3">
        <div class="text-sm md:text-base font-semibold leading-tight">
          Інтерес до<br>предмету:
        </div>
        <div class="mt-1 text-2xl md:text-3xl font-ermilov">+45%</div>
      </div>
    </div>

    <p class="mt-6 text-center text-xs sm:text-sm text-gray-500 lg:hidden">
      статистика оновлюється щогодини автоматично
    </p>
  </div>
</section>
