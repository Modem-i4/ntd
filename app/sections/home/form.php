<?php 
require_once __DIR__ . '/../common/orangeBtn.php';
?>

<section class="relative  md:gap-10 mx-auto px-4 lg:px-10 xl:px-20 py-10 md:py-24">
  <h2 class="text-center text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-ermilov tracking-tight mb-12">
    ЯК ОТРИМАТИ ДОСТУП ДО ГРИ?  
  </h2>

  <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 lg:items-center">
    <div class="relative flex justify-center lg:justify-start">

      <div class="absolute top-1/2 -translate-y-1/2 w-[55vw] max-w-[420px] h-[420px] -z-10 pointer-events-none scale-[0.6] -left-28 md:scale-100 md:left-0">
        <div class="relative h-full">
          <div class="absolute -left-18 -top-10 h-[520px] aspect-square rounded-full opacity-70 bg-[linear-gradient(135deg,#F9D9CA,#CDE6DA)]"></div>
          <div class="absolute left-[300px] top-16 h-[430px] aspect-square rounded-full opacity-80 bg-[linear-gradient(135deg,#F9D9CA,#CDE6DA)]"></div>
          <img src="/assets/decor/red-dot.png"  alt="" class="absolute left-24 top-2/3 w-5 h-5 drop-shadow-md">
          <img src="/assets/decor/blue-dot.png" alt="" class="absolute left-[260px] top-14 w-7 h-7 drop-shadow-md">
        </div>
      </div>

      <div class="relative w-full max-w-[500px]">
        <img
          src="/assets/misc/mockup-desktop.webp"
          alt="Інтерфейс гри «Нова традиція» на комп'ютері"
          class=" h-auto filter w-[80%] xl:w-full"
        />
        <img
          src="/assets/misc/mockup-mobile.webp"
          alt="Інтерфейс гри «Нова традиція» на телефоні"
          class="absolute -bottom-2 w-[68%] max-w-[400px] filter right-0 xl:-right-20 2xl:-right-32"
        />
      </div>
    </div>

    <div class="pt-12 max-w-xl mx-auto lg:ml-0 md:text-xl">
        <p>Гра та всі матеріали є <b>безкоштовними</b>. Заповніть коротку <b>реєстраційну форму</b> і пройдіть  відеотренінг на 1 годину з найкращими практиками застосування гри.</p>
        <p>Після проведення гри з учнями ви зможете отримати сертифікати підвищення кваліфікації та приємні подарунки від нас! Деталі у формі. </p>
        
        <a href="https://forms.gle/zj8XumuBV3uGmZeB6" target="_blank" class="flex justify-center md:justify-start pt-10">
            <?= btn_orange('Реєстраційна форма', 'cursor-pointer transform transition-transform duration-200 hover:scale-105 py-6') ?>
        </a>
    </div>
  </div>
</section>
