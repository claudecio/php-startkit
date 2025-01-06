<?php
    ini_set(option: 'display_errors', value: 1);
    ini_set(option: 'display_startup_errors', value: 1);
    error_reporting(error_level: E_ALL);

    // Importa Inicialização do sistema
    require_once realpath(path: __DIR__ . '/../bootstrap.php');

    // Carrega classes utilizadas pelo sistema
    use Dotenv\Dotenv;
    use app\core\Router;

    // Inicializa as váriaveis de ambiente
    $dotenv = Dotenv::createImmutable(paths: realpath(path: __DIR__ . "/../"));
    $dotenv -> load();

    // Carrega o indexador de rotas
    require_once realpath(path: __DIR__ . '/../app/routes/IndexRoute.php');

    Router::dispath();