<?php 
require_once __DIR__ . '/../common/orangeBtn.php';
?>

<section class="relative overflow-hidden text-white md:mt-16 md:bg-fixed bg-cover bg-center bg-[url('/assets/bg-blur.webp')]">
  <div class="mx-auto flex max-w-6xl flex-col gap-x-10 px-4 py-12 md:py-5 md:flex-row md:items-center md:gap-x-16">
    <div class="md:w-1/2">
      <img
        src="/assets/ntd-text-logo.webp"
        alt="Нова Традиція"
        class="mb-6 w-[30rem] max-w-full mx-auto md:mx-0 filter drop-shadow-[0_2px_8px_rgba(0,0,0,0.35)]"
      />
      <p class="max-w-xl text-base leading-relaxed md:text-xl font-bold mx-auto md:mx-0 px-2 md:px-0">
        — це безкоштовна освітня гра з історії України та громадянської освіти для
        учнів та учениць 7–11 класів, побудована у форматі інтерактивних історій.
        Учні потрапляють у вир історичних подій, щоб побачити їх на власні очі та
        засвоїти шкільну програму з уст історичних персоналій.
      </p>
    </div>

    <div class="md:w-1/2 mt-6 md:mt-0">
      <div class="flex flex-col gap-4 items-stretch">

        <div class="w-full flex justify-start md:justify-center">
          <?= btn_orange(
            '15-хвилинні<br>сюжети',
            'transform md:translate-x-[15%] xl:translate-y-[45%] transition-transform duration-200 hover:scale-105'
          ) ?>
        </div>

        <div class="w-full flex justify-end">
          <?= btn_orange(
            'збільшує інтерес<br>до навчання',
            'transform xl:translate-x-[30%] xl:translate-y-[35%] transition-transform duration-200 hover:scale-105'
          ) ?>
        </div>

        <div class="w-full flex">
          <?=
            btn_orange(
              function () { ?>
                <div class="text-center leading-tight">
                  <div>доступна на</div>
                  <img
                    src="/assets/misc/devices.webp"
                    alt="Пристрої"
                    class="mx-auto mt-1 w-30 md:w-32"
                  />
                </div>
              <?php },
              'transform md:translate-x-[65%] xl:-translate-y-[25%] transition-transform duration-200 hover:scale-105'
            )
          ?>
        </div>

        <div class="w-full flex justify-end">
          <?= btn_orange(
            'розвінчує<br>історичні міфи',
            'transform md:translate-x-[8%] xl:-translate-y-[35%] transition-transform duration-200 hover:scale-105'
          ) ?>
        </div>

        <div class="w-full flex justify-start md:justify-center">
          <?= btn_orange(
            'базується на<br>методології НУШ',
            'transform xl:-translate-y-[45%] transition-transform duration-200 hover:scale-105'
          ) ?>
        </div>

      </div>
    </div>
  </div>
</section>
