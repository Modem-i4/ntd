<?php
class StatsController {
    public function __construct(private PDO $pdo) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function index(): array {
        try {
            $testsByMetric = $this->pdo->query("
                WITH base_candidates AS (
                    SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                           CASE WHEN topt.correct=1 THEN 1.0 ELSE 0.0 END AS pre_raw,
                           ROW_NUMBER() OVER (
                               PARTITION BY s.user_code, at.scenario_slug, at.metric, at.test_n
                               ORDER BY s.started_at, at.id
                           ) AS rn
                    FROM ans_test at
                    JOIN sessions s ON s.id=at.session_id
                    JOIN test_opts topt
                      ON topt.scenario_slug=at.scenario_slug
                     AND topt.metric=at.metric
                     AND topt.test_n=at.test_n
                     AND topt.number=at.first_option_n
                ),
                baseline AS (
                    SELECT user_code, scenario_slug, metric, test_n, pre_raw
                    FROM base_candidates
                    WHERE rn=1
                ),
                current_scored AS (
                    SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                           CASE WHEN topt.correct=1 THEN 1.0 ELSE 0.0 END AS post_raw
                    FROM ans_test at
                    JOIN sessions s ON s.id=at.session_id
                    JOIN test_opts topt
                      ON topt.scenario_slug=at.scenario_slug
                     AND topt.metric=at.metric
                     AND topt.test_n=at.test_n
                     AND topt.number=at.final_option_n
                    WHERE at.final_option_n IS NOT NULL
                ),
                joined AS (
                    SELECT cs.metric,
                           cs.user_code,
                           ((3.0*b.pre_raw-1.0)/2.0)  AS pre_corr_item,
                           ((3.0*cs.post_raw-1.0)/2.0) AS post_corr_item,
                           b.pre_raw  AS pre_raw,
                           cs.post_raw AS post_raw
                    FROM current_scored cs
                    JOIN baseline b
                      ON b.user_code=cs.user_code
                     AND b.scenario_slug=cs.scenario_slug
                     AND b.metric=cs.metric
                     AND b.test_n=cs.test_n
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
            ")->fetchAll();

            $surveys = $this->pdo->query("
                WITH pre AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_before AS B0,
                           ROW_NUMBER() OVER (
                             PARTITION BY s.user_code, sa.scenario_slug, sa.sur_n
                             ORDER BY s.started_at, sa.id
                           ) AS rn
                    FROM ans_sur sa
                    JOIN sessions s ON s.id=sa.session_id
                    WHERE sa.val_before IS NOT NULL
                ),
                baseline_sa AS (
                    SELECT user_code, scenario_slug, sur_n, B0
                    FROM pre
                    WHERE rn=1
                ),
                current_sa AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_after AS AfterVal
                    FROM ans_sur sa
                    JOIN sessions s ON s.id=sa.session_id
                    WHERE sa.val_after IS NOT NULL
                ),
                joined AS (
                    SELECT c.user_code, c.scenario_slug, c.sur_n,
                           b.B0 AS pre_val, c.AfterVal AS post_val, (c.AfterVal-b.B0) AS improvement
                    FROM current_sa c
                    JOIN baseline_sa b
                      ON b.user_code=c.user_code
                     AND b.scenario_slug=c.scenario_slug
                     AND b.sur_n=c.sur_n
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
            ")->fetchAll();

            $surveysOverall = $this->pdo->query("
                WITH pre AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_before AS B0,
                           ROW_NUMBER() OVER (
                             PARTITION BY s.user_code, sa.scenario_slug, sa.sur_n
                             ORDER BY s.started_at, sa.id
                           ) AS rn
                    FROM ans_sur sa
                    JOIN sessions s ON s.id=sa.session_id
                    WHERE sa.val_before IS NOT NULL
                ),
                baseline_sa AS (
                    SELECT user_code, scenario_slug, sur_n, B0
                    FROM pre
                    WHERE rn=1
                ),
                current_sa AS (
                    SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_after AS AfterVal
                    FROM ans_sur sa
                    JOIN sessions s ON s.id=sa.session_id
                    WHERE sa.val_after IS NOT NULL
                ),
                joined AS (
                    SELECT b.B0 AS pre_val, c.AfterVal AS post_val, (c.AfterVal-b.B0) AS improvement, c.user_code
                    FROM current_sa c
                    JOIN baseline_sa b
                      ON b.user_code=c.user_code
                     AND b.scenario_slug=c.scenario_slug
                     AND b.sur_n=c.sur_n
                )
                SELECT AVG(pre_val) AS pre,
                       AVG(post_val) AS post,
                       AVG(improvement) AS avg_change,
                       COUNT(*) AS answers_count,
                       COUNT(DISTINCT user_code) AS users_count
                FROM joined
            ")->fetch();

            return ['ok'=>true,'tests_by_metric'=>$testsByMetric,'surveys'=>$surveys,'surveys_overall'=>$surveysOverall];
        } catch (Throwable $e) {
            return ['ok'=>false,'error'=>'stats failed','detail'=>$e->getMessage()];
        }
    }
}
