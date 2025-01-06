<?php
    // Inicia o buffer de saída
    ob_start();

    // Inicia ou resgata a sessão existente
    session_start();

    //Definindo fuso horário
    date_default_timezone_set(timezoneId: 'America/Sao_Paulo');

    // Define o idioma padrão do sistema para Português do Brasil
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');

    // Importa arquivos de configuração se ele existir
    if(file_exists(filename: realpath(path: __DIR__ . '/app/config/Config.php'))) {
        require_once realpath(path: __DIR__ . '/app/config/Config.php');
    }

    // Importa autoload das classes do software
    require_once realpath(path: __DIR__ . '/app/autoload.php');
    
    // Importa autoload dos frameworks administrados pelo composer
    require_once realpath(path: __DIR__ . '/vendor/autoload.php');