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
  created_at TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP()),
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


-- ------------- МІГРАЦІЯ 2 -------------- --
-- ------------- МІГРАЦІЯ 2 -------------- --

INSERT INTO `metrics` (`metric`) VALUES
('knowladge');

-- ------------- ОРЛИК -------------- --
INSERT INTO `scenarios` (`slug`) VALUES
('orlyk');

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- orlyk — knowladge
('orlyk','knowladge',1,'Коли громадяни обирають депутатів, щоб ті ухвалювали закони від їхнього імені, це називається'),
('orlyk','knowladge',2,'Яка головна мета ухвалення конституції в державі?'),
('orlyk','knowladge',3,'Навіщо в демократії потрібен поділ влади на 3 гілки?'),

-- orlyk — critical
('orlyk','critical',1,'Яка головна цінність політичного плюралізму (наявності багатьох партій та думок)?'),
('orlyk','critical',2,'Який принцип демократії може допомогти боротися з корупцією?'),
('orlyk','critical',3,'У демократії джерелом влади є народ. Що це означає на практиці?');


INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- orlyk — knowladge (1)
('orlyk','knowladge',1,1,'Прямою демократією.',0),
('orlyk','knowladge',1,2,'Представницькою демократією.',1),
('orlyk','knowladge',1,3,'Політичним плюралізмом.',0),

-- orlyk — knowladge (2)
('orlyk','knowladge',2,1,'Детально перелічити всі права та обов''язки громадян.',0),
('orlyk','knowladge',2,2,'Забезпечити швидке та ефективне ухвалення рішень урядом.',0),
('orlyk','knowladge',2,3,'Встановити межі для влади та гарантувати основні права.',1),

-- orlyk — knowladge (3)
('orlyk','knowladge',3,1,'Щоб кожна гілка влади могла працювати ефективніше.',0),
('orlyk','knowladge',3,2,'Щоб запобігти концентрації всієї влади в одних руках.',1),
('orlyk','knowladge',3,3,'Щоб громадяни могли чітко знати, хто саме відповідає за ту чи ту проблему.',0),

-- orlyk — critical (1)
('orlyk','critical',1,1,'Він змушує владу ухвалювати рішення повільніше та більш виважено.',0),
('orlyk','critical',1,2,'Він дозволяє різним групам суспільства бути представленими та конкурувати за владу.',1),
('orlyk','critical',1,3,'Він гарантує, що в результаті конкуренції до влади прийде найкращий та найкомпетентніший лідер.',0),

-- orlyk — critical (2)
('orlyk','critical',2,1,'Поділ влади.',0),
('orlyk','critical',2,2,'Народовладдя.',0),
('orlyk','critical',2,3,'Підзвітність влади.',1),

-- orlyk — critical (3)
('orlyk','critical',3,1,'Влада зобов''язана правити виключно так, як накаже народ.',0),
('orlyk','critical',3,2,'Влада править лише за згодою громадян і може бути ними законно змінена.',1),
('orlyk','critical',3,3,'Усі ключові державні рішення мають ухвалюватися лише через всенародний референдум.',0);



INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('orlyk', 1, 'Я хочу докладати зусиль для змін навколо.'),
('orlyk', 2, 'Я готов[ий/а] домовлятися про правила, зручні для всіх, і дотримуватися їх.'),
('orlyk', 3, 'Мій голос важливий при прийнятті рішень.');


INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES
('orlyk', 1, 1, 'Коли кожен із козаків приєднувався до прийняття рішень'),
('orlyk', 1, 2, 'Коли Пилип Орлик у Конституції уклав певні правила для всіх, навіть для себе'),
('orlyk', 1, 3, 'Коли ознайомився з устроєм козаків і принципом народовладдя'),

('orlyk', 2, 1, 'Коли кожен із козаків приєднувався до прийняття рішень'),
('orlyk', 2, 2, 'Коли Пилип Орлик у Конституції уклав певні правила для всіх, навіть для себе'),
('orlyk', 2, 3, 'Коли ознайомився з устроєм козаків і принципом народовладдя'),

('orlyk', 3, 1, 'Коли кожен із козаків приєднувався до прийняття рішень'),
('orlyk', 3, 2, 'Коли Пилип Орлик у Конституції уклав певні правила для всіх, навіть для себе'),
('orlyk', 3, 3, 'Коли ознайомився з устроєм козаків і принципом народовладдя');

-- ------------- МІГРАЦІЯ 2.1 -------------- --

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- orlyk — knowladge
('orlyk','knowladge',4,'Який вид демократії відображає загальне голосування громадян (референдум)?'),
('orlyk','knowladge',5,'Таємне голосування потрібне передусім для:');

INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- orlyk — knowladge (4) !!
('orlyk','knowladge',4,1,'Пряма.',1),
('orlyk','knowladge',4,2,'Посередницька.',0),
('orlyk','knowladge',4,3,'Представницька.',0),

-- orlyk — knowladge (5) !!
('orlyk','knowladge',5,1,'Пришвидшення процедури підрахунку.',0),
('orlyk','knowladge',5,2,'Зменшення тиску на виборців.',1),
('orlyk','knowladge',5,3,'Підвищення явки на дільницях.',0);

-- ------------- КІНЕЦЬ МІГРАЦІЇ 2 -------------- --

-- ------------- МІГРАЦІЯ 3 -------------- --
-- ------------- МІГРАЦІЯ 3 -------------- --

-- ------------- УНР -------------- --
INSERT INTO `scenarios` (`slug`) VALUES
('unr');

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- unr — knowladge
('unr','knowladge',1,'Послідовність стадій конфлікту:'),
('unr','knowladge',2,'«Привід» — це'),
('unr','knowladge',3,'«Врегулювання» на відміну від «вирішення»'),

-- unr — critical
('unr','critical',1,'В якій парі спершу йде позиція, а потім – інтерес?'),
('unr','critical',2,'\"Домовилися: на уроці граємо у гру. Вам – цікаво, а вчителю — вища активність і кращі результати\". Це приклад:'),
('unr','critical',3,'Медіація — це коли посередник');

INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- unr — knowladge (1)
('unr','knowladge',1,1,'оцінка можливостей → інцидент → активні дії → вирішення',0),
('unr','knowladge',1,2,'накопичення суперечностей → оцінка можливостей → інцидент → активні дії',1),
('unr','knowladge',1,3,'інцидент → оцінка можливостей → активні дії → накопичення суперечностей',0),

-- unr — knowladge (2)
('unr','knowladge',2,1,'Перше усвідомлення того, що погляди різні',0),
('unr','knowladge',2,2,'Подія, яка запускає активні дії в конфлікті',1),
('unr','knowladge',2,3,'Підстава, щоб довести щось у суперечці',0),

-- unr — knowladge (3)
('unr','knowladge',3,1,'Ставить емоції вище змісту',0),
('unr','knowladge',3,2,'Задає правила співіснування попри збереження частини протиріч',1),
('unr','knowladge',3,3,'Ліквідує причину конфлікту і примирює сторони',0),

-- unr — critical (1)
('unr','critical',1,1,'Мені потрібна тиша під час іспиту, тому зачиніть двері.',0),
('unr','critical',1,2,'Я не впущу тебе, бо не хочу, щоб ти побачив мій безлад.',1),
('unr','critical',1,3,'Чергуйте за списком і, будь ласка, не міняйтеся місцями.',0),

-- unr — critical (2)
('unr','critical',2,1,'Компромісу',0),
('unr','critical',2,2,'Консенсусу',1),
('unr','critical',2,3,'Поступок',0),

-- unr — critical (3)
('unr','critical',3,1,'Ухвалює справедливе рішення замість сторін',0),
('unr','critical',3,2,'Веде діалог між сторонами, щоб вони ухвалили рішення',1),
('unr','critical',3,3,'Визначає, чия позиція сильніша, щоб закінчити суперечку',0);

INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('unr', 1, 'Я хотів би допомагати сторонам шукати примирення'),
('unr', 2, 'Я готов[ий/а] змінювати свою думку, якщо почую переконливі аргументи.'),
('unr', 3, 'Я вважаю за потрібне вислухати обидві сторони перед тим, як робити висновки.');


INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES
('unr', 1, 1, 'Те, як директористи та гетьман змогли закінчити сутичку, припинивши кровопролиття.'),
('unr', 1, 2, 'Те, як директористи погодилися на перемовини, почувши від мене переконливі докази.'),
('unr', 1, 3, 'Те, що Гетьман хотів лише найкращого для України, хоч директористи вважали інакше.'),

('unr', 2, 1, 'Те, як директористи та гетьман змогли закінчити сутичку, припинивши кровопролиття.'),
('unr', 2, 2, 'Те, як директористи погодилися на перемовини, почувши від мене переконливі докази.'),
('unr', 2, 3, 'Те, що Гетьман хотів лише найкращого для України, хоч директористи вважали інакше.'),

('unr', 3, 1, 'Те, як директористи та гетьман змогли закінчити сутичку, припинивши кровопролиття.'),
('unr', 3, 2, 'Те, як директористи погодилися на перемовини, почувши від мене переконливі докази.'),
('unr', 3, 3, 'Те, що Гетьман хотів лише найкращого для України, хоч директористи вважали інакше.');

-- ------------- КІНЕЦЬ МІГРАЦІЇ 3 -------------- --


-- ------------- МІГРАЦІЯ 4 -------------- --
-- ------------- МІГРАЦІЯ 4 -------------- --
CREATE TABLE `courses` (
  `id` SMALLINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `ects` FLOAT NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `template` VARCHAR(255) NOT NULL
);

CREATE TABLE `certificates` (
  `id` CHAR(5) PRIMARY KEY NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `course_id` SMALLINT DEFAULT NULL,
  `issued_at` timestamp NOT NULL DEFAULT (current_timestamp())
);

ALTER TABLE `certificates`
  ADD CONSTRAINT `fk_co_cert`
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);


INSERT INTO `courses` (`title`, `ects`, `url`) VALUES
  ('Застосування гри<br/>(базовий модуль)',0.1,'/pdf/program-1.pdf');
-- ------------- КІНЕЦЬ МІГРАЦІЇ 4 -------------- --

-- ------------- МІГРАЦІЯ 5 -------------- --
-- ------------- МІГРАЦІЯ 5 -------------- --
INSERT INTO `scenarios` (`slug`) VALUES
('khotyn');

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- khotyn — critical
('khotyn','critical',1,'Чому Річ Посполита одночасно оголошувала козаків «злочинцями» і регулярно наймала їх на службу?'),
('khotyn','critical',2,'У чому полягала головна стратегічна мета «героїчних походів» козаків на Чорному морі, окрім здобичі?'),
('khotyn','critical',3,'Яке політичне значення мав вступ Сагайдачного з усім військом до Київського братства у 1620 році?'),

-- khotyn — narratives
('khotyn','narratives',1,'Участь Петра Конашевича-Сагайдачного та козацького війська в поході на москву 1618 р. була:'),
('khotyn','narratives',2,'Однією з основних причин приєднання козацького війська до Хотинської битви була:');

INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- khotyn — critical (1)
('khotyn','critical',1,1,'Через спроби уряду задобрити козаків платнею та для уникнення повстань.',0),
('khotyn','critical',1,2,'Через бажання сейму таким чином зробити із них реєстрових козаків.',0),
('khotyn','critical',1,3,'Через перевагу військової потреби у козаках над бажанням їх контролювати.',1),

-- khotyn — critical (2)
('khotyn','critical',2,1,'Демонстрація переваги козацьких загонів над великими османськими військами.',0),
('khotyn','critical',2,2,'Послаблення тиску на кордони через удари по опорних пунктах ворога.',1),
('khotyn','critical',2,3,'Піднесення визвольних рухів поневолених народів у Османській імперії.',0),

-- khotyn — critical (3)
('khotyn','critical',3,1,'Надання козакам доступу до якісної освіти на рівні шляхти.',0),
('khotyn','critical',3,2,'Політичне об''єднання військової сили козацтва із захистом православ''я.',1),
('khotyn','critical',3,3,'Легалізацію війни з османами через релігійний статус самого братства.',0),

-- khotyn — narratives (1)
('khotyn','narratives',1,1,'Демонстрацією лояльності королю, щоб увійти в його оточення.',0),
('khotyn','narratives',1,2,'Політичним кроком, що супроводжувався вимогами до корони.',1),
('khotyn','narratives',1,3,'Способом отримати високу платню та трофеї для розвитку козацького війська.',0),

-- khotyn — narratives (2)
('khotyn','narratives',2,1,'Наказ короля, який прийняли, щоб послабити переслідування козацтва.',0),
('khotyn','narratives',2,2,'Боротьба проти спільного ворога.',1),
('khotyn','narratives',2,3,'Дуже вигідна платня та трофеї, необхідні для розвитку козацького війська.',0);

INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('khotyn', 1, 'Я відчуваю особисту відповідальність за збереження та підтримку культури, мови та спадщини свого народу.'),
('khotyn', 2, 'Я вважаю, що громадяни, об''єднавшись, здатні самостійно створити ефективні інституції.'),
('khotyn', 3, 'Я переконаний(а), що активна позиція кожного громадянина та його участь у спільній справі є вирішальними для перемоги.');

INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES
-- khotyn — survey 1
('khotyn', 1, 1, 'Вступ до Братства: Усвідомив(ла), що Сагайдачний разом з усім Військом Запорозьким демонстративно вступив до Київського братства, беручи культуру та освіту під збройний захист.'),
('khotyn', 1, 2, 'Заповіт гетьмана: Дізнався(лась), що смертельно поранений Сагайдачний заповів свої маєтності не родичам, а на освітні та церковні потреби, зокрема київському та львівському братствам.'),
('khotyn', 1, 3, 'Відновлення ієрархії: Зрозумів(ла), що Сагайдачний ризикував стосунками з королем, коли взяв під захист патріарха Феофана і сприяв відновленню православної ієрархії у 1620 році.'),

-- khotyn — survey 2
('khotyn', 2, 1, 'Потужність війська: Усвідомив(ла), що Запорозьке військо, попри заборони Сейму (який оголосив їх «злочинцями»), стало масовою й організованою силою, здатною себе захистити.'),
('khotyn', 2, 2, 'Центр самоорганізації: Зрозумів(ла), що Запорозька Січ була не просто військовим табором, а центром самоорганізації козацтва.'),
('khotyn', 2, 3, 'Братства: Побачив(ла), що київські та львівські братства були громадськими організаціями, які опікувалися освітою та вірою, а Сагайдачний та козаки їх підтримували.'),

-- khotyn — survey 3
('khotyn', 3, 1, 'Реформи Сагайдачного: Зрозумів(ла), що успіх козацтва був результатом реформ одного лідера, який проявив ініціативу та тисяч людей, які активно підтримали його починання.'),
('khotyn', 3, 2, 'Тактика під Хотином: Усвідомив(ла), що перемога під Хотином (1621) залежала від дій кожного козака, які в таборі стійко оборонялися, а вночі здійснювали контратаки.'),
('khotyn', 3, 3, 'Морські походи: Побачив(ла), що успіх «героїчних походів» залежав від злагоджених дій екіпажів десятків і сотень «чайок».');

-- ------------- КІНЕЦЬ МІГРАЦІЇ 5 -------------- --

-- ------------- МІГРАЦІЯ 6 -------------- --
-- ------------- МІГРАЦІЯ 6 -------------- --
INSERT INTO `scenarios` (`slug`) VALUES
('kyiv');

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- kyiv — critical
('kyiv','critical',1,'Чи є система ув’язнення шляхтича лише за рішенням шляхетського суду неоднозначною?'),
('kyiv','critical',2,'Що найкраще показує розвиток господарства на руських земелях у XV ст.?'),
('kyiv','critical',3,'Для чого майстри об’єднувалися у цехи?'),

-- kyiv — narratives (протидія наративам)
('kyiv','narratives',1,'Правове становище селян на теренах Русі у XV ст. передбачало:'),
('kyiv','narratives',2,'Яке становище православної церкви було на теренах Русі у XV ст.?'),
('kyiv','narratives',3,'Яку користь приносило Києву Магдебурзьке право?');

INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- kyiv — critical (1)
('kyiv','critical',1,1,'Так, бо привілеї можуть ускладнювати покарання небезпечної людини.',1),
('kyiv','critical',1,2,'Так, бо шляхтичі відповідають вищим стандартам, через що несуть вище покарання.',0),
('kyiv','critical',1,3,'Ні, бо поділ судів створений навмисно, щоб розглядати справи різної складності.',0),

-- kyiv — critical (2)
('kyiv','critical',2,1,'Вирощування технічних культур та продаж продукції на зовнішні ринки.',1),
('kyiv','critical',2,2,'Те, що селяни самоорганізовувалися у фільварки і могли самі заробляти кошти.',0),
('kyiv','critical',2,3,'Практики закріпачення, що давали новий приріст ефективності праці.',0),

-- kyiv — critical (3)
('kyiv','critical',3,1,'Це була вимога місцевої влади для наведення порядку.',0),
('kyiv','critical',3,2,'Цехи дозволяли узгоджувати якість та ціни виробів.',1),
('kyiv','critical',3,3,'Участь у цеху звільняла від військової служби і частини податків.',0),

-- kyiv — narratives (1)
('kyiv','narratives',1,1,'Фактичне рабство для всього сільського населення.',0),
('kyiv','narratives',1,2,'Вільну можливість піти від пана і укладати угоди з новим для частини селян.',1),
('kyiv','narratives',1,3,'Обов''язкову службу у шляхетському війську разом зі сплатою податків.',0),

-- kyiv — narratives (2)
('kyiv','narratives',2,1,'Заборона будувати православні храми й проводити богослужіння.',0),
('kyiv','narratives',2,2,'Можливість вести традиційні богослужіння за умови підпорядкування Папі Римському.',0),
('kyiv','narratives',2,3,'Можливість вільно зводити церкви, проводити служби й мати свою церковну ієрархію.',1),

-- kyiv — narratives (3)
('kyiv','narratives',3,1,'Дозволяло збагачення поміщиків завдяки вільній економічній зоні',0),
('kyiv','narratives',3,2,'Дозволяло власний суд, міське самоврядування і господарювання',1),
('kyiv','narratives',3,3,'Дозволяло мати окреме військо для оборони міста',0);

INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('kyiv', 1, 'Я вважаю, що місцева влада повинна мати власні повноваження і бути менш залежною від центральної влади.'),
('kyiv', 2, 'Я вважаю, що справедливість можлива лише тоді, коли суд є незалежним від влади.'),
('kyiv', 3, 'Я вважаю, що звичайні мешканці теж відповідають за безпеку громади, а не тільки воїни і правителі.');

INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES
-- kyiv — survey 1
('kyiv', 1, 1, 'Те, що міста з самоврядуванням розвивалися значно швидше.'),
('kyiv', 1, 2, 'Те, що місцева влада змогла швидше зреагувати на загрозу шпигуна.'),
('kyiv', 1, 3, 'Те, що війт краще розумів потреби жителів, ніж віддалені від них володарі.'),

-- kyiv — survey 2
('kyiv', 2, 1, 'Те, що пан міг одноосібно судити своїх селян, часто упереджено.'),
('kyiv', 2, 2, 'Те, що шляхетський суд міг виправдати навіть шпигуна як "одного зі своїх".'),
('kyiv', 2, 3, 'Те, що в міста добивалися свого окремого суду – Лави, щоб уникнути зловживань.'),

-- kyiv — survey 3
('kyiv', 3, 1, 'Те, що саме селянин помітив закопану бочку з порохом та попередив загрозу.'),
('kyiv', 3, 2, 'Те, що продавчиня, монах, міщанин і ремісник разом допомогли викрити шпигуна.'),
('kyiv', 3, 3, 'Те, що я із мешканцями зміг затримати шпигуна та врятувати ярмарок.');

-- ------------- КІНЕЦЬ МІГРАЦІЇ 6 -------------- --

-- ------------- МІГРАЦІЯ 7 -------------- --
-- ------------- МІГРАЦІЯ 7 -------------- --
ALTER TABLE `users`
  ADD COLUMN `name` VARCHAR(50) NOT NULL DEFAULT '' AFTER `code`;
-- ------------- КІНЕЦЬ МІГРАЦІЇ 7 -------------- --

-- ------------- МІГРАЦІЯ 8 -------------- --
-- ------------- МІГРАЦІЯ 8 -------------- --

INSERT INTO `scenarios` (`slug`) VALUES
('lesya');

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- lesya — knowladge
('lesya','knowladge',1,'Первинні агенти соціалізації – це'),
('lesya','knowladge',2,'Коли відбувається процес соціалізації (засвоєння культури та норм суспільства)?'),
('lesya','knowladge',3,'Чим визначається групова ідентичність?'),

-- lesya — critical
('lesya','critical',1,'Твердження «я такий, як є, і змінитися вже не можу» – помилка, бо ідентичність:'),
('lesya','critical',2,'За пірамідою Маслоу, шлях до самореалізації блокується, якщо:'),
('lesya','critical',3,'Ключова відмінність між формальним громадянством та громадянською ідентичністю у:');

INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- lesya — knowladge (1)
('lesya','knowladge',1,1,'Школа, робота, організації.',0),
('lesya','knowladge',1,2,'Друзі, вчителі, ЗМІ.',0),
('lesya','knowladge',1,3,'Сім’я, лише найближчі друзі.',1),

-- lesya — knowladge (2)
('lesya','knowladge',2,1,'Переважно в дитинстві та підлітковому віці.',0),
('lesya','knowladge',2,2,'Від народження та протягом усього життя.',1),
('lesya','knowladge',2,3,'Під час взаємодії з людьми поза домом.',0),

-- lesya — knowladge (3)
('lesya','knowladge',3,1,'Особистими рисами (характер, хобі, таланти).',0),
('lesya','knowladge',3,2,'Тим, що відрізняє від інших (звички, стиль).',0),
('lesya','knowladge',3,3,'Тим, що об’єднує з іншими (спільнота, професія).',1),

-- lesya — critical (1)
('lesya','critical',1,1,'Формується та змінюється протягом усього життя.',1),
('lesya','critical',1,2,'Остаточно формується у підлітковому віці.',0),
('lesya','critical',1,3,'Визначається генетикою, тому менше залежить від оточення.',0),

-- lesya — critical (2)
('lesya','critical',2,1,'Немає відповідних талантів для цього.',0),
('lesya','critical',2,2,'Не закриті потреби нижчих рівнів.',1),
('lesya','critical',2,3,'Немає визнання та суспільного статусу.',0),

-- lesya — critical (3)
('lesya','critical',3,1,'Наявності юридичного статусу громадянина.',0),
('lesya','critical',3,2,'Спільному етносі та мові з іншими громадянами держави.',0),
('lesya','critical',3,3,'Власному відчутті належності до народу і держави.',1);

INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('lesya', 1, 'Я думаю, що сила громади – у самоорганізації через спільноти.'),
('lesya', 2, 'Я думаю, що за спільну справу відповідає кожен учасник, а не лише лідери.'),
('lesya', 3, 'Я думаю, що поширення знань і правди — це те, що змінює суспільство.');

INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES
('lesya', 1, 1, 'Те, як герої змогли триматися разом у «Плеяді» та зберігати українську культуру.'),
('lesya', 1, 2, 'Те, що при загрозі герої обирають триматися гуртом і збираються у домі Косачів.'),
('lesya', 1, 3, 'Те, як «Плеяда» об’єднала людей із різними вміннями, щоб досягти спільної мети.'),

('lesya', 2, 1, 'Те, що я зміг допомогти «Плеяді», щойно долучившись до неї.'),
('lesya', 2, 2, 'Те, що кожен робить свій внесок: від розмов і планування до втілення.'),
('lesya', 2, 3, 'Те, що спільна мета тримається на дисципліні всіх, а не на одній людині.'),

('lesya', 3, 1, 'Те, як твори героїнь досягають читачів, щоб ті «не мовчали» і думали самі.'),
('lesya', 3, 2, 'Те, що «Плеяда» об’єднується навколо ідей і текстів, які формують свідомість.'),
('lesya', 3, 3, 'Те, що твори героїнь стають інструментом впливу, навіть без зброї.');

-- ------------- КІНЕЦЬ МІГРАЦІЇ 8 -------------- --

-- ------------- МІГРАЦІЯ 9 -------------- --
-- ------------- МІГРАЦІЯ 9 -------------- --

INSERT INTO `scenarios` (`slug`) VALUES
('plast');

INSERT INTO `tests` (`scenario_slug`, `metric`, `number`, `question_text`) VALUES
-- plast — knowladge
('plast','knowladge',1,'Головна мета створення громадської організації – це:'),
('plast','knowladge',2,'Що таке Статут громадської організації?'),
('plast','knowladge',3,'Які 4 принципи громадської організації?'),

-- plast — critical
('plast','critical',1,'Приклад низької громадянської участі в організації:'),
('plast','critical',2,'Який з цих прикладів порушує принцип відкритості громадської організації (ГО)?'),
('plast','critical',3,'Якщо волонтерство – це допомога через самостійні дії, то активізм –');

INSERT INTO `test_opts` (`scenario_slug`,`metric`,`test_n`,`number`,`option_text`,`correct`) VALUES
-- plast — knowladge (1)
('plast','knowladge',1,1,'Об’єднання групи населення за власними інтересами.',0),
('plast','knowladge',1,2,'Боротьба за владу та участь у державних виборах.',0),
('plast','knowladge',1,3,'Захист суспільних інтересів та вирішення проблем.',1),

-- plast — knowladge (2)
('plast','knowladge',2,1,'Документ, з юридичною фіксацією учасників об’єднання.',0),
('plast','knowladge',2,2,'Документ з метою та внутрішніми правилами.',1),
('plast','knowladge',2,3,'Державний документ, який отримує організація при реєстрації.',0),

-- plast — knowladge (3)
('plast','knowladge',3,1,'добровільність, самоуправність, неприбутковість, відкритість.',1),
('plast','knowladge',3,2,'солідарність, підзвітність, верховенство права, повага до різноманіття.',0),
('plast','knowladge',3,3,'добровільність, прозорість, відповідальність, підзвітність.',0),

-- plast — critical (1)
('plast','critical',1,1,'Керівник сам приймає рішення, а інші втілюють його.',1),
('plast','critical',1,2,'Учасники організації пропонують ідеї без згоди керівника.',0),
('plast','critical',1,3,'Учасники не можуть дійти між собою згоди.',0),

-- plast — critical (2)
('plast','critical',2,1,'Учасники таємно радяться без відома керівника.',0),
('plast','critical',2,2,'Учасник не може за власним бажанням доєднатись чи вийти з ГО.',1),
('plast','critical',2,3,'ГО приховує частину діяльності від суспільства.',0),

-- plast — critical (3)
('plast','critical',3,1,'Вплив на рішення влади або ставлення громадян до проблеми',1),
('plast','critical',3,2,'Допомога через громадські організації без впливу на владу',0),
('plast','critical',3,3,'Вплив через регулярну підтримку громадян та задоволення їхніх потреб',0);

INSERT INTO `surveys` (`scenario_slug`, `sur_n`, `question_text`) VALUES
('plast', 1, 'Я думаю, що мала організована група здатна вирішувати великі проблеми.'),
('plast', 2, 'Я думаю, що за спільну справу відповідає кожен учасник, а не лише лідери.'),
('plast', 3, 'Я думаю, що ще надто молод[ий/а], щоб робити зміни довкола.');

INSERT INTO `reasons` (`scenario_slug`, `sur_n`, `number`, `option_text`) VALUES
('plast', 1, 1, 'Те, що українські ГО змогли зберегти ідентичність у чужій імперії.'),
('plast', 1, 2, 'Те, як «Пласт» самоорганізовував молодь та навіть дітей.'),
('plast', 1, 3, 'Те, що я сам організував захід разом з командою.'),

('plast', 2, 1, 'Те, що різні учасники «Пласту» брали участь у його становленні.'),
('plast', 2, 2, 'Те, що Тисовський і Франко стали важливою частиною мого заходу.'),
('plast', 2, 3, 'Те, що у «Пласті» молодь та навіть діти проявляли ініціативу.'),

('plast', 3, 1, 'Те, що в «Пласті» навіть діти проявляли ініціативу.'),
('plast', 3, 2, 'Побачив, що організувати захід не так і складно.'),
('plast', 3, 3, 'Побачив, як можна самотужки впливати на рішення влади.');

-- ------------- КІНЕЦЬ МІГРАЦІЇ 9 -------------- --

-- ------------- МІГРАЦІЯ 10 -------------- --
-- ------------- МІГРАЦІЯ 10 -------------- --
INSERT INTO `games` (`code`, `scenario_slug`) VALUES 
('danylo', 'danylo'),
('vitovt', 'vitovt'),
('orsha', 'orsha'),
('orlyk', 'orlyk'),
('unr', 'unr'),
('khotyn', 'khotyn'),
('kyiv', 'kyiv'),
('lesya', 'lesya'),
('plast', 'plast');
-- ------------- КІНЕЦЬ МІГРАЦІЇ 10 -------------- --