<?php require __DIR__ . '/../parts/head.php'; ?>
<body class="antialiased min-h-dvh md:min-h-screen flex flex-col overflow-x-clip">
<?php
  $header_fixed = isset($header_fixed) ? (bool)$header_fixed : true;
  require __DIR__ . '/../parts/header.php';
?>
<?= $content ?>
<?php require __DIR__ . '/../parts/footer.php'; ?>
</body>
</html>
