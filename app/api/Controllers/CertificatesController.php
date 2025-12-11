<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class CertificatesController
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function index(): array
    {
        $page    = max(1, (int)($_GET['p'] ?? 1));
        $perPage = min(50, max(1, (int)($_GET['per_page'] ?? 10)));
        $search  = trim((string)($_GET['s'] ?? ''));

        $where  = '';
        $params = [];
        if ($search !== '') {
            $where = 'WHERE (c.name LIKE :q1 OR c.id LIKE :q2)';
            $like  = "%{$search}%";
            $params[':q1'] = $like;
            $params[':q2'] = $like;
        }

        $st = $this->pdo->prepare("
            SELECT COUNT(*) cnt
            FROM certificates c
            LEFT JOIN courses crs ON crs.id = c.course_id
            $where
        ");
        foreach ($params as $k=>$v) $st->bindValue($k,$v);
        $st->execute();
        $total = (int)($st->fetch()['cnt'] ?? 0);

        $pages  = max(1, (int)ceil($total / $perPage));
        $page   = min($page, $pages);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT
                    c.id, c.name, c.issued_at,
                    crs.id AS course_id, crs.title AS course_title, crs.template AS course_template,
                    crs.ects AS course_ects, crs.url AS course_url
                FROM certificates c
                LEFT JOIN courses crs ON crs.id = c.course_id
                $where
                ORDER BY c.issued_at DESC, c.id DESC
                LIMIT :limit OFFSET :offset";
        $st = $this->pdo->prepare($sql);
        foreach ($params as $k=>$v) $st->bindValue($k,$v);
        $st->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $st->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $st->execute();

        $rows = $st->fetchAll() ?: [];
        $items = array_map(fn($r) => [
            'id'        => $r['id'],
            'name'      => $r['name'],
            'issued_at' => $r['issued_at'],
            'course'    => $r['course_id'] ? [
                'id'       => (int)$r['course_id'],
                'title'    => $r['course_title'],
                'ects'     => $r['course_ects'] !== null ? (float)$r['course_ects'] : null,
                'url'      => $r['course_url'],
                'template' => $r['course_template'],
            ] : null,
        ], $rows);

        return [
            'items'    => $items,
            'page'     => $page,
            'pages'    => $pages,
            'total'    => $total,
            'per_page' => $perPage,
        ];
    }

    public function single(string $uid): array
    {
        $r = $this->find($uid);
        if (!$r) { http_response_code(404); return ['error' => 'Certificate not found']; }

        return [
            'id'        => $r['id'],
            'name'      => $r['name'],
            'issued_at' => $r['issued_at'],
            'course'    => $r['course_title'] ? [
                'id'       => (int)$r['course_id'],
                'title'    => $r['course_title'],
                'ects'     => $r['course_ects'] !== null ? (float)$r['course_ects'] : null,
                'url'      => $r['course_url'],
                'template' => $r['course_template'],
            ] : null,
        ];
    }

    public function getPdf(string $uid): void {
        $cert = $this->find($uid);
        if (!$cert) { http_response_code(404); echo 'Certificate not found'; exit; }

        $slug = $this->slugFromFirstWord($cert['name']);
        header('Location: ' . "/certs/{$slug}-{$uid}.pdf", true, 303);
        exit;
    }

    public function pdf(string $file): void
    {
        if (!preg_match('~-(?<uid>[A-Za-z0-9]{5})\.pdf$~', $file, $m)) {
            http_response_code(404); echo 'Bad certificate URL'; exit;
        }
        require_once __DIR__ . '/../vendor/dompdf/autoload.inc.php';

        $uid  = $m['uid'];
        $cert = $this->find($uid);
        if (!$cert) { http_response_code(404); echo 'Certificate not found'; exit; }

        $downloadBase = $this->safeCyrFilename($cert['name']);
        $pageTitle    = "Сертифікат «Нова Традиція» – " . $downloadBase;

        $bytes = $this->renderPdf($cert, $pageTitle);

        $utf8Name  = $pageTitle . '.pdf';
        $asciiName = $this->slugFromFirstWord($cert['name']) . '.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$asciiName.'"; filename*=UTF-8\'\''.rawurlencode($utf8Name));
        header('Cache-Control: private, max-age=600');
        header('Accept-Ranges: bytes');
        echo $bytes; exit;
    }

    public function check(string $uid) {
        header('Location: ' . "/guidelines/registry/?s={$uid}", true, 303);
        exit;
    }

    private function find(string $uid): ?array
    {
        $st = $this->pdo->prepare("
            SELECT
                c.id,
                c.name,
                c.issued_at,
                crs.id       AS course_id,
                crs.title    AS course_title,
                crs.ects     AS course_ects,
                crs.url      AS course_url,
                crs.template AS course_template
            FROM certificates c
            LEFT JOIN courses crs ON crs.id = c.course_id
            WHERE c.id = :id
            LIMIT 1
        ");
        $st->execute([':id' => $uid]);
        $r = $st->fetch();
        return $r ?: null;
    }

    private function slugFromFirstWord(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name));
        $first = $parts[0] ?? $name;
        $map = [
            'А'=>'A','Б'=>'B','В'=>'V','Г'=>'H','Ґ'=>'G','Д'=>'D','Е'=>'E','Є'=>'Ye','Ж'=>'Zh','З'=>'Z','И'=>'Y','І'=>'I','Ї'=>'Yi','Й'=>'Y',
            'К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'Kh','Ц'=>'Ts','Ч'=>'Ch',
            'Ш'=>'Sh','Щ'=>'Shch','Ю'=>'Yu','Я'=>'Ya','Ь'=>'','Ъ'=>'',
            'а'=>'a','б'=>'b','в'=>'v','г'=>'h','ґ'=>'g','д'=>'d','е'=>'e','є'=>'ie','ж'=>'zh','з'=>'z','и'=>'y','і'=>'i','ї'=>'i','й'=>'i',
            'к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts','ч'=>'ch',
            'ш'=>'sh','щ'=>'shch','ю'=>'iu','я'=>'ia','ь'=>'','ъ'=>'',
        ];
        $first = strtr($first, $map);
        $first = preg_replace('~[^A-Za-z0-9]+~', '-', $first);
        $first = trim($first, '-');
        return $first !== '' ? strtolower($first) : 'certificate';
    }

    private function safeCyrFilename(string $name): string
    {
        $name = trim($name);
        $name = preg_replace('~[\/\\\\:\*\?"<>\|\x00-\x1F]+~u', ' ', $name);
        $name = preg_replace('~\s+~u', ' ', $name);
        $name = mb_substr($name, 0, 100, 'UTF-8');
        return $name !== '' ? $name : 'Сертифікат';
    }

    private function renderPdf(array $cert, string $pageTitle): string
    {
        $html = $this->buildHtml($cert, $pageTitle);

        $opt = new Options();
        $opt->set('isRemoteEnabled', true);
        $opt->set('defaultFont', 'DejaVu Sans');
        $opt->setDpi(171);

        $dompdf = new Dompdf($opt);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        if (method_exists($dompdf, 'getCanvas')) {
            $canvas = $dompdf->getCanvas();
            if ($canvas && method_exists($canvas, 'get_cpdf')) {
                $dompdf->getCanvas()->get_cpdf()->addInfo('Title', $pageTitle);
            }
        }
        return $dompdf->output();
    }

    private function qrSvg(string $payload): string
    {
        $dir  = __DIR__ . '/../../../storage/qr';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $key  = sha1($payload);
        $file = $dir . "/{$key}.svg";

        if (is_file($file) && filesize($file) > 0) {
            $c = @file_get_contents($file);
            if ($c !== false) return $c;
        }

        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&margin=0&format=svg&data=' . rawurlencode($payload);
        $svg = @file_get_contents($url) ?: '';

        if ($svg !== '') { @file_put_contents($file, $svg); }
        return $svg;
    }

    private function buildHtml(array $cert, string $pageTitle): string
    {
        $bgFs  = realpath(__DIR__ . '/../../../public/assets/certs/'.$cert['course_template']);
        $bgUrl = ($bgFs && is_readable($bgFs)) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgFs)) : '';

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $verifyUrl = $scheme.'://'.$host.'/certs/'.$cert['id'];

        $qrSvg = $this->qrSvg($verifyUrl);
        $qrDataUrl = $qrSvg !== '' ? 'data:image/svg+xml;base64,' . base64_encode($qrSvg) : '';

        $issued = '';
        if (!empty($cert['issued_at'])) {
            $ts = strtotime((string)$cert['issued_at']);
            $issued = $ts ? date('d.m.Y', $ts) : htmlspecialchars((string)$cert['issued_at']);
        }

        ob_start(); ?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <style>
    @page { size: A4 landscape; margin: 0; }
    html, body { margin:0; padding:0 }
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap');
    body { font-family: "Montserrat", "DejaVu Sans", sans-serif; }

    .page { position: relative; width: 297mm; height: 210mm; overflow: hidden; }
    .bg   { position: absolute; left:0; top:0; width:297mm; height:210mm; }

    .field { position: absolute; color:#000; line-height: 1.15; }

    .cert-no { left: 64px; top: 61px; color: white; font-size: 13pt; font-weight: 800; line-height:1; }
    .name { left: 50%; top: 549px; transform: translateX(-50%); font-weight: 600; line-height:1; white-space: nowrap; }
    .name-lg { font-size: 28pt;}
    .name-md { font-size: 24pt; }
    .date { left: 23px; bottom: 167px; color: white; font-size: 12pt; font-weight: 700; }
    .qr { position:absolute; right: 89px; bottom: 347px; padding: 12px; width: 200px; height: 200px; }
    .verify { position:absolute; right: 80px; bottom: 255px; line-height:0.7; text-align: right; font-size: 11pt; }
    .verify .url { display:block; margin-top: 2mm; font-weight: 600; }

    * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  </style>
</head>
<body>
  <div class="page">
    <?php if ($bgUrl): ?><img class="bg" src="<?= $bgUrl ?>" alt=""><?php endif; ?>

    <div class="field cert-no">Номер сертифікату<br>№ <?= htmlspecialchars($cert['id']) ?></div>

    <div class="field name name-<?= mb_strlen($cert['name'], 'UTF-8') > 28 ? 'md' : 'lg'; ?>"><?= htmlspecialchars($cert['name']) ?></div>

    <div class="field date">Сертифікат виданий: <?= $issued ?></div>

    <?php if ($qrDataUrl): ?><img class="qr" src="<?= $qrDataUrl ?>" alt="QR"><?php endif; ?>

    <div class="field verify">
      Перевірка автентичності сертифікату:
      <span class="url"><?= htmlspecialchars($verifyUrl) ?></span>
    </div>
  </div>
</body>
</html>
<?php
        return ob_get_clean();
    }
}
