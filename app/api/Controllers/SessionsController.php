<?php
class SessionsController {
    public function __construct(private PDO $pdo) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    private function in(): array { $raw=file_get_contents('php://input')?:''; $j=json_decode($raw,true); return is_array($j)?$j:[]; }
    private function s($v): string { return strtolower(trim((string)$v)); }
    private function n($v): ?int { return is_numeric($v)?(int)$v:null; }
    private function ts($v): ?string { if($v===null||$v==='')return null; if(is_numeric($v)){ $x=(int)$v; if($x>20000000000)$x=intdiv($x,1000); return date('Y-m-d H:i:s',$x);} $s=trim((string)$v); return $s!==''?$s:null; }
    private function one(string $sql,array $p):?array{ $st=$this->pdo->prepare($sql); $st->execute($p); $r=$st->fetch(); return $r?:null; }
    private function exists(string $sql,array $p):bool{ $st=$this->pdo->prepare($sql); $st->execute($p); return (bool)$st->fetchColumn(); }

    private function ensureUser(string $code): void {
        if(!$this->exists("SELECT 1 FROM users WHERE code=?",[$code])){
            $this->pdo->prepare("INSERT INTO users(code) VALUES(?)")->execute([$code]);
        }
    }

    private function ensureSession(array $sessIn): array {
        $sid=$this->n($sessIn['id']??null);
        if($sid){
            $row=$this->one("SELECT id,user_code,scenario_slug FROM sessions WHERE id=?",[$sid]);
            if($row){
                $upd=[];$vals=[];
                foreach(['started_at','game_started_at','game_ended_at','ended_at'] as $k){
                    $v=$this->ts($sessIn[$k]??null);
                    if($v!==null){ $upd[]="$k=?"; $vals[]=$v; }
                }
                if($upd){ $vals[]=$sid; $this->pdo->prepare("UPDATE sessions SET ".implode(',',$upd)." WHERE id=?")->execute($vals); }
                return $row;
            }
        }
        $user=trim((string)($sessIn['user_code']??'')); $sc=$this->s($sessIn['scenario_slug']??'');
        if(!preg_match('/^\d{6}$/',$user) || $sc==='') throw new RuntimeException('need session.user_code(6) and session.scenario_slug');
        if(!$this->exists("SELECT 1 FROM scenarios WHERE slug=?",[$sc])) throw new RuntimeException('scenario_slug not found');
        $this->ensureUser($user);
        $this->pdo->prepare("INSERT INTO sessions(user_code,scenario_slug,started_at,game_started_at,game_ended_at,ended_at) VALUES(?,?,?,?,?,?)")
            ->execute([$user,$sc,$this->ts($sessIn['started_at']??null),$this->ts($sessIn['game_started_at']??null),$this->ts($sessIn['game_ended_at']??null),$this->ts($sessIn['ended_at']??null)]);
        $sid=(int)$this->pdo->lastInsertId();
        return ['id'=>$sid,'user_code'=>$user,'scenario_slug'=>$sc];
    }

    private function isOpt(string $scenario,string $metric,int $testN,int $optN):bool{
        return $this->exists("SELECT 1 FROM test_opts WHERE scenario_slug=? AND metric=? AND test_n=? AND number=?",[$scenario,$metric,$testN,$optN]);
    }
    private function correct(string $scenario,string $metric,int $testN,int $optN):bool{
        $r=$this->one("SELECT correct FROM test_opts WHERE scenario_slug=? AND metric=? AND test_n=? AND number=?",[$scenario,$metric,$testN,$optN]);
        return $r?((int)$r['correct']===1):false;
    }
    private function calcDelta(string $scenario,string $metric,int $testN,?int $first,?int $final):?int{
        if(!$first||!$final)return null; $a=$this->correct($scenario,$metric,$testN,$first); $b=$this->correct($scenario,$metric,$testN,$final); if($b&&!$a)return 1; if(!$b&&$a)return -1; return 0;
    }

    public function record(): array {
        $in=$this->in(); $sess=(array)($in['session']??[]); $tests=is_array($in['tests']??null)?$in['tests']:[]; $surveys=is_array($in['surveys']??null)?$in['surveys']:[];
        try{
            $this->pdo->beginTransaction();
            $S=$this->ensureSession($sess); $sid=(int)$S['id']; $scenario=$S['scenario_slug'];

            $savedTests=0;
            foreach($tests as $t){
                if(!is_array($t))continue;
                $metric=$this->s($t['metric']??''); $testN=$this->n($t['test_n']??null);
                $first=$this->n($t['first_option_n']??null); $final=$this->n($t['final_option_n']??null);
                if($metric===''||!$testN||(!$first&&!$final))continue;
                if(!$this->exists("SELECT 1 FROM tests WHERE scenario_slug=? AND metric=? AND number=?",[$scenario,$metric,$testN]))continue;
                if($first&&!$this->isOpt($scenario,$metric,$testN,$first))$first=null;
                if($final&&!$this->isOpt($scenario,$metric,$testN,$final))$final=null;
                if(!$first&&!$final)continue;

                $row=$this->one("SELECT id,first_option_n,final_option_n FROM ans_test WHERE session_id=? AND scenario_slug=? AND metric=? AND test_n=?",[$sid,$scenario,$metric,$testN]);
                if($row){
                    $f1=$row['first_option_n']??null; $f2=$row['final_option_n']??null;
                    $f1=$first??$f1; $f2=$final??$f2;
                    $delta=$this->calcDelta($scenario,$metric,$testN,$f1,$f2);
                    $this->pdo->prepare("UPDATE ans_test SET first_option_n=?, final_option_n=?, delta=?, created_at=CURRENT_TIMESTAMP WHERE id=?")->execute([$f1,$f2,$delta,$row['id']]);
                }else{
                    if(!$first)continue;
                    $delta=$final?$this->calcDelta($scenario,$metric,$testN,$first,$final):null;
                    $this->pdo->prepare("INSERT INTO ans_test(session_id,scenario_slug,metric,test_n,first_option_n,final_option_n,delta,created_at) VALUES(?,?,?,?,?,?,?,CURRENT_TIMESTAMP)")
                        ->execute([$sid,$scenario,$metric,$testN,$first,$final,$delta]);
                }
                $savedTests++;
            }

            $rng=function($v){ $n=$this->n($v); return ($n && $n>=1 && $n<=10)?$n:null; };
            $savedSurveys=0;
            foreach($surveys as $s){
                if(!is_array($s)) continue;
                $surN=$this->n($s['sur_n']??null);
                if(!$surN) continue;
                if(!$this->exists("SELECT 1 FROM surveys WHERE scenario_slug=? AND sur_n=?",[$scenario,$surN])) continue;

                $reasonN=$this->n($s['reason_n']??null);
                if($reasonN!==null && !$this->exists("SELECT 1 FROM reasons WHERE scenario_slug=? AND sur_n=? AND number=?",[$scenario,$surN,$reasonN])) $reasonN=null;

                $vb=$rng($s['val_before']??($s['val_before']??null));
                $va=$rng($s['val_after']??($s['val_after']??null));

                $row=$this->one("SELECT id FROM ans_sur WHERE session_id=? AND scenario_slug=? AND sur_n=? LIMIT 1",[$sid,$scenario,$surN]);
                if($row){
                    $this->pdo->prepare("UPDATE ans_sur SET reason_n=COALESCE(?,reason_n), val_before=COALESCE(?,val_before), val_after=COALESCE(?,val_after), created_at=CURRENT_TIMESTAMP WHERE id=?")->execute([$reasonN,$vb,$va,$row['id']]);
                }else{
                    $this->pdo->prepare("INSERT INTO ans_sur(session_id,scenario_slug,sur_n,reason_n,val_before,val_after,created_at) VALUES(?,?,?,?,?,?,CURRENT_TIMESTAMP)")->execute([$sid,$scenario,$surN,$reasonN,$vb,$va]);
                }
                $savedSurveys++;
            }

                
            $this->pdo->commit();
            return ['ok'=>true,'session'=>['id'=>$sid,'user_code'=>$S['user_code'],'scenario_slug'=>$scenario],'saved'=>['tests'=>$savedTests,'surveys'=>$savedSurveys]];
        }catch(Throwable $e){
            if($this->pdo->inTransaction())$this->pdo->rollBack();
            return ['ok'=>false,'error'=>'record failed','detail'=>$e->getMessage()];
        }
    }
}
