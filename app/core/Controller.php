<?php
    namespace app\core;

    class Controller {
        
        /**
         * Renderiza um template PHP.
         *
         * Esta função localiza e inclui um arquivo de template PHP, passando os dados fornecidos para o template.
         * Se o arquivo não for encontrado, um erro 404 é exibido.
         *
         * @param string $pathFile O caminho relativo para o arquivo do template (sem a extensão .php).
         * @param mixed $dados Um array de dados a serem passados para o template.
         */
        public function view(string $pathFile, mixed $dados = []):void {
            // Monta caminho da view solicitada
            $filePath = realpath(path: __DIR__ . "/../views/$pathFile.php");
            
            // Verifica se o arquivo não existe
            if(!file_exists(filename: $filePath)) {
                header(header: "HTTP/1.0 404 Not Found");
                require_once realpath(path: __DIR__ . "/../views/erros/404.php");
                exit();
            }

            // Renderiza a view solicitada
            require_once $filePath;
        }
    }