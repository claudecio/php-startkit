<?php
    namespace app\core;

    use PDO;
    use PDOStatement;
    use PDOException;
    use app\core\CoreUtils;
    use app\traits\UUID;

    class Connection {
        private static ?PDO $dbh = null;
        private static ?CoreUtils $coreUtils = null;
        private static ?UUID $uuid = null;

        /**
         * Inicializa a conexão com o banco de dados.
         */
        private static function init():void {
            if(Self::$dbh === null) {
                try {
                    // Armazena as credenciais através das váriaveis de ambiente
                    $host = $_ENV["DATABASE_HOST"];
                    $dbschema = $_ENV["DATABASE_SCHEMA"];
                    $username = $_ENV["DATABASE_USERNAME"];
                    $passowrd = $_ENV["DATABASE_PASSWORD"];
                    $port = $_ENV["DATABASE_PORT"];

                    $dns = "mysql:host={$host};port={$port};dbname={$dbschema}";
                    $options = [
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ];

                    Self::$dbh = new PDO(dsn: $dns, username: $username, password: $passowrd, options: $options);
                    Self::$coreUtils = new CoreUtils();
                    Self::$uuid = new UUID();
                } catch (PDOException $e) {
                    die("Erro ao conectar com o banco de dados: {$e -> getMessage()}");
                }
            }
        }

        /**
         * Prepara uma consulta SQL para execução.
         *
         * @param string $sql A consulta SQL a ser preparada.
         * @return PDOStatement Retorna o objeto PDOStatement preparado.
         */
        public static function prepare(string $sql):PDOStatement {
            Self::init();
            return Self::$dbh -> prepare(query: $sql);
        }

        /**
         * Executa uma consulta diretamente (para comandos simples).
         *
         * @param string $sql A consulta SQL a ser executada.
         * @return bool Retorna true em caso de sucesso, false caso contrário.
         */
        public static function execute(string $sql):bool {
            Self::init();
            return Self::$dbh -> exec($sql) !== false;
        }

        /**
         * Obtém o primeiro resultado de uma consulta SQL.
         *
         * @param string $sql A consulta SQL a ser executada.
         * @param array $params Parâmetros para a consulta preparada.
         * @return array|null Retorna um array associativo ou null se nenhum resultado for encontrado.
         */
        public static function fetchOne(string $sql, array $params = []):?array {
            $stmt = Self::prepare(sql: $sql);
            $stmt -> execute($params);
            return $stmt -> fetch(PDO::FETCH_ASSOC) ?: null;
        }

        /**
         * Obtém todos os resultados de uma consulta SQL.
         *
         * @param string $sql A consulta SQL a ser executada.
         * @param array $params Parâmetros para a consulta preparada.
         * @return array Retorna um array de arrays associativos.
         */
        public static function fetchAll(string $sql, array $params = []):array {
            $stmt = Self::prepare(sql: $sql);
            $stmt -> execute($params);
            return $stmt -> fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Inicia uma transação.
         */
        public static function beginTransaction(): void {
            Self::init();
            Self::$dbh -> beginTransaction();
        }

        /**
         * Verifica se há uma transação ativa.
         *
         * @return bool Retorna true se houver uma transação ativa.
         */
        public static function inTransaction(): bool {
            Self::init();
            return Self::$dbh -> inTransaction();
        }

        /**
         * Confirma uma transação.
         */
        public static function commit(): void {
            if (Self::inTransaction()) {
                Self::$dbh -> commit();
            }
        }

        /**
         * Reverte uma transação.
         */
        public static function rollBack(): void {
            if (Self::inTransaction()) {
                Self::$dbh -> rollBack();
            }
        }

        /**
         * Obtém o ID do último registro inserido.
         *
         * @return string O ID do último registro inserido.
         */
        public static function lastInsertId(): string {
            Self::init();
            return Self::$dbh -> lastInsertId();
        }
    }