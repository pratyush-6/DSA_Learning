<?php
/**
 * Page header / opening layout.
 * Expects optional $pageTitle before inclusion.
 */
require_once __DIR__ . '/../includes/auth.php';
$pageTitle = $pageTitle ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> &middot; <?= e(APP_NAME) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <link href="<?= asset('css/app.css') ?>" rel="stylesheet">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
</head>
<body>
<?php require __DIR__ . '/nav.php'; ?>
<main class="py-4">
  <div class="container-xl">
