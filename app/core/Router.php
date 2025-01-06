<?php
    namespace app\core;

    class Router {
        private static $routes = [];
        private static $params = [];

        /**
         * Adiciona uma nova rota à lista de rotas.
         *
         * @param string $method O método HTTP da rota (GET, POST, PUT, DELETE, etc.).
         * @param string $path O caminho da rota.
         * @param array $handler Um array contendo o nome da classe do controlador e o nome do método de ação.
         * @param array $middlewares Lista de middlewares a serem aplicados.
         * @return void
         */
        public static function addRoute(string $method, string $path, array $handler, array $middlewares = []): void {
            Self::$routes[] = [
                'method' => strtoupper(string: $method),
                'path' => '/' . trim(string: $path, characters: '/'),
                'controller' => $handler[0],
                'action' => $handler[1],
                'middlewares' => $middlewares
            ];
        }

        /**
         * Adiciona uma rota com o método GET.
         *
         * @param string $path O caminho da rota.
         * @param array $handler Um array contendo o nome da classe do controlador e o nome do método de ação.
         * @param array $middlewares Lista de middlewares a serem aplicados.
         * @return void
         */
        public static function get(string $path, array $handler, array $middlewares = []): void {
            Self::addRoute(method: 'GET', path: $path, handler: $handler, middlewares: $middlewares);
        }

        /**
         * Adiciona uma rota com o método POST.
         *
         * @param string $path O caminho da rota.
         * @param array $handler Um array contendo o nome da classe do controlador e o nome do método de ação.
         * @param array $middlewares Lista de middlewares a serem aplicados.
         * @return void
         */
        public static function post(string $path, array $handler, array $middlewares = []): void {
            Self::addRoute(method: 'POST', path: $path, handler: $handler, middlewares: $middlewares);
        }

        /**
         * Adiciona uma rota com o método PUT.
         *
         * @param string $path O caminho da rota.
         * @param array $handler Um array contendo o nome da classe do controlador e o nome do método de ação.
         * @param array $middlewares Lista de middlewares a serem aplicados.
         * @return void
         */
        public static function put(string $path, array $handler, array $middlewares = []): void {
            Self::addRoute(method: 'PUT', path: $path, handler: $handler, middlewares: $middlewares);
        }

        /**
         * Adiciona uma rota com o método DELETE.
         *
         * @param string $path O caminho da rota.
         * @param array $handler Um array contendo o nome da classe do controlador e o nome do método de ação.
         * @param array $middlewares Lista de middlewares a serem aplicados.
         * @return void
         */
        public static function delete(string $path, array $handler, array $middlewares = []): void {
            Self::addRoute(method: 'DELETE', path: $path, handler: $handler, middlewares: $middlewares);
        }

        /**
         * Verifica se o caminho da rota corresponde ao caminho da requisição.
         *
         * @param string $routePath O caminho da rota.
         * @param string $requestPath O caminho da requisição.
         * @return bool Retorna true se houver correspondência, false caso contrário.
         */
        public static function matchPath($routePath, $requestPath): bool {
            $routeParts = explode(separator: '/', string: trim(string: $routePath, characters: '/'));
            $requestParts = explode(separator: '/', string: trim(string: $requestPath, characters: '/'));

            if (count(value: $routeParts) !== count(value: $requestParts)) {
                return false;
            }

            foreach ($routeParts as $i => $part) {
                if (preg_match(pattern: '/^{\w+}$/', subject: $part)) {
                    Self::$params[] = $requestParts[$i];
                } elseif ($part !== $requestParts[$i]) {
                    return false;
                }
            }

            return true;
        }

        /**
         * Executa os middlewares da rota.
         *
         * @param array $middlewares Lista de middlewares a serem executados.
         * @return void
         */
        public static function runMiddlewares(array $middlewares): void {
            foreach ($middlewares as $middleware) {
                if (strpos(haystack: $middleware, needle: '::') !== false) {
                    list($middlewareClass, $methodWithArgs) = explode(separator: '::', string: $middleware);
                    
                    // Suporte a argumentos no middleware (exemplo: Middleware::Permission:ADMINISTRADOR)
                    $methodParts = explode(separator: ':', string: $methodWithArgs);
                    $method = $methodParts[0];
                    $args = array_slice(array: $methodParts, offset: 1); // Argumentos adicionais
                    
                    if (method_exists(object_or_class: $middlewareClass, method: $method)) {
                        // Chama o middleware com os argumentos
                        call_user_func_array(callback: [$middlewareClass, $method], args: $args);
                    } else {
                        throw new \Exception(message: "Método {$method} não existe na classe {$middlewareClass}");
                    }
                } else {
                    throw new \Exception(message: "Formato inválido do middleware: {$middleware}");
                }
            }
        }
        
        /**
         * Despacha a requisição para o controlador e ação correspondentes.
         *
         * @return void
         */
        public static function dispath(): void {
            $method = $_SERVER['REQUEST_METHOD'];
            $path = parse_url(url: $_SERVER['REQUEST_URI'], component: PHP_URL_PATH);
            $path = trim(string: rtrim(string: $path, characters: '/'), characters: '/');
        
            foreach (Self::$routes as $route) {
                if ($route['method'] === $method && Self::matchPath(routePath: $route['path'], requestPath: $path)) {
                    if (!empty($route['middlewares'])) {
                        Self::runMiddlewares(middlewares: $route['middlewares']);
                    }
        
                    $controller = new $route['controller']();
                    $action = $route['action'];
                    $params = array_values(array_merge(Self::$params, $_GET));
        
                    if (method_exists(object_or_class: $controller, method: $action)) {
                        call_user_func_array(callback: [$controller, $action], args: $params);
                    } else {
                        CoreUtils::addNotification(type: "warning", message: "Método Não Encontrado!");
                        http_response_code(response_code: 404);
                        header(header: "HTTP/1.0 404 Not Found");
                        require_once realpath(path: __DIR__ . "/../views/erros/404.php");
                        exit();
                    }
                    return;
                }
            }

            http_response_code(response_code: 404);
            header(header: "HTTP/1.0 404 Not Found");
            require_once realpath(path: __DIR__ . "/../views/erros/404.php");
            exit();
        }
    }