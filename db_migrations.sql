CREATE TABLE `reasons` (
  `scenario_slug` varchar(12) NOT NULL,
  `sur_n` tinyint(4) NOT NULL,
  `number` tinyint(4) NOT NULL,
  `option_text` text DEFAULT null,
  PRIMARY KEY (`scenario_slug`, `sur_n`, `number`)
);

CREATE TABLE `scenarios` (
  `slug` varchar(12) PRIMARY KEY NOT NULL
);

CREATE TABLE `sessions` (
  `id` mediumint(9) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_code` char(6) NOT NULL,
  `scenario_slug` varchar(12) NOT NULL,
  `started_at` timestamp,
  `game_started_at` timestamp null DEFAULT null,
  `game_ended_at` timestamp null DEFAULT null,
  `ended_at` timestamp null DEFAULT null
);

CREATE TABLE `surveys` (
  `scenario_slug` varchar(12) NOT NULL,
  `sur_n` tinyint(4) NOT NULL,
  `question_text` text DEFAULT null,
  PRIMARY KEY (`scenario_slug`, `sur_n`)
);

CREATE TABLE `ans_sur` (
  `id` mediumint(9) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `session_id` mediumint(9) NOT NULL,
  `scenario_slug` varchar(12) NOT NULL,
  `sur_n` tinyint(4) NOT NULL,
  `reason_n` tinyint(4) DEFAULT null,
  `val_before` tinyint(4) DEFAULT null,
  `val_after` tinyint(4) DEFAULT null,
  `created_at` timestamp null DEFAULT null
);

CREATE TABLE `metrics` (
  `metric` varchar(64) PRIMARY KEY NOT NULL
);

CREATE TABLE `tests` (
  `scenario_slug` varchar(12) NOT NULL,
  `metric` varchar(64) NOT NULL,
  `number` tinyint(4) NOT NULL,
  `question_text` text DEFAULT null,
  PRIMARY KEY (`scenario_slug`, `metric`, `number`)
);

CREATE TABLE `ans_test` (
  `id` mediumint(9) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `session_id` mediumint(9) NOT NULL,
  `scenario_slug` varchar(12) NOT NULL,
  `metric` varchar(64) NOT NULL,
  `test_n` tinyint(4) NOT NULL,
  `first_option_n` tinyint(4),
  `final_option_n` tinyint(4),
  `delta` tinyint(4) DEFAULT null,
  `created_at` timestamp null DEFAULT null
);

CREATE TABLE `test_opts` (
  `scenario_slug` varchar(12) NOT NULL,
  `metric` varchar(64) NOT NULL,
  `test_n` tinyint(4) NOT NULL,
  `number` tinyint(4) NOT NULL,
  `option_text` text DEFAULT null,
  `correct` tinyint(1) DEFAULT null,
  PRIMARY KEY (`scenario_slug`, `metric`, `test_n`, `number`)
);

CREATE TABLE `users` (
  `code` char(6) PRIMARY KEY NOT NULL,
  `saves` longtext DEFAULT null,
  `created_at` timestamp NOT NULL DEFAULT (current_timestamp())
);

CREATE TABLE `user_installations` (
  `id` mediumint(9) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_code` char(6) NOT NULL,
  `fid` varchar(128) DEFAULT null
);

ALTER TABLE `tests` ADD CONSTRAINT `fk_metr_test` FOREIGN KEY (`metric`) REFERENCES `metrics` (`metric`);

ALTER TABLE `surveys` ADD CONSTRAINT `fk_surs_scenario` FOREIGN KEY (`scenario_slug`) REFERENCES `scenarios` (`slug`);

ALTER TABLE `reasons` ADD CONSTRAINT `fk_reasons_sur` FOREIGN KEY (`scenario_slug`, `sur_n`) REFERENCES `surveys` (`scenario_slug`, `sur_n`);

ALTER TABLE `sessions` ADD CONSTRAINT `fk_sess_scenario` FOREIGN KEY (`scenario_slug`) REFERENCES `scenarios` (`slug`);

ALTER TABLE `sessions` ADD CONSTRAINT `fk_sess_user` FOREIGN KEY (`user_code`) REFERENCES `users` (`code`);

ALTER TABLE `ans_sur` ADD CONSTRAINT `fk_sa_reason` FOREIGN KEY (`scenario_slug`, `sur_n`, `reason_n`) REFERENCES `reasons` (`scenario_slug`, `sur_n`, `number`);

ALTER TABLE `ans_sur` ADD CONSTRAINT `fk_sa_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`);

ALTER TABLE `ans_sur` ADD CONSTRAINT `fk_sa_sur` FOREIGN KEY (`scenario_slug`, `sur_n`) REFERENCES `surveys` (`scenario_slug`, `sur_n`);

ALTER TABLE `tests` ADD CONSTRAINT `fk_tests_scenario` FOREIGN KEY (`scenario_slug`) REFERENCES `scenarios` (`slug`);

ALTER TABLE `ans_test` ADD CONSTRAINT `fk_ta_final` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`, `final_option_n`) REFERENCES `test_opts` (`scenario_slug`, `metric`, `test_n`, `number`);

ALTER TABLE `ans_test` ADD CONSTRAINT `fk_ta_first` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`, `first_option_n`) REFERENCES `test_opts` (`scenario_slug`, `metric`, `test_n`, `number`);

ALTER TABLE `ans_test` ADD CONSTRAINT `fk_ta_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`);

ALTER TABLE `ans_test` ADD CONSTRAINT `fk_ta_test` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`) REFERENCES `tests` (`scenario_slug`, `metric`, `number`);

ALTER TABLE `test_opts` ADD CONSTRAINT `fk_to_test` FOREIGN KEY (`scenario_slug`, `metric`, `test_n`) REFERENCES `tests` (`scenario_slug`, `metric`, `number`);

ALTER TABLE `user_installations` ADD CONSTRAINT `fk_ui_user` FOREIGN KEY (`user_code`) REFERENCES `users` (`code`);







INSERT INTO `scenarios` (`slug`) VALUES
('vitovt'),
('orsha'),
('danylo');

INSERT INTO `metrics` (`metric`) VALUES
('critical'),
('narratives');



INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- VITOVT — CRITICAL
('vitovt','critical',1,'Чому звичайна корона була настільки важлива для князя Вітовта та Великого князівства Литовського?'),
('vitovt','critical',2,'Чому князь Вітовт та король Ягайло, бувши суперниками, змогли разом воювати під Грюнвальдом?'),
('vitovt','critical',3,'Чому підтримка руських земель була важливою для претендентів на владу у Великому князівстві Литовському?'),

-- VITOVT — NARRATIVES
('vitovt','narratives',1,'Як правителі Великого князівства Литовського ставилися до руської спадщини під час приєднання руських земель?'),
('vitovt','narratives',2,'Яке твердження про спадщину Русі-України є найбільш точним?'),
('vitovt','narratives',3,'Як найкраще описати становище руських земель у складі Великого князівства Литовського?'),

-- ORSHA — CRITICAL
('orsha','critical',1,'Якою була головна причина війни між московією та Великим князівством Литовським на межі XV–XVI ст.?'),
('orsha','critical',2,'Яке значення мала битва під Оршею для Великого князівства Литовського?'),

-- ORSHA — NARRATIVES
('orsha','narratives',1,'Що означала для московських правителів ідея «москва — третій Рим»?'),
('orsha','narratives',2,'москва говорила про захист православних у ВКЛ, адже хотіла:'),
('orsha','narratives',3,'Титул московського правителя «володар всієї Русі» у міжнародній політиці означав:'),

-- DANYLO — CRITICAL
('danylo','critical',1,'Для чого Данило Романович прийняв королівську корону від Папи Римського?'),
('danylo','critical',2,'Чому обіцяний Папою Римським хрестовий похід проти монголів так і не відбувся?'),
('danylo','critical',3,'Як Данило та Василько Романовичі організували спільне правління Волинсько-Галицькою державою?'),

-- DANYLO — NARRATIVES
('danylo','narratives',1,'Поїздка Данила в Золоту Орду та визнання влади хана була:'),
('danylo','narratives',2,'Волинсько-Галицьке князівство у XIII столітті було:');

/* ---------- INSERT INTO test_opts ---------- */
INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- VITOVT — CRITICAL (1)
('vitovt','critical',1,1,'Щоб довести свою зверхність над сусідніми державами.',0),
('vitovt','critical',1,2,'Щоб вона підтверджувала багатство правителя і держави.',0),
('vitovt','critical',1,3,'Щоб зміцнити незалежність держави в очах інших правителів.',1),

-- VITOVT — CRITICAL (2)
('vitovt','critical',2,1,'Бо Ягайло, як його король, наказав Вітовту приєднатися до свого війська.',0),
('vitovt','critical',2,2,'Бо у них з''явився спільний ворог, Тевтонський орден.',1),
('vitovt','critical',2,3,'Бо як двоюрідні брати вони часто діяли разом.',0),

-- VITOVT — CRITICAL (3)
('vitovt','critical',3,1,'Бо ці землі платили найбільші податки, і їхні гроші були потрібні для утримання війська.',0),
('vitovt','critical',3,2,'Бо руська культура вважалася давнішою, і її прийняття давало правителю більше авторитету.',0),
('vitovt','critical',3,3,'Бо руське населення становило більшість у державі, і правителі мали рахуватися з його думкою.',1),

-- VITOVT — NARRATIVES (1)
('vitovt','narratives',1,1,'Знищували, щоб насадити свою культуру',0),
('vitovt','narratives',1,2,'Взяли її за одну з основ для створення власної держави',1),
('vitovt','narratives',1,3,'Не втручалися і були байдужими до неї',0),

-- VITOVT — NARRATIVES (2)
('vitovt','narratives',2,1,'Велике князівство Литовське стало одним із головних політичних та культурних спадкоємців Русі, об''єднавши більшість її земель.',1),
('vitovt','narratives',2,2,'московія забрала більшість земель Русі після монгольської навали, тому претендувала на її спадщину.',0),
('vitovt','narratives',2,3,'Після монгольської навали спадщина Русі була переважно втрачена, тому ніхто не міг претендувати на її відновлення.',0),

-- VITOVT — NARRATIVES (3)
('vitovt','narratives',3,1,'Це була окупація, де литовська знать цілеспрямовано знищувала місцеві культуру, мову та віру.',0),
('vitovt','narratives',3,2,'Це був багатоетнічний союз, де руські землі становили більшість і зберігали автономію, закони, віру та мову.',1),
('vitovt','narratives',3,3,'Це було злиття етносів, де руські та литовські землі перетворювалися на єдиний народ.',0),

-- ORSHA — CRITICAL (1)
('orsha','critical',1,1,'Боротьба за спадщину Русі-України та контроль над руськими землями.',1),
('orsha','critical',1,2,'Суперечка через важливу торгівельну фортецю Смоленськ та шлях із «варягів у греки».',0),
('orsha','critical',1,3,'Особистий конфлікт та образа між правителями обох держав.',0),

-- ORSHA — CRITICAL (2)
('orsha','critical',2,1,'Вона зупинила наступ ворога та прославила військо в Європі.',1),
('orsha','critical',2,2,'Вона призвела до капітуляції москви та завершення війни.',0),
('orsha','critical',2,3,'Вона забезпечила тривале перемир’я з москвою та повернення втрачених земель.',0),

-- ORSHA — NARRATIVES (1)
('orsha','narratives',1,1,'Культурну місію зі збереження спадщини та традицій Риму.',0),
('orsha','narratives',1,2,'Ідеологічне обґрунтування права на зверхність та розширення свого впливу.',1),
('orsha','narratives',1,3,'Зміцнення авторитету церкви та правителя всередині самої держави.',0),

-- ORSHA — NARRATIVES (2)
('orsha','narratives',2,1,'Забезпечення релігійної свободи для одновірців на всій колишній території Русі.',0),
('orsha','narratives',2,2,'Створення формального приводу для втручання у внутрішні справи ВКЛ та агресії.',1),
('orsha','narratives',2,3,'Привернення уваги Константинопольського патріарха до проблем православ''я.',0),

-- ORSHA — NARRATIVES (3)
('orsha','narratives',3,1,'Спробу зміцнення авторитету своєї влади.',0),
('orsha','narratives',3,2,'Закріплення факту володіння більшістю земель Русі.',0),
('orsha','narratives',3,3,'Претензію на землі колишньої Русі-України',1),

-- DANYLO — CRITICAL (1)
('danylo','critical',1,1,'Щоб отримати допомогу європейських держав і скликали хрестовий похід.',1),
('danylo','critical',1,2,'Щоб мати рівний статус з іншими правителям католицьких держав.',0),
('danylo','critical',1,3,'Щоб вільно торгувати та взаємодіяти із католицькими державами.',0),

-- DANYLO — CRITICAL (2)
('danylo','critical',2,1,'Бо Данило не виконав свою обіцянку щодо укладення церковної унії.',0),
('danylo','critical',2,2,'Бо європейці хотіли спробувати провести переговори з монгольською імперією.',0),
('danylo','critical',2,3,'Бо європейські правителі були зайняті власними війнами та конфліктами.',1),

-- DANYLO — CRITICAL (3)
('danylo','critical',3,1,'Данило займався загальнодержавними справами, а Василько його підтримував, правлячи на Волині.',1),
('danylo','critical',3,2,'Вони вели боротьбу за вплив, намагаючись перетягнути на свій бік бояр та сусідні країни.',0),
('danylo','critical',3,3,'Вони розділили державу на дві незалежні частини та правили в них, щоб уникнути конфліктів за владу.',0),

-- DANYLO — NARRATIVES (1)
('danylo','narratives',1,1,'Свідченням повної втрати державності та особистим приниженням правителя.',0),
('danylo','narratives',1,2,'Дипломатичним кроком, що дозволив зберегти державу',1),
('danylo','narratives',1,3,'Спробою укласти військовий союз із Золотою Ордою проти угорських та польських нападників',0),

-- DANYLO — NARRATIVES (2)
('danylo','narratives',2,1,'Невеликою частиною «руського світу», залежною від сильніших сусідів.',0),
('danylo','narratives',2,2,'Головним спадкоємцем Русі-України та впливовим європейським королівством.',1),
('danylo','narratives',2,3,'Сильно залежною державою, що страждало від постійних внутрішніх боярських воєн',0);



INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('vitovt', 1, 'Я готов[ий/а] діяти й давати ідеї, коли хочу щось змінити в школі чи навколо.'),
('vitovt', 2, 'Якщо є офіційний спосіб розв’язати проблему (через школу чи громаду), я ним скористаюся.'),
('vitovt', 3, 'Я хочу вирішувати справи чесно й відкрито, зважаючи на правила і докази.'),

('danylo', 1, 'Я готовий діяти рішуче, коли хочу щось змінити в школі чи навколо.'),
('danylo', 2, 'Я готовий шукати однодумців, бо разом легше досягати мети.'),
('danylo', 3, 'Мій голос важливий: загалом, в громаді, в школі.'),

('orsha', 1, 'Я готовий діяти й давати ідеї, коли хочу щось змінити в школі чи навколо.'),
('orsha', 2, 'Мій голос важливий: загалом, в громаді, в школі.'),
('orsha', 3, 'Я готовий брати відповідальність за свої рішення, навіть якщо вони впливають на інших.');


INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES

('vitovt', 1, 1, 'Отримана грамота з печаткою від Вітовта показала, що офіційний шлях працює краще за обхід правил'),
('vitovt', 1, 2, 'Публічний показ листа переконав, що відкритість і наявність доказів допомагають отримати підтримку'),
('vitovt', 1, 3, 'Опора Свидригайла на руську більшість дала зрозуміти, що врахування голосу громади дає результат'),

('vitovt', 2, 1, 'Отримана грамота з печаткою від Вітовта показала, що офіційний шлях працює краще за обхід правил'),
('vitovt', 2, 2, 'Публічний показ листа переконав, що відкритість і наявність доказів допомагають отримати підтримку'),
('vitovt', 2, 3, 'Опора Свидригайла на руську більшість дала зрозуміти, що врахування голосу громади дає результат'),

('vitovt', 3, 1, 'Отримана грамота з печаткою від Вітовта показала, що офіційний шлях працює краще за обхід правил'),
('vitovt', 3, 2, 'Публічний показ листа переконав, що відкритість і наявність доказів допомагають отримати підтримку'),
('vitovt', 3, 3, 'Опора Свидригайла на руську більшість дала зрозуміти, що врахування голосу громади дає результат'),


('danylo', 1, 1, 'Після розвідки настроїв бояр і вояків, зрозумі[в/ла], що рішення мають силу, коли спираються на думку громади'),
('danylo', 1, 2, 'Коли хрестовий похід не прийшов, і король Данило сам виступив проти Орди, я побачи[в/ла], як важливо діяти рішуче'),
('danylo', 1, 3, 'Командна робота Данила і Василька показала, що знайшовши однодумців, можна втілити великі справи'),

('danylo', 2, 1, 'Після розвідки настроїв бояр і вояків, зрозумі[в/ла], що рішення мають силу, коли спираються на думку громади'),
('danylo', 2, 2, 'Коли хрестовий похід не прийшов, і король Данило сам виступив проти Орди, я побачи[в/ла], як важливо діяти рішуче'),
('danylo', 2, 3, 'Командна робота Данила і Василька показала, що знайшовши однодумців, можна втілити великі справи'),

('danylo', 3, 1, 'Після розвідки настроїв бояр і вояків, зрозумі[в/ла], що рішення мають силу, коли спираються на думку громади'),
('danylo', 3, 2, 'Коли хрестовий похід не прийшов, і король Данило сам виступив проти Орди, я побачи[в/ла], як важливо діяти рішуче'),
('danylo', 3, 3, 'Командна робота Данила і Василька показала, що знайшовши однодумців, можна втілити великі справи'),


('orsha', 1, 1, 'У момент, коли я сам[/а] да[в/ла] пораду князю Острозькому, відчу[в/ла] цінність власної ініціативи.'),
('orsha', 1, 2, 'Побачи[в/ла], що перемога стала можливою завдяки спільним зусиллям — від князя до простого воїна.'),
('orsha', 1, 3, 'Завдяки перемозі відчу[в/ла], як успішне великої справи обов`язку приносить результат.'),

('orsha', 2, 1, 'У момент, коли я сам[/а] да[в/ла] пораду князю Острозькому, відчу[в/ла] цінність власної ініціативи.'),
('orsha', 2, 2, 'Побачи[в/ла], що перемога стала можливою завдяки спільним зусиллям — від князя до простого воїна.'),
('orsha', 2, 3, 'Завдяки перемозі відчу[в/ла], як успішне великої справи обов`язку приносить результат.'),

('orsha', 3, 1, 'У момент, коли я сам[/а] да[в/ла] пораду князю Острозькому, відчу[в/ла] цінність власної ініціативи.'),
('orsha', 3, 2, 'Побачи[в/ла], що перемога стала можливою завдяки спільним зусиллям — від князя до простого воїна.'),
('orsha', 3, 3, 'Завдяки перемозі відчу[в/ла], як успішне великої справи обов`язку приносить результат.');


-- ------------- МІГРАЦІЯ 1 -------------- --
-- ------------- МІГРАЦІЯ 1 -------------- --

-- 0) Вчителі (аналог users: code + installations)
CREATE TABLE IF NOT EXISTS teachers (
  code CHAR(6) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP()),
  PRIMARY KEY (code)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS teacher_installations (
  id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
  teacher_code CHAR(6) NOT NULL,
  fid VARCHAR(128) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_ti_teacher (teacher_code),
  CONSTRAINT fk_ti_teacher
    FOREIGN KEY (teacher_code) REFERENCES teachers(code)
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 1) Нова таблиця games з teacher_code (а не fid)
CREATE TABLE IF NOT EXISTS games (
  code CHAR(6) NOT NULL,
  teacher_code CHAR(6) NULL,
  scenario_slug VARCHAR(12) NOT NULL,
  PRIMARY KEY (code),
  KEY idx_games_scenario_slug (scenario_slug),
  KEY idx_games_teacher_code (teacher_code),
  CONSTRAINT fk_game_scenario
    FOREIGN KEY (scenario_slug) REFERENCES scenarios(slug),
  CONSTRAINT fk_games_teacher
    FOREIGN KEY (teacher_code) REFERENCES teachers(code)
      ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 2) Додаємо game_code у sessions (тимчасово NULL)
ALTER TABLE sessions
  ADD COLUMN IF NOT EXISTS game_code CHAR(6) NULL AFTER user_code;

-- 3) Створюємо “легасі-гру” для кожного сценарію (детермінований 6-значний код)
-- code = LPAD(MOD(CRC32(CONCAT(slug, '_legacy')), 1000000), 6, '0')
INSERT INTO games (code, scenario_slug)
SELECT
  LPAD(MOD(CRC32(CONCAT(s.slug, '_legacy')), 1000000), 6, '0') AS code,
  s.slug AS scenario_slug
FROM scenarios s
ON DUPLICATE KEY UPDATE
  scenario_slug = VALUES(scenario_slug);

-- 4) Бекфіл game_code у sessions за scenario_slug
UPDATE sessions se
JOIN (
  SELECT slug,
         LPAD(MOD(CRC32(CONCAT(slug, '_legacy')), 1000000), 6, '0') AS code
  FROM scenarios
) m ON m.slug = se.scenario_slug
SET se.game_code = m.code
WHERE se.game_code IS NULL;

-- 5) Прибираємо старий FK і колонку scenario_slug з sessions
ALTER TABLE sessions
  DROP FOREIGN KEY fk_sess_scenario,
  DROP COLUMN scenario_slug;

-- 6) Додаємо FK на games та допоміжні поля у sessions
ALTER TABLE sessions
  ADD CONSTRAINT fk_sess_game
    FOREIGN KEY (game_code) REFERENCES games(code);

ALTER TABLE sessions
  MODIFY COLUMN game_code CHAR(6) NOT NULL,
  ADD COLUMN IF NOT EXISTS progress JSON NULL,
  ADD COLUMN IF NOT EXISTS score SMALLINT NULL;

-- ------------- КІНЕЦЬ МІГРАЦІЇ 1 -------------- --
