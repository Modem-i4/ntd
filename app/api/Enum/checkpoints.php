<?php
enum Scenario: string {
  case _default = '_default';
  case danylo   = 'danylo';
  case vitovt   = 'vitovt';
  case orsha    = 'orsha';

  /** Етапи, що є завжди */
  private const COMMON_PREFIX = [
    'start' => 'Пролог',
    'pre_start' => 'Початкове опитування', 
    'pre_end' => 'Опитування завершено',
  ];
  private const COMMON_SUFFIX = [
    'epilogue' => 'Післямова',
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
