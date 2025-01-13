<?php
    namespace app\traits;

    use Exception;
    use app\core\CoreUtils;

    class CryptoUtils {
        private static string $key;
        private static string $algo;

        /**
         * Inicializa as configurações de criptografia.
         *
         * Este método obtém a chave e o algoritmo de criptografia das variáveis de ambiente
         * e os define nas propriedades estáticas da classe.
         *
         * @return void
         */
        public static function initialize():void {
            Self::$key = $_ENV['CRYPTO_KEY'];
            Self::$algo = $_ENV['CRYPTO_ALGO'];
        }

        /**
         * Criptografa dados usando AES-256-CBC.
         *
         * Este método serializa os dados de entrada para suportar qualquer tipo,
         * gera um vetor de inicialização (IV) aleatório, criptografa os dados
         * usando AES-256-CBC com uma chave secreta estática e retorna o resultado
         * codificado em Base64.
         *
         * @param mixed $data Os dados a serem criptografados.
         * @return string Os dados criptografados e codificados em Base64, ou uma string vazia em caso de erro.
         */
        public static function encryptData(mixed $data):string {
            // Inicializa ambiente
            Self::initialize();

            if(empty(Self::$key)) {
                CoreUtils::addNotification(type: "danger", message: "Chave de criptografia não definida.");
                return "";
            }

            try {
                // Serializa os dados para garantir suporte a qualquer tipo
                $serializedData = serialize(value: $data);
                // Gera vetor de inicialização IV aleatório
                $iv = openssl_random_pseudo_bytes(length: openssl_cipher_iv_length(cipher_algo: Self::$algo));

                if($iv === false) {
                    CoreUtils::addNotification(type: "danger", message: "Falha ao gerar IV.");
                    return "";
                }

                // Criptografa os dados
                $encryptedData = openssl_encrypt(data: $serializedData, cipher_algo: Self::$algo, passphrase: Self::$key, options: 0, iv: $iv);

                if($encryptedData === false) {
                    CoreUtils::addNotification(type: "danger", message: "Falha na criptografia dos dados: ". openssl_error_string());
                    return "";
                }

                // Retorna o resultado como base64 (dados criptografados + IV)
                return base64_encode(string: "{$encryptedData}::{$iv}");
            } catch(Exception $e) {
                CoreUtils::addNotification(type: "danger", message: "Erro durante criptografia: {$e -> getMessage()}");
                return "";
            }
        }

        /**
         * Descriptografa dados criptografados com AES-256-CBC.
         *
         * Este método decodifica os dados criptografados em Base64, separa os dados
         * criptografados do vetor de inicialização (IV), descriptografa os dados
         * usando AES-256-CBC com uma chave secreta estática e desserializa o resultado
         * para retornar os dados originais.
         *
         * @param string $encrypted Os dados criptografados e codificados em Base64.
         * @return mixed Os dados descriptografados, ou null em caso de erro.
         */
        public static function decryptData(string $encrypted):mixed {
            // Inicializa ambiente
            Self::initialize();

            // Verifica se a chave existe
            if(empty(Self::$key)) {
                CoreUtils::addNotification(type: "danger", message: "Chave de descriptografia não definida.");
                return "";
            }

            try {
                // Verifica se o arquivo para ser descriptografado está vazio
                if(empty($encrypted)) {
                    CoreUtils::addNotification(type: "warning", message: "Arquivo para descriptografia está vazio.");
                    return "";
                }

                // Decodifica o dado base64
                $decoded = base64_decode(string: $encrypted);
                // Separa os dados criptografados do IV
                [$encryptedData, $iv] = explode(separator: "::", string: $decoded);

                $decryptedData = openssl_decrypt(data: $encryptedData, cipher_algo: Self::$algo, passphrase: Self::$key, options: 0, iv: $iv);

                // Retorna os dados originais
                return unserialize(data: $decryptedData);
            } catch (Exception $e) {
                CoreUtils::addNotification(type: "danger", message: "Erro durante descriptografia: {$e -> getMessage()}");
                return "";
            }
        }
    }