<?php
    namespace app\core;

    use app\core\CoreUtils;

    class Middleware {
        
        // Middleware de autenticação
        public static function Auth(): void {
            if (!isset($_SESSION['login']['userData']) || ($_SESSION['login']['allowedUpdatePassword'] == true)) {
                CoreUtils::redirect(url: '/auth/login');
                exit;
            }
        }

        // Middleware de verificação de permissões
        public static function Permission($requiredPermission):void {
            // Inicializa a variável de controle
            $resultadoConsulta = false;

            // Verifica se o array de permissões do usuário existe na sessão
            if (isset($_SESSION['login']['permissions']) && is_array(value: $_SESSION['login']['permissions'])) {
                foreach ($_SESSION['login']['permissions'] as $permissaoUsuario) {
                    // Verifica se o módulo corresponde e se tem acesso
                    if ($permissaoUsuario['modulo'] === $requiredPermission) {
                        $resultadoConsulta = (bool) $permissaoUsuario['acesso_modulo'];
                        break; // Encontrou a permissão, não precisa continuar o laço
                    }
                }
            }

            // Verifica se o usuário tem permissão ou se a sessão está válida
            if (!$resultadoConsulta) {
                // Retorna código HTTP de acesso negado
                http_response_code(response_code: 403);
                // Adiciona notificação de erro
                CoreUtils::addNotification(type: "danger", message: "Usuário sem permissão!");
                // Redireciona para a página anterior ou uma página padrão
                CoreUtils::redirect(url: '[previous-page]');

                exit; // Encerra a execução
            }
        }

        // Middleware para verificar se o usuário já está logado
        public static function Guest(): void {
            if (isset($_SESSION['login']['userData'])) {
                CoreUtils::redirect(url: "[home-page]");
                exit;
            }
        }

        public static function canChangePassword(){
            if($_SESSION['login']['allowedUpdatePassword'] !== true) {
                // Acesso negado
                http_response_code(response_code: 403);
                CoreUtils::addNotification(type: "danger", message:"Usuário sem permissão!");
                CoreUtils::redirect(url: "[previous-page]");
                exit;
            }
        }
        
        // Exemplo de outro middleware, como verificar se o usuário tem a role "admin"
        public static function Admin():void {
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                http_response_code(response_code: 403);
                CoreUtils::addNotification(type: "danger", message:"Acesso restrito apenas para administradores!");
                CoreUtils::redirect(url: "[previous-page]");
                exit;
            }
        }
    }