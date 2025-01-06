<?php
    namespace app\core;

    use app\traits\UUID;

    class Model {
        /**
         * Gera um UUID único para uma tabela.
         *
         * Este método gera um UUID v4 e verifica se ele já existe na tabela especificada.
         * Se o UUID já existir, um novo UUID é gerado até que um único seja encontrado.
         *
         * @param string $table A tabela onde o UUID será verificado.
         * @param string $column A coluna onde o UUID será armazenado (padrão: "uuid").
         * @return string|null Retorna o UUID gerado ou null em caso de erro na geração.
         */
        public static function generateUUID(string $table, string $column = "uuid"):?string {
            do {
                $uuid = UUID::UUIDv4();
                $sql = "SELECT {$column} FROM {$table} WHERE {$column} = :uuid";
                $stmt = Connection::prepare(sql: $sql);
                $stmt -> execute(params: [':uuid' => $uuid]);
            } while ($stmt -> rowCount() > 0);

            return $uuid;
        }

        /**
         * Insere um novo registro na tabela.
         *
         * @param string $table A tabela onde o registro será inserido.
         * @param array $data Um array associativo contendo os dados a serem inseridos.
         * @return bool Retorna true em caso de sucesso, false em caso de falha.
         */
        public function insert(string $table, array $data):bool {
            $columns = preg_replace(pattern: array_keys(['/[:]/u' => '']), replacement: '', subject: implode(separator: ", ", array: array_keys($data)));
            $binds = implode(separator: ", ", array: array_keys($data));

            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$binds})";
            Connection::beginTransaction();
            $stmt = Connection::prepare(sql: $sql);
            $stmt -> execute(params: $data);
            if(!$stmt) {
                Connection::rollBack();
                return false;
            }
            Connection::commit();
            return true;
        }

        /**
         * Atualiza registros na tabela.
         *
         * @param string $table A tabela a ser atualizada.
         * @param array $data Um array associativo contendo os dados a serem atualizados.
         * @param string $conditions A cláusula WHERE para a atualização.
         * @return bool Retorna true em caso de sucesso, false em caso de falha.
         */
        public function update(string $table, array $data, string $conditions = "1=1"):bool {
            // Monta a Querie SQL dinamicamente
            $sql = "UPDATE {$table} SET";
            $countParams = count(value: $data);
            $paramsAdd = 0;
            
            foreach ($data as $key => $value) {
                $key = preg_replace(pattern: array_keys(['/[:]/u' => '']), replacement: '', subject: $key);
                $paramsAdd++;
                if($paramsAdd === $countParams) {
                    $sql .= " {$key} = :{$key} WHERE {$conditions}";
                } else {
                    $sql .= " {$key} = :{$key},";
                }
            }

            // Faz a alteração no banco de dados
            Connection::beginTransaction();
            $stmt = Connection::prepare(sql: $sql);
            $stmt -> execute(params: $data);
            if(!$stmt) {
                Connection::rollBack();
                return false;
            }
            Connection::commit();
            return true;
        }

        /**
         * Busca um único registro na tabela.
         *
         * @param string $table A tabela a ser consultada.
         * @param string $columns As colunas a serem retornadas (padrão: "*").
         * @param string $conditions A cláusula WHERE da consulta (padrão: "1=1").
         * @param array $params Os parâmetros para a consulta preparada.
         * @return array|null Retorna um array associativo com o registro ou null se não encontrado.
         */
        public function findOne(string $table, string $columns = "*", string $conditions = "1=1", array $params = []):array {
            $sql = "SELECT {$columns} FROM {$table} WHERE {$conditions} LIMIT 1";
            $result = Connection::fetchAll(sql: $sql, params: $params)['0'] ?? [];
            return $result;
        }

        /**
         * Busca todos os registros na tabela.
         *
         * @param string $table A tabela a ser consultada.
         * @param string $column As colunas a serem retornadas (padrão: "*").
         * @param string $condition A cláusula WHERE da consulta (padrão: "1=1").
         * @param array $params Os parâmetros para a consulta preparada.
         * @return array Retorna um array com todos os registros encontrados. Retorna um array vazio caso nenhum registro seja encontrado.
         */
        public function findAll(string $table, string $column = "*", string $condition = "1=1", array $params = []):array {
            $sql = "SELECT {$column} FROM {$table} WHERE {$condition}";
            return Connection::fetchAll(sql: $sql, params: $params);
        }
    }