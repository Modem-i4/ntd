<?php
// ===== Навігація: slug-и як ключі =====
$chaptersBase = 'training/Pol';
$categories = [
  'intro' => [
    'title' => 'Привіт',
    'items' => [
      'intro'   => ['title' => 'Познайомимось!'],
    ],
  ],
  'myth' => [
    'title' => 'Ідеї',
    'items' => [
      'ukr' => ['title' => 'Українство'],
      'fem' => ['title' => 'Фемінізм'],
    ],
  ],
  [
    'title' => 'Каркас сценарію',
    'items' => [
      'chars'   => ['title' => 'Персонажі'],
      'events'   => ['title' => 'Події'],
    ],
  ],
  'encyclopedia' => [
    'title' => 'Знання',
    'items' => [
      'know'  => ['title' => 'Нащо вони?'],
      'identity'  => ['title' => 'Ідентичність'],
      'socializ'  => ['title' => 'Соціалізація'],
      'samorealiz'  => ['title' => 'Самореалізація'],
    ],
  ],
];
$heroTitle = "Тренінг зі створення сценарію для гри «Нова Традиція»";
$introRoute = __DIR__ . '/../sections/training/pol/intro.php';
?>
<?php require __DIR__ . '/../parts/training.php'; ?>