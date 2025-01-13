<?php use app\core\CoreUtils; ?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="theme-color" content="#712cf9">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Erro 404</title>
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
        <style>
            .container {
                max-width: 600px;
                margin: 100px auto;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h1 {
                font-size: 100px;
                margin: 0;
            }

            p {
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?=CoreUtils::showNotifications()?>
            <b><h1 class="text-danger">404</h1></b>
            <p>Desculpe, a página que você está procurando não foi encontrada.</p>
            <a class="btn btn-primary" href="javascript:history.back()">Volte para a página anterior</a></p>
        </div>
    </body>
    <!-- Importação de Javascript -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
</html>