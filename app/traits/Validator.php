<?php
    namespace app\traits;

    class Validator {
        private $inputs;
        private $errors = [];
    
        /**
         * Método Construtor da classe
         * @param array $inputs  Os dados de entrada a serem validados.
         */
        public function __construct(array $inputs) {
            $this -> inputs = $inputs;
        }
    
        /**
         * Valida um campo usando uma regra específica.
         *
         * @param string $field O nome do campo a ser validado.
         * @param callable $rule A função de validação (callable). Deve retornar true se a validação passar, false caso contrário.
         * @param string $message A mensagem de erro a ser armazenada caso a validação falhe.
         * @param array $additionalParams Parâmetros adicionais a serem passados para a função de validação.
         * @return static Retorna a própria instância do Validator para encadeamento de métodos (fluent interface).
         */
        public function validate(string $field, callable $rule, string $message, array $additionalParams = []):static {
            $value = $this -> inputs[$field] ?? null;

            // Adiciona valores extras ao contexto, se necessário
            $params = array_merge([$value], $additionalParams);

            // Chama a regra de validação
            if (!call_user_func_array(callback: $rule, args: $params)) {
                $this -> errors[$field] = ['status' => 'is_invalid', 'message' => $message];
            }

            return $this;
        }
    
        /**
         * Retorna os erros de validação encontrados.
         *
         * @return array Um array associativo onde as chaves são os nomes dos campos com erro
         *               e os valores são arrays contendo 'status' (sempre 'is_invalid') e 'message'.
         */
        public function getErrors():array {
            return $this -> errors;
        }
    
        /**
         * Verifica se a validação passou (sem erros).
         *
         * @return bool Retorna true se não houver erros, false caso contrário.
         */
        public function passes():bool {
            return empty($this -> errors);
        }
    }