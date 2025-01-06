<?php
    namespace app\core;

    class CoreUtils {
        /**
         * Adiciona uma notificação à sessão.
         *
         * Esta função cria uma nova notificação e a armazena na sessão para exibição posterior.
         *
         * @param string $type O tipo da notificação (ex: "success", "warning", "danger").
         * @param string $message A mensagem da notificação.
         */
        public static function addNotification(string $type, string $message):void {
            // Inicia a sessão que armazena as notificações caso não esteja iniciada
            if (!isset($_SESSION['notifications'])) {
                $_SESSION['notifications'] = [];
            }
        
            // Adiciona a notificação no array
            $_SESSION['notifications'][] = 
            "<div class='alert alert-$type alert-dismissible fade show' role='alert' style='margin-top: 20px;'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
        }

        /**
         * Redireciona o usuário para a URL especificada.
         *
         * Esta função inicia um redirecionamento para a URL fornecida. Se a URL for "[home_page]", redireciona para a página inicial.
         *
         * @param string $url A URL para redirecionar o usuário.
         * @return never Esta função não retorna nada, pois termina a execução do script.
         */
        public static function redirect(mixed $url = "[home-page]"): never {
            ob_start();
            switch ($url) {
                case "[home-page]":
                    $url = "/";
                break;
        
                case "[previous-page]":
                    $url = $_SERVER['HTTP_REFERER'] ?? "/";
                break;
            }
        
            // Define o cabeçalho de redirecionamento para o valor final de $url
            header(header: "Location: $url");
            ob_end_flush();
            exit();
        }

        /**
         * Exibe as notificações armazenadas na sessão.
         *
         * Esta função verifica se há notificações na sessão e, se houver, as exibe na saída padrão.
         * Após exibir as notificações, limpa a lista de notificações da sessão.
         */
        public static function showNotifications():void {
            if (isset($_SESSION['notifications']) && !empty($_SESSION['notifications'])) {
                // Percorre todas as notificações e as exibe
                foreach ($_SESSION['notifications'] as $notification) {
                    echo $notification;
                }
                // Limpa as notificações da sessão após exibi-las
                unset($_SESSION['notifications']);
            }
        }
    }