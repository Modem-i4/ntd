<?php if (!isset($title)) { $title = 'Document'; } ?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="/tailwind.css" />
  <script defer src="/js/header.js"></script>
</head>
