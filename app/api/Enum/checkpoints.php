<?php
enum Scenario: string {
  case _default = '_default';
  case danylo   = 'danylo';
  case vitovt   = 'vitovt';
  case orsha    = 'orsha';
  case orlyk    = 'orlyk';
  case unr      = 'unr';
  case khotyn   = 'khotyn';
  case kyiv     = 'kyiv';
  case lesya    = 'lesya';
  case plast    = 'plast';

  /** Етапи, що є завжди */
  private const COMMON_PREFIX = [
    'start' => 'Пролог',
    'pre_start' => 'Початкове опитування', 
    'pre_end' => 'Опитування завершено',
  ];
  private const COMMON_SUFFIX = [
    'post_start' => 'Кінцеве опитування',
    'post_end'   => 'Гру закінчено',
  ];

  private function ownStages(): array {
    return match ($this) {
      self::danylo => [
        'levSpoke' => 'Лев спрямував до батька',
        'danyloSpoke' => 'Данило попросив розпитати бояр',
        'boyarSpoke' => 'Дізнався у Боярина стан справ',
        'convinceDanylo' => 'Спробував переконати Данила',
        'vasylkoSpoke' => 'Василько розповів секрет',
        'meeting' => 'Спільна нарада',
        'epilogue' => 'Післямова',
    ],
      self::vitovt => 
      [
        'task' => 'Вітовт доручив завдання',
        'monkSpoke' => 'Монах дав підказку',
        'svydrSpoke' => 'Свидригайло розповів більше',
        'radnykSpoke' => 'Незнайомець дав обривок листа',
        'yagelloSpoke' => 'Ягайло висловив позицію',
        'guardSpoke' => 'Сторож впустив в покої',
        'vitovtSpoke' => 'З Вітовтом вирішили, як діяти',
        'meeting' => 'Загальні збори',
        'epilogue' => 'Післямова',
    ],
      self::orsha  => [
        'pysarSpoke' => 'Представився Писарю',
        'knyazSpoke' => 'Почув думку місцевого князя',
        'harmashTask' => 'Отримав доручення від Гармаша',
        'ostrozkyTask' => 'Острозький попросив підбадьорити солдат',
        'harmashSpoke' => 'Підбадьорив Гармаша',
        'krymetsSpoke' => 'Підбадьорив Кримскього Татарина',
        'fightStart' => 'Розпочався бій',
        'fightOver' => 'Переміг у битві',
        'epilogue' => 'Післямова',
        ],
      self::orlyk  => [
        'speech' => 'Орлик виступив перед козаками',
        'orlykTask' => 'Орлик доручив завдання',
        'kostSpoke' => 'Гордієнко висловив бачення демократії',
        'judgeSpoke' => 'Суддя з Старшиною розповіли про гілки влади',
        'kostAgitation' => 'Гордієнко провів агітацію',
        'kozakSpoke' => 'Козак дав поради про свободи та контроль влади',
        'starSpoke' => 'Старшина розповів про ознаки демократії',
        'kostDecided' => 'Гордієнко прийняв рішення',
        'sign' => 'Орлик і Гордієнко підписали Конституцію',
        ],
      self::unr  => [
        'meet' => 'Зустріч з Директористами',
        'negotiateOptions' => 'Конфлікт врегульовано',
        'vynnSpoke' => 'Винниченко розповів про види конфліктів',
        'petlSpoke' => 'Петлюра розповів про причини та форми конфліктів',
        'trainReady' => 'Підготовка до відправки потяга',
        'newLoc' => 'Приїзд у Київ для премовин',
        'dyrPower' => 'Директорист намагається вирішити все силою',
        'hetSpoke' => 'Гравдієць погоджується впустити до Скоропадського',
        'skoropHint' => 'Гравець дає пораду Скоропадському',
        'skoropDecide' => 'Скоропадський приймає рішення',
        'dyrStop' => 'Директористи зупиняють штурм',
        ],
      self::khotyn  => [
        'idea' => 'Козак запропонував ідею нічної вилазки',
        'sahaySpoke' => 'Сагайдачний розповів про козацькі тактики',
        'boatmanSpoke' => 'Дізнався у човняра про морські походи',
        'kozakMet' => 'Козак розповів про боротьбу за привілеї',
        'goToKhod' => 'Сагайдачний спрямував до Ходкевича',
        'secretPassage' => 'Дістався секретного проходу',
        'planConducted' => 'План про вилазку спільно ухвалено',
        'defended' => 'Відбили штурм османів',
        'attacked' => 'Здійснили нічну вилазку',
        ],
        self::kyiv  => [
          'stranger'    => 'Зустрів незнайомця',
          'fair'        => 'Пішов з селянином на ярмарок',
          'traderCalled'=> 'Покликала продавчиня',
          'trader'      => 'Продавчиня розповіла про шпигуна',
          'monk'        => 'Монах знайшов кинджал',
          'mist'        => 'Опитав підозрюваного містянина',
          'crafter'     => 'Ремісник впізнав клеймо на зброї',
          'spySeen'     => 'Селянин бачив шпигуна',
          'viz'         => 'Вирішив конфлікт з війтом',
          'viyt'        => 'Доповів про шпигуна',
          'ambush'      => 'Засідка з селянином на полі',
          'spyAppear'   => 'Шпигун з\'явився',
          'spyCaught'   => 'Шпигун розкаявся',
        ],
        self::lesya  => [
          'welcome'      => 'Леся запросила до «Плеяди»',
          'zhan'         => 'Жандарм випитував ідентичність',
          'lesyaStar'    => 'Леся і Старицька: самовизначення',
          'pchil'        => 'Попередив Пчілку про загрозу',
          'pchil1'       => 'Дізнався про роль оточення',
          'lys'          => 'Запросив Лисенка',
          'lys1'         => 'Оцінив свої задатки і здібності',
          'star'         => 'Порятунок із Старицькою',
          'star1'        => 'Дізнався про цінності «Плеяди»',
          'hrom'         => 'Усвідомив громадянський обов\'язок',
          'act'          => 'Порівняв із активою позицією',
          'forb'         => 'Леся про імперські заборони',
          'check'        => 'Жандарм нагрянув з перевіркою',
          'saved'        => 'Твори учасників «Плеяди» врятовано',
        ],
        self::plast  => [
          'meet'      => 'Зустрівся з Пластунами у 1912',
          'fran'      => 'Франко розповів про організації',
          'chmol'      => 'Чмола сказав, як реєструвати ГО',
          'log'      => 'Підготували табір з Пластуном',
          'joined'      => 'Обрано головні цінності',
          'hoverla'      => 'Установчі збори на Говерлі',
          'time'      => 'Випадково перемістився у часі',
          'tys'      => 'У 1914 вирішив втілити проєкт',
          'proj'      => 'Дізнався про соціальне проєктування',
          'bandage'      => 'Знайшов усе для втілення',
          'completed'      => 'Проєкт здійснився!',
          'step'      => 'Зустрів таємничу гостю',
          'war'      => 'Велика війна – велика готовність'
        ],
      self::_default => ['stage1'=>'Етап 1','stage2'=>'Етап 2'],
    };
  }

  public function stages(bool $withCommon = true): array {
    if (!$withCommon) {
      return $this->ownStages();
    }
    return self::mergeStages(self::COMMON_PREFIX, $this->ownStages(), self::COMMON_SUFFIX);
  }

  private static function mergeStages(array ...$parts): array {
    $res = [];
    foreach ($parts as $part) {
      foreach ($part as $k => $v) {
        $res[$k] = $v;
      }
    }
    return $res;
  }

  public static function fromSlugOrDefault(?string $slug): self {
    return self::tryFrom((string)$slug) ?? self::_default;
  }

  public static function stagesBySlug(?string $slug, bool $withCommon = true): array {
    return self::fromSlugOrDefault($slug)->stages($withCommon);
  }

  public static function titlesMap(bool $withCommon = true): array {
    $map = [];
    foreach (self::cases() as $c) {
      $map[$c->value] = $c->stages($withCommon);
    }
    return $map;
  }

  public static function getJson(bool $withCommon = true): string {
    $json = json_encode(self::titlesMap($withCommon), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return str_replace('</', '<\/', $json);
  }

  public static function commonPrefix(): array { return self::COMMON_PREFIX; }
  public static function commonSuffix(): array { return self::COMMON_SUFFIX; }

  public static function zeroProgressFor(?string $slug, bool $withCommon = true): array {
    $keys = array_keys(self::stagesBySlug($slug, $withCommon));
    return array_fill_keys($keys, 0);
  }

  public static function zeroProgressMap(bool $withCommon = true): array {
    $out = [];
    foreach (self::cases() as $c) {
      $out[$c->value] = array_fill_keys(array_keys($c->stages($withCommon)), 0);
    }
    return $out;
  }

  public static function getZeroProgressJson(bool $withCommon = true): string {
    $json = json_encode(self::zeroProgressMap($withCommon), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return str_replace('</', '<\/', $json);
  }
}
