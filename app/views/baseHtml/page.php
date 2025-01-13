<?php use app\core\CoreUtils; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="theme-color" content="#712cf9">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?=$dados['html_info']['title']?></title>
        <script src="/assets/js/color-modes.js"></script>
        <link rel="icon" href="/assets/img/favicons/favicon.ico">
        <link rel="icon" href="/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="icon" href="/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <!-- Estilos Customizados -->
        <link rel="stylesheet" href="/assets/css/dists/header.css">
        <link rel="stylesheet" href="/assets/css/dists/style.css">
        <link rel="stylesheet" href="/assets/css/dists/style@3.css">
        <link rel="stylesheet" href="/assets/css/dists/publico-home.css">
        <link rel="stylesheet" href="/assets/css/dists/offcanvas-navbar.css">
    </head>
    <body class="bg-body-tertiary">
        <!-- Header -->
        <?php CoreUtils::buildHeader(mainPath: __DIR__ . "/../partials/header.php");?>
        <!-- Content -->
        <main class="container">
            <?= CoreUtils::showNotifications(); ?>

            <!-- Footer -->
            <?php require_once realpath(path: __DIR__ . "/../partials/footer.php")?>
        </main>
        <!-- Importação de Javascript -->
        <script src="/assets/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/dists/offcanvas-navbar.js"></script>
    </body>
</html>