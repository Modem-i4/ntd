<?php

class ScenarioStatsController
{
    private array $scenarioSlugs;

    public function __construct(private PDO $pdo, array $scenarioSlugs)
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $this->scenarioSlugs = array_values(array_unique($scenarioSlugs));
    }

    private function buildScenarioInClause(string $prefix): array
    {
        if (empty($this->scenarioSlugs)) {
            return ['NULL', []];
        }

        $placeholders = [];
        $params = [];

        foreach ($this->scenarioSlugs as $i => $slug) {
            $name = $prefix . $i;
            $placeholders[] = $name;
            $params[$name] = $slug;
        }

        return [implode(',', $placeholders), $params];
    }

    public function index(): array
    {
        return [
            'ok' => true,
            'numeric' => $this->numeric(),
            'improvement' => $this->improvement(),
        ];
    }

    public function numeric(): array
    {
        try {
            [$in, $params] = $this->buildScenarioInClause(':sn_');

            $stmt = $this->pdo->prepare("
                SELECT COUNT(DISTINCT s.user_code)
                FROM sessions s
                JOIN games g ON g.code = s.game_code
                WHERE g.scenario_slug IN ($in)
            ");
            $stmt->execute($params);
            $uniquePlayers = (int)$stmt->fetchColumn();

            $stmt = $this->pdo->prepare("
                SELECT COUNT(DISTINCT s.user_code)
                FROM sessions s
                JOIN games g ON g.code = s.game_code
                WHERE g.scenario_slug IN ($in)
                  AND s.ended_at IS NOT NULL
            ");
            $stmt->execute($params);
            $uniquePlayersCompleted = (int)$stmt->fetchColumn();

            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM (
                    SELECT s.game_code
                    FROM sessions s
                    JOIN games g ON g.code = s.game_code
                    WHERE g.scenario_slug IN ($in)
                    GROUP BY s.game_code
                    HAVING COUNT(DISTINCT s.user_code) > 1
                ) t
            ");
            $stmt->execute($params);
            $lessons = (int)$stmt->fetchColumn();

            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM sessions s
                JOIN games g ON g.code = s.game_code
                WHERE g.scenario_slug IN ($in)
                  AND (s.score > 0 OR s.ended_at IS NOT NULL)
            ");
            $stmt->execute($params);
            $sessions = (int)$stmt->fetchColumn();

            [$inInner, $paramsInner] = $this->buildScenarioInClause(':st_in_');
            [$inOuter, $paramsOuter] = $this->buildScenarioInClause(':st_out_');

            $stmt = $this->pdo->prepare("
                SELECT COUNT(DISTINCT g.teacher_code)
                FROM games g
                JOIN (
                    SELECT s.game_code
                    FROM sessions s
                    JOIN games g2 ON g2.code = s.game_code
                    WHERE g2.scenario_slug IN ($inInner)
                    GROUP BY s.game_code
                    HAVING COUNT(DISTINCT s.user_code) > 1
                ) s ON s.game_code = g.code
                WHERE g.teacher_code IS NOT NULL
                  AND g.scenario_slug IN ($inOuter)
            ");
            $stmt->execute($paramsInner + $paramsOuter);
            $uniqueTeachers = (int)$stmt->fetchColumn();

            return [
                'ok' => true,
                'uniquePlayers' => $uniquePlayers,
                'uniquePlayersCompleted' => $uniquePlayersCompleted,
                'lessons' => $lessons,
                'sessions' => $sessions,
                'uniqueTeachers' => $uniqueTeachers,
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'error' => 'stats failed', 'detail' => $e->getMessage()];
        }
    }

    public function improvement(): array
    {
        try {
            [$testsIn1, $testsParams1] = $this->buildScenarioInClause(':t1_');
            [$testsIn2, $testsParams2] = $this->buildScenarioInClause(':t2_');
            $testsParams = $testsParams1 + $testsParams2;

            $stmt = $this->pdo->prepare("
                WITH base_candidates AS (
                    SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                           CASE WHEN topt.correct = 1 THEN 1.0 ELSE 0.0 END AS pre_raw,
                           ROW_NUMBER() OVER (
                               PARTITION BY s.user_code, at.scenario_slug, at.metric, at.test_n
                               ORDER BY s.started_at, at.id
                           ) AS rn
                    FROM ans_test at
                    JOIN sessions s ON s.id = at.session_id
                    JOIN test_opts topt
                      ON topt.scenario_slug = at.scenario_slug
                     AND topt.metric = at.metric
                     AND topt.test_n = at.test_n
                     AND topt.number = at.first_option_n
                    WHERE at.scenario_slug IN ($testsIn1)
                ),
                baseline AS (
                    SELECT user_code, scenario_slug, metric, test_n, pre_raw
                    FROM base_candidates
                    WHERE rn = 1
                ),
                current_scored AS (
                    SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                           CASE WHEN topt.correct = 1 THEN 1.0 ELSE 0.0 END AS post_raw
                    FROM ans_test at
                    JOIN sessions s ON s.id = at.session_id
                    JOIN test_opts topt
                      ON topt.scenario_slug = at.scenario_slug
                     AND topt.metric = at.metric
                     AND topt.test_n = at.test_n
                     AND topt.number = at.final_option_n
                    WHERE at.final_option_n IS NOT NULL
                      AND at.scenario_slug IN ($testsIn2)
                ),
                joined AS (
                    SELECT cs.metric,
                           cs.user_code,
                           ((3.0 * b.pre_raw - 1.0) / 2.0)  AS pre_corr_item,
                           ((3.0 * cs.post_raw - 1.0) / 2.0) AS post_corr_item,
                           b.pre_raw   AS pre_raw,
                           cs.post_raw AS post_raw
                    FROM current_scored cs
                    JOIN baseline b
                      ON b.user_code = cs.user_code
                     AND b.scenario_slug = cs.scenario_slug
                     AND b.metric = cs.metric
                     AND b.test_n = cs.test_n
                )
                SELECT j.metric,
                       GREATEST(0.0, AVG(j.pre_corr_item))  AS pre,
                       GREATEST(0.0, AVG(j.post_corr_item)) AS post,
                       (GREATEST(0.0, AVG(j.post_corr_item)) - GREATEST(0.0, AVG(j.pre_corr_item))) AS improvement,
                       AVG(j.pre_raw)  AS pre_raw,
                       AVG(j.post_raw) AS post_raw,
                       COUNT(*) AS answers_count,
                       COUNT(DISTINCT j.user_code) AS users_count
                FROM joined j
                GROUP BY j.metric
                ORDER BY j.metric
            ");
            $stmt->execute($testsParams);
            $testsRows = $stmt->fetchAll();

            $testsByMetric = [];
            foreach ($testsRows as $row) {
                $metric = $row['metric'];
                unset($row['metric']);
                $testsByMetric[$metric] = $row;
            }

            [$surIn1, $surParams1] = $this->buildScenarioInClause(':s1_');
            [$surIn2, $surParams2] = $this->buildScenarioInClause(':s2_');
            $surParams = $surParams1 + $surParams2;

            $stmt = $this->pdo->prepare("
                WITH pre AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_before AS B0,
                           ROW_NUMBER() OVER (
                             PARTITION BY s.user_code, sa.scenario_slug, sa.sur_n
                             ORDER BY s.started_at, sa.id
                           ) AS rn
                    FROM ans_sur sa
                    JOIN sessions s ON s.id = sa.session_id
                    WHERE sa.val_before IS NOT NULL
                      AND sa.scenario_slug IN ($surIn1)
                ),
                baseline_sa AS (
                    SELECT user_code, scenario_slug, sur_n, B0
                    FROM pre
                    WHERE rn = 1
                ),
                current_sa AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_after AS AfterVal
                    FROM ans_sur sa
                    JOIN sessions s ON s.id = sa.session_id
                    WHERE sa.val_after IS NOT NULL
                      AND sa.scenario_slug IN ($surIn2)
                ),
                joined AS (
                    SELECT c.user_code, c.scenario_slug, c.sur_n,
                           b.B0 AS pre_val, c.AfterVal AS post_val, (c.AfterVal - b.B0) AS improvement
                    FROM current_sa c
                    JOIN baseline_sa b
                      ON b.user_code = c.user_code
                     AND b.scenario_slug = c.scenario_slug
                     AND b.sur_n = c.sur_n
                )
                SELECT scenario_slug, sur_n,
                       AVG(pre_val)  AS pre,
                       AVG(post_val) AS post,
                       AVG(improvement) AS avg_change,
                       COUNT(*) AS answers_count,
                       COUNT(DISTINCT user_code) AS users_count
                FROM joined
                GROUP BY scenario_slug, sur_n
                ORDER BY scenario_slug, sur_n
            ");
            $stmt->execute($surParams);
            $surveys = $stmt->fetchAll();

            [$surAllIn1, $surAllParams1] = $this->buildScenarioInClause(':sa1_');
            [$surAllIn2, $surAllParams2] = $this->buildScenarioInClause(':sa2_');
            $surAllParams = $surAllParams1 + $surAllParams2;

            $stmt = $this->pdo->prepare("
                WITH pre AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_before AS B0,
                           ROW_NUMBER() OVER (
                             PARTITION BY s.user_code, sa.scenario_slug, sa.sur_n
                             ORDER BY s.started_at, sa.id
                           ) AS rn
                    FROM ans_sur sa
                    JOIN sessions s ON s.id = sa.session_id
                    WHERE sa.val_before IS NOT NULL
                      AND sa.scenario_slug IN ($surAllIn1)
                ),
                baseline_sa AS (
                    SELECT user_code, scenario_slug, sur_n, B0
                    FROM pre
                    WHERE rn = 1
                ),
                current_sa AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_after AS AfterVal
                    FROM ans_sur sa
                    JOIN sessions s ON s.id = sa.session_id
                    WHERE sa.val_after IS NOT NULL
                      AND sa.scenario_slug IN ($surAllIn2)
                ),
                joined AS (
                    SELECT b.B0 AS pre_val, c.AfterVal AS post_val, (c.AfterVal - b.B0) AS improvement, c.user_code
                    FROM current_sa c
                    JOIN baseline_sa b
                      ON b.user_code = c.user_code
                     AND b.scenario_slug = c.scenario_slug
                     AND b.sur_n = c.sur_n
                )
                SELECT AVG(pre_val) AS pre,
                       AVG(post_val) AS post,
                       AVG(improvement) AS avg_change,
                       COUNT(*) AS answers_count,
                       COUNT(DISTINCT user_code) AS users_count
                FROM joined
            ");
            $stmt->execute($surAllParams);
            $surveysOverall = $stmt->fetch();

            return [
                'ok' => true,
                'tests' => $testsByMetric,
                'surveys' => $surveysOverall,
            ];
        } catch (Throwable $e) {
            return ['ok' => false, 'error' => 'stats failed', 'detail' => $e->getMessage()];
        }
    }
}
