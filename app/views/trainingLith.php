<?php
// ===== Навігація: slug-и як ключі =====
$chaptersBase = 'training/Lith';
$categories = [
  'intro' => [
    'title' => 'Завдання',
    'items' => [
      'intro'   => ['title' => 'Познайомимось!'],
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
$heroTitle = "Тренінг зі створення сценарію для гри «Нова Традиція»";
$introRoute = __DIR__ . '/../sections/training/lith/intro.php';
?>
<?php require __DIR__ . '/../parts/training.php'; ?>