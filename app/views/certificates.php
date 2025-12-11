<?php
require __DIR__ . '/../vendor/dompdf/autoload.inc.php';
require __DIR__ . '/../api/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfCertificatesController
{
    /** /certs/{uid} → 302 → /certs/<slug>-<uid>.pdf */
    public function redirectToNamedPdf(array $p): void {
        $uid  = $p['uid'];
        $pdo  = db(); // з app/api/db.php
        $cert = $this->find($pdo, $uid);
        if (!$cert) http_404('Certificate not found');

        $slug = $this->slugify($cert['name']);
        http_redirect("/certs/{$slug}-{$uid}.pdf", 302);
    }

    /** /certs/{file}.pdf → генеруємо і шлемо PDF (inline) */
    public function streamPdfByFile(array $p): void {
        if (!preg_match('~-(?<uid>[A-Za-z0-9]{5})\.pdf$~', $p['file'], $m)) {
            http_404('Bad certificate URL');
        }
        $uid  = $m['uid'];
        $pdo  = db();
        $cert = $this->find($pdo, $uid);
        if (!$cert) http_404('Certificate not found');

        $slug  = $this->slugify($cert['name']);
        $bytes = $this->renderPdf($cert);
        send_pdf($bytes, $slug . '.pdf', 600);
    }

    /* ===== privates ===== */

    private function find(PDO $pdo, string $uid): ?array {
        $st = $pdo->prepare("
            SELECT c.id, c.name, c.issued_at,
                   crs.title AS course_title, crs.ects, crs.url
            FROM certificates c
            LEFT JOIN courses crs ON crs.id = c.course_id
            WHERE c.id = :id
            LIMIT 1
        ");
        $st->execute([':id' => $uid]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    private function slugify(string $s): string {
        $map = [
            'А'=>'A','Б'=>'B','В'=>'V','Г'=>'H','Ґ'=>'G','Д'=>'D','Е'=>'E','Є'=>'Ye','Ж'=>'Zh','З'=>'Z','И'=>'Y','І'=>'I','Ї'=>'Yi','Й'=>'Y',
            'К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'Kh','Ц'=>'Ts','Ч'=>'Ch',
            'Ш'=>'Sh','Щ'=>'Shch','Ю'=>'Yu','Я'=>'Ya','Ь'=>'','Ъ'=>'',
            'а'=>'a','б'=>'b','в'=>'v','г'=>'h','ґ'=>'g','д'=>'d','е'=>'e','є'=>'ie','ж'=>'zh','з'=>'z','и'=>'y','і'=>'i','ї'=>'i','й'=>'i',
            'к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts','ч'=>'ch',
            'ш'=>'sh','щ'=>'shch','ю'=>'iu','я'=>'ia','ь'=>'','ъ'=>'',
        ];
        $s = strtr($s, $map);
        $s = preg_replace('~[^A-Za-z0-9]+~', '-', $s);
        $s = trim($s, '-');
        return $s !== '' ? strtolower($s) : 'certificate';
    }

    private function renderPdf(array $cert): string {
        $html = $this->buildHtml($cert);

        $opt = new Options();
        $opt->set('isRemoteEnabled', true);
        $opt->set('defaultFont', 'DejaVu Sans'); // кирилиця
        $dompdf = new Dompdf($opt);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    private function buildHtml(array $cert): string {
        ob_start(); ?>
<!doctype html>
<html lang="uk"><meta charset="utf-8">
<style>
  @page { margin: 32px; }
  body { font-family: DejaVu Sans, sans-serif; }
  .wrap{border:6px solid #333;padding:40px;text-align:center}
  h1{margin:0 0 8px;font-size:28px}
  .muted{color:#555}
</style>
<div class="wrap">
  <h1>Сертифікат</h1>
  <p class="muted">ID: <?=htmlspecialchars($cert['id'])?></p>
  <p>Видано: <strong><?=htmlspecialchars($cert['name'])?></strong></p>
  <?php if (!empty($cert['course_title'])): ?>
    <p>Курс: <strong><?=htmlspecialchars($cert['course_title'])?></strong>
      <?php if ($cert['ects'] !== null): ?>(<?= (float)$cert['ects']?> ECTS)<?php endif; ?>
    </p>
  <?php endif; ?>
  <p>Дата: <?=htmlspecialchars($cert['issued_at'])?></p>
</div>
</html>
<?php
        return ob_get_clean();
    }
}
