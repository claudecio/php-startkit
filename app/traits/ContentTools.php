<?php
    namespace app\traits;

    use app\core\CoreUtils;

    class ContentTools {
        /**
         * Remove tags HTML e caracteres especiais de uma string.
         *
         * @param string $string A string a ser sanitizada.
         * @return string|null A string sanitizada ou null em caso de erro.
         */
        public static function sanitizeString(string $string):array|string|null {
            // Remove tags HTML do texto
            $string = strip_tags(string: $string);

            // Remover pontuação, símbolos e converter para maiúsculas
            $caracteres_especiais = [    
                '/[!@#$%^&*(),.?":;{}|<>]/u' => '',
                '/[-_+=]/u' => '',
                '/[12345678]/u' => '',
                '/[áàâãªä]/u' => 'a', '/[ÁÀÂÃÄ]/u' => 'A',
                '/[éèêë]/u' => 'e', '/[ÉÈÊË]/u' => 'E',
                '/[íìîï]/u' => 'i', '/[ÍÌÎÏ]/u' => 'I',
                '/[óòôõºö]/u' => 'o', '/[ÓÒÔÕÖ]/u' => 'O',
                '/[úùûü]/u' => 'u', '/[ÚÙÛÜ]/u' => 'U',
                '/ç/u' => 'c', '/Ç/u' => 'C',
                '/ñ/u' => 'n', '/Ñ/u' => 'N'
            ];

            return preg_replace(pattern: array_keys($caracteres_especiais), replacement: array_values(array: $caracteres_especiais), subject: $string);
        }

        /**
         * Remove tags HTML e caracteres especiais de uma string de busca.
         *
         * @param string $search A string de busca a ser sanitizada.
         * @return string|null A string de busca sanitizada ou null em caso de erro.
         */
        public static function sanitizeSearch(string $search):array|string|null {
            // Remove tags HTML do texto
            $search = strip_tags(string: $search);

            // Remover pontuação, símbolos e converter para maiúsculas
            $caracteres_especiais = [
                '/[!@#$%^&*(),?":;{}|<>]/u' => '', // Símbolos comuns
                '/[-_+=]/u' => '', // Outros símbolos comuns
                '/[áàâãªä]/u' => 'a', '/[ÁÀÂÃÄ]/u' => 'A',
                '/[éèêë]/u' => 'e', '/[ÉÈÊË]/u' => 'E',
                '/[íìîï]/u' => 'i', '/[ÍÌÎÏ]/u' => 'I',
                '/[óòôõºö]/u' => 'o', '/[ÓÒÔÕÖ]/u' => 'O',
                '/[úùûü]/u' => 'u', '/[ÚÙÛÜ]/u' => 'U',
                '/ç/u' => 'c', '/Ç/u' => 'C',
                '/ñ/u' => 'n', '/Ñ/u' => 'N'
            ];

            return preg_replace(pattern: array_keys($caracteres_especiais), replacement: array_values(array: $caracteres_especiais), subject: $search);
        }

        /**
         * Remove tags HTML, caracteres especiais e espaços de um nome de usuário.
         *
         * @param string $username O nome de usuário a ser sanitizado.
         * @return string|null O nome de usuário sanitizado ou null em caso de erro.
         */
        public static function sanitizeUsername(string $username):array|string|null {
            // Remove tags HTML do texto
            $username = strip_tags(string: $username);
        
            // Remover pontuação, símbolos e converter para maiúsculas
            $caracteres_especiais = [
                '/[!@#$%^&*(),?":;{}|<>]/u' => '', // Símbolos comuns
                '/[-_+=]/u' => '', // Outros símbolos comuns
                '/[áàâãªä]/u' => 'a', '/[ÁÀÂÃÄ]/u' => 'A',
                '/[éèêë]/u' => 'e', '/[ÉÈÊË]/u' => 'E',
                '/[íìîï]/u' => 'i', '/[ÍÌÎÏ]/u' => 'I',
                '/[óòôõºö]/u' => 'o', '/[ÓÒÔÕÖ]/u' => 'O',
                '/[úùûü]/u' => 'u', '/[ÚÙÛÜ]/u' => 'U',
                '/ç/u' => 'c', '/Ç/u' => 'C',
                '/ñ/u' => 'n', '/Ñ/u' => 'N'
            ];
        
            // Substitui os caracteres especiais
            $username = preg_replace(pattern: array_keys($caracteres_especiais), replacement: array_values(array: $caracteres_especiais), subject: $username);
        
            // Remove os espaços
            $username = preg_replace(pattern: '/\s+/', replacement: '', subject: $username);
        
            return $username;
        }

        /**
         * Valida um nome de usuário.
         *
         * Este método sanitiza o nome de usuário, verifica se está vazio, se contém apenas
         * caracteres alfanuméricos e se o comprimento está dentro dos limites aceitáveis.
         *
         * @param string $username O nome de usuário a ser validado.
         * @return bool Retorna true se o nome de usuário for válido, false caso contrário.
         */
        public static function isValidUsername(string $username): bool {
            // Sanitize o nome de usuário usando o método sanitizeUsername
            $username = self::sanitizeUsername(username: $username);
            
            // Verifica se o nome de usuário está vazio após a sanitização
            if (empty($username)) {
                return false;
            }
        
            // Verifica se o nome de usuário tem apenas caracteres alfanuméricos
            if (!preg_match(pattern: '/^[a-zA-Z0-9]+$/', subject: $username)) {
                return false;
            }
        
            // Verifica o comprimento do nome de usuário (mínimo 3 caracteres, máximo 20 caracteres)
            $usernameLength = strlen(string: $username);
            if ($usernameLength < 3 || $usernameLength > 20) {
                CoreUtils::addNotification(type: "warning", message: "O usuário tem que ter entre 3 a 20 caracteres.");
                return false;
            }
        
            return true;
        }

        /**
         * Valida uma senha.
         *
         * Este método verifica se a senha atende aos critérios de segurança, como comprimento mínimo,
         * presença de letras maiúsculas, minúsculas e números.
         *
         * @param string $password A senha a ser validada.
         * @return bool Retorna true se a senha for válida, false caso contrário.
         */
        public static function validatePassword(string $password): bool {
            // Verifica o comprimento mínimo de 8 caracteres
            if (strlen(string: $password) < 8) {
                CoreUtils::addNotification(type: "warning", message: "A senha deve ter números, letras maiúsculas e minúsculas e no minímo 8 digitos");
                return false;
            }
    
            // Verifica se contém pelo menos uma letra maiúscula, uma letra minúscula, um número ou um símbolo especial
            if (!preg_match(pattern: '/[A-Z]/', subject: $password) || // Pelo menos uma letra maiúscula
                !preg_match(pattern: '/[a-z]/', subject: $password) || // Pelo menos uma letra minúscula
                !preg_match(pattern: '/\d/', subject: $password)) { // Pelo menos um número
                CoreUtils::addNotification(type: "warning", message: "A senha deve ter números, letras maiúsculas e minúsculas e no minímo 8 digitos");
                return false;
            }
    
            return true; // Senha válida
        }

        /**
         * Remove todos os caracteres não numéricos de um documento.
         *
         * @param string $document O documento a ser sanitizado.
         * @return string|null O documento sanitizado ou null em caso de erro.
         */
        public static function sanitizeDocument(string $document):array|string|null {
            // Remove todos os caracteres que não são dígitos
            return preg_replace(pattern: "/[^0-9]/", replacement: "", subject: $document);
        }

        /**
         * Remove espaços em branco do início e do final de uma string.
         *
         * @param string $text A string a ser sanitizada.
         * @return string A string sanitizada, sem espaços em branco no início e no final.
         */
        public static function sanitazeSimpleText(string $text):string {
            return trim(string: $text);
        }

        /**
         * Valida um CPF.
         *
         * @param string $cpf O CPF a ser validado.
         * @return bool True se o CPF for válido, false caso contrário.
         */
        public static function validateDocumentCpf(string $cpf):bool {
            // Remover qualquer caractere não numérico
            $cpf = preg_replace(pattern: '/\D/', replacement: '', subject: $cpf);
        
            // Verificar se o CPF tem 11 dígitos
            if (strlen(string: $cpf) !== 11) {
                return false;
            }
        
            // Verificar se o CPF é uma sequência de números iguais (ex: 111.111.111-11)
            if (preg_match(pattern: '/(\d)\1{10}/', subject: $cpf)) {
                return false;
            }
        
            // Validação do primeiro dígito verificador
            $soma = 0;
            for ($i = 0; $i < 9; $i++) {
                $soma += (int)$cpf[$i] * (10 - $i);
            }
            $resto = $soma % 11;
            $digito1 = ($resto < 2) ? 0 : 11 - $resto;
            if ($digito1 != (int)$cpf[9]) {
                return false;
            }
        
            // Validação do segundo dígito verificador
            $soma = 0;
            for ($i = 0; $i < 10; $i++) {
                $soma += (int)$cpf[$i] * (11 - $i);
            }
            $resto = $soma % 11;
            $digito2 = ($resto < 2) ? 0 : 11 - $resto;
            if ($digito2 != (int)$cpf[10]) {
                return false;
            }
        
            // Se passou em todas as verificações, o CPF é válido
            return true;
        }

        /**
         * Valida um CNPJ.
         *
         * @param string $cnpj O CNPJ a ser validado.
         * @return bool True se o CNPJ for válido, false caso contrário.
         */
        public static function validateDocumentCnpj(string $cnpj):bool {
            // Remover qualquer caractere não numérico
            $cnpj = preg_replace(pattern: '/\D/', replacement: '', subject: $cnpj);
        
            // Verificar se o CNPJ tem 14 dígitos
            if (strlen(string: $cnpj) !== 14) {
                return false;
            }
        
            // Verificar se o CNPJ é uma sequência de números iguais (ex: 11111111111111)
            if (preg_match(pattern: '/(\d)\1{13}/', subject: $cnpj)) {
                return false;
            }
        
            // Cálculo do primeiro dígito verificador
            $soma = 0;
            $pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for ($i = 0; $i < 12; $i++) {
                $soma += (int)$cnpj[$i] * $pesos[$i];
            }
            $resto = $soma % 11;
            $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
            if ($digito1 != (int)$cnpj[12]) {
                return false;
            }
        
            // Cálculo do segundo dígito verificador
            $soma = 0;
            $pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for ($i = 0; $i < 13; $i++) {
                $soma += (int)$cnpj[$i] * $pesos[$i];
            }
            $resto = $soma % 11;
            $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
            if ($digito2 != (int)$cnpj[13]) {
                return false;
            }
        
            // Se passou em todas as verificações, o CNPJ é válido
            return true;
        }

        /**
         * Valida se um nome de usuário é inválido.
         *
         * Este método verifica se um nome de usuário atende aos seguintes critérios de validade:
         * - Possui pelo menos 3 caracteres.
         * - Contém apenas letras (maiúsculas e minúsculas), números, underscores (_) e hífens (-).
         * - Não contém espaços.
         *
         * @param string $username O nome de usuário a ser validado.
         * @return bool Retorna true se o nome de usuário for válido (ou seja, *não* inválido) e false caso contrário.
         */
        public static function validateIsValidUsername(string $username):bool {
            // Verifica se o nome de usuário é menor que 3 caracteres.
            if (strlen(string: $username) < 3) {
                CoreUtils::addNotification(type: 'warning', message: 'Usuário não pode ser menor que 3 caracteres.');
                return false;
            }

            // Verifica se contém símbolos especiais diferentes de _ ou -.
            if (preg_match(pattern: '/[^a-zA-Z0-9_-]/', subject: $username)) {
                CoreUtils::addNotification(type: 'warning', message: 'Usuário não pode conter espaços e simbolos especiais diferentes de _ e -.');
                return false;
            }

            return true;
        }
        
        /**
         * Valida um endereço de e-mail.
         *
         * @param mixed $email O e-mail a ser validado.
         * @param mixed $dominioEsperado O domínio esperado (opcional).
         * @return bool|int True se o e-mail for válido, false caso contrário, 1 se o email corresponder ao dominio esperado.
         */
        public static function validateEmail(mixed $email, mixed $dominioEsperado = null):bool|int {
            // Verifica se o e-mail tem um formato válido usando filter_var
            if (!filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL)) {
                return false; // E-mail é inválido
            }
        
            // Se não for fornecido um domínio específico, só verifica o formato
            if ($dominioEsperado === null) {
                return true; // E-mail é válido para qualquer domínio
            }
        
            // Se um domínio foi fornecido, verifica se o e-mail corresponde a ele (considerando subdomínios)
            $padrao = '/@([a-z0-9.-]+\.)?' . preg_quote(str: $dominioEsperado, delimiter: '/') . '$/i';
            
            // Verifica se o domínio do e-mail corresponde ao domínio fornecido
            $validationResult = preg_match(pattern: $padrao, subject: $email);

            if(!$validationResult) {
                CoreUtils::addNotification(type: "warning", message: "Só são aceitos emails do domínio {$dominioEsperado}");
                return $validationResult;
            }

            return $validationResult;
        }

        /**
         * Valida uma data no formato YYYY-MM-DD.
         *
         * @param mixed $date A data a ser validada.
         * @param string $separator O separador da data (opcional, padrão '-').
         * @return bool True se a data for válida, false caso contrário.
         */
        public static function validateDate(mixed $date, string $separator = '-'):bool {
            // Verificar se a data está no formato YYYY-MM-DD
            $pattern = '/^\d{4}-\d{2}-\d{2}$/';
            if (!preg_match(pattern: $pattern, subject: $date)) {
                return false;
            }

            // Dividir a data em ano, mês e dia
            list($ano, $mes, $dia) = explode(separator: "{$separator}", string: $date);

            // Usar checkdate para validar a data
            return checkdate(month: (int)$mes, day: (int)$dia, year: (int)$ano);
        }

         /**
         * Verifica se um valor não está vazio.
         *
         * @param mixed $value O valor a ser verificado.
         * @return bool Retorna true se o valor não estiver vazio, false caso contrário.
         */
        public static function validateIsNotEmpty(mixed $value):bool {
            return !empty($value);
        }

        /**
         * Verifica se dois valores são estritamente iguais (mesmo valor e mesmo tipo).
         *
         * @param mixed $value1 O primeiro valor a ser comparado.
         * @param mixed $value2 O segundo valor a ser comparado.
         * @return bool Retorna true se os valores forem estritamente iguais, false caso contrário.
         */
        public static function validateIsEquals(mixed $value1, mixed $value2):bool {
            return $value1 === $value2;
        }

        public static function validateIsNotNull(mixed $value):bool {
            return ($value === null) ? false : true;
        }

        /**
         * Mascara um CPF ou CNPJ.
         *
         * @param string $cpfCnpj O CPF ou CNPJ a ser mascarado.
         * @return string A string mascarada.
         */
        public static function maskDocumentCpfCnpj($cpfCnpj):string {
            switch ($cpfCnpj) {
                case strlen(string: $cpfCnpj) == 11:
                    return substr($cpfCnpj, 0, 3) . '.' . substr($cpfCnpj, 3, 3) . '.' . substr($cpfCnpj, 6, 3) . '-' . substr($cpfCnpj, 9, 2);
                    
                case strlen(string: $cpfCnpj) == 14:
                    return substr($cpfCnpj, 0, 2) . '.' . substr($cpfCnpj, 2, 3) . '.' . substr($cpfCnpj, 5, 3) . '/' . substr($cpfCnpj, 8, 4) . '-' . substr($cpfCnpj, 12, 2);
                
                default:
                    return '-';
            }
        }

        /**
         * Mascara um número de telefone.
         *
         * @param string $number O número de telefone a ser mascarado.
         * @return string|null O número de telefone mascarado ou null caso ocorra um erro.
         */
        public static function maskTelephoneNumber($number):array|string|null {
            // Remove todos os caracteres que não são dígitos
            $number = preg_replace(pattern: '/\D/', replacement: '', subject: $number);

            switch ($number) {
                case strlen(string: $number) == 10:
                    return '(' . substr(string: $number, offset: 0, length: 2) . ')' . substr(string: $number, offset: 2, length: 5) . '-' . substr(string: $number, offset: 7);

                case strlen(string: $number) == 11:
                    return '(' . substr(string: $number, offset: 0, length: 2) . ')' . substr(string: $number, offset: 2, length: 5) . '-' . substr(string: $number, offset: 7);
                
                default:
                    return $number;
            }
        }

        /**
         * Mascara um valor monetário.
         *
         * @param mixed $currency O valor monetário a ser mascarado.
         * @param string $identifier O identificador da moeda (opcional, padrão 'R$').
         * @return string O valor monetário mascarado.
         */
        public static function maskCurrency(mixed $currency, string $identifier = 'R$'):string {
            $masked_currency = number_format(num: $currency, decimals: 2, decimal_separator: ',', thousands_separator: '.');

            switch ($currency) {
                case null:
                    return '-';
                
                default:
                    return "{$identifier} {$masked_currency}";
            }
        }

        /**
        * Conta o número de dias entre a data fornecida e a data atual.
        *
        * @param string|null $date A data no formato 'Y-m-d H:i:s' a ser comparada com a data atual.
        * @return mixed O número de dias entre a data fornecida e a data atual.
        */
        public static function daysCounter(mixed $date1, mixed $date2 = null):?int {
            if (is_null(value: $date1)) {
                return null;
            }
        
            try {
                // Cria o objeto DateTime para a primeira data
                $firstDate = new \DateTime(datetime: $date1);
        
                // Define a segunda data como a atual, caso não seja informada
                $secondDate = is_null(value: $date2) ? new \DateTime() : new \DateTime(datetime: $date2);
        
                // Calcula a diferença entre as datas
                $dateDifference = $secondDate -> diff(targetObject: $firstDate);
        
                // Obtém o número de dias da diferença
                $days = $dateDifference -> days;
        
                return $days;
            } catch (\Exception $e) {
                // Trata erros caso as datas sejam inválidas
                return null;
            }
        }

        /**
         * Verifica se uma senha corresponde a um hash.
         *
         * Este método utiliza a função `password_verify()` do PHP para comparar uma senha
         * fornecida com um hash criptografado.
         *
         * @param string $password A senha a ser verificada.
         * @param string $hash O hash criptografado para comparação.
         * @return bool Retorna true se a senha corresponder ao hash, false caso contrário.
         */
        public static function matchPasswordHash(string $password, string $hash):bool {
            return password_verify(password: $password, hash: $hash);
        }

        /**
         * Retorna o nome do dia da semana atual por extenso.
         *
         * @return string O nome do dia da semana atual por extenso.
         */
        public static function hojeExtenso():string {
            return match(date(format: 'l')) {
                'Sunday' => 'Domingo',
                'Monday' => 'Segunda-Feira',
                'Tuesday' => 'Terça-Feira',
                'Wednesday' => 'Quarta-Feira',
                'Thursday' => 'Quinta-Feira',
                'Friday' => 'Sexta-Feira',
                'Saturday' => 'Sábado'
            };
        }

        /**
         * Prepara os resultados para paginação.
         *
         * Este método formata a resposta do banco de dados para incluir informações
         * úteis para a paginação, como os próprios resultados, o número total de resultados
         * e o limite por página.
         *
         * @param array $dbResponse A resposta do banco de dados contendo os resultados da consulta.
         * @param int|string $limit O limite de resultados por página. Pode ser um inteiro ou uma string numérica. O valor padrão é "10".
         * @return array Um array formatado para paginação, contendo 'response', 'totalResults' e 'limit'.
         */
        public static function preparePaginationResults(mixed $atuallPage, array $dbResponse, string $totalResults, mixed $limit = "10"):array {
            return [
                'atuallPage' => $atuallPage,
                'response' => $dbResponse,
                'totalResults' => floatval(value: $totalResults),
                'limit' => $limit
            ];
        }

        /**
         * Gera a estrutura HTML para uma paginação.
         *
         * Esta função cria os links de navegação para as páginas de resultados, considerando o número total de registros,
         * o limite de registros por página, a página atual e opções de personalização.
         *
         * @param mixed $pagina_atual A página atual.
         * @param int $limite O número máximo de registros por página.
         * @param mixed $total_registros O número total de registros.
         * @param int $max_links O número máximo de links de página a serem exibidos (opcional, padrão: 10).
         * @param string $container_class A classe CSS para o container da paginação (opcional, padrão: 'pagination justify-content-center').
         * @return string O HTML da paginação.
         */
        public static function generatePagination(mixed $pagina_atual, mixed $total_registros, int $limite = 10,): string {

            is_null(value: $pagina_atual) ? '1' : $pagina_atual;
            is_null(value: $total_registros) ? '0' : $total_registros;
        
            // Calcula o total de páginas
            $total_paginas = ceil(num: $total_registros / $limite);
        
            // Construir a query string com os parâmetros atuais, exceto 'page'
            $query_params = $_GET;
            unset($query_params['page']); // Remove 'page' para evitar duplicação
            unset($query_params['url']); // Remove 'url' para evitar duplicação
            $query_string = http_build_query(data: $query_params);
        
            // Limitar a quantidade máxima de botões a serem exibidos
            $max_botoes = 10;
            $inicio = max(1, $pagina_atual - intval($max_botoes / 2));
            $fim = min($total_paginas, $inicio + $max_botoes - 1);
        
            // Ajustar a janela de exibição se atingir o limite inferior ou superior
            if ($fim - $inicio + 1 < $max_botoes) {
                $inicio = max(1, $fim - $max_botoes + 1);
            }
        
            // Validação das paginações
            if($total_registros > $limite){
                // Inicia a criação do HTML da paginação
                $html = '<nav aria-label="Page navigation">';
                $html .= '<ul class="pagination justify-content-center">';
        
                // Botão Anterior (desabilitado na primeira página)
                if ($pagina_atual > 1) {
                    $anterior = $pagina_atual - 1;
                    $html .= '<li class="page-item">';
                    $html .= '<a class="page-link" href="?' . $query_string . '&page=' . $anterior . '">Anterior</a>';
                    $html .= '</li>';
                } else {
                    $html .= '<li class="page-item disabled">';
                    $html .= '<a class="page-link">Anterior</a>';
                    $html .= '</li>';
                }
        
                // Geração dos links de cada página dentro da janela definida
                for ($i = $inicio; $i <= $fim; $i++) {
                    if ($i == $pagina_atual) {
                        $html .= '<li class="page-item active">';
                        $html .= '<a class="page-link" href="?' . $query_string . '&page=' . $i . '">' . $i . '</a>';
                        $html .= '</li>';
                    } else {
                        $html .= '<li class="page-item">';
                        $html .= '<a class="page-link" href="?' . $query_string . '&page=' . $i . '">' . $i . '</a>';
                        $html .= '</li>';
                    }
                }
        
                // Botão Próximo (desabilitado na última página)
                if ($pagina_atual < $total_paginas) {
                    $proxima = $pagina_atual + 1;
                    $html .= '<li class="page-item">';
                    $html .= '<a class="page-link" href="?' . $query_string . '&page=' . $proxima . '">Próximo</a>';
                    $html .= '</li>';
                } else {
                    $html .= '<li class="page-item disabled">';
                    $html .= '<a class="page-link">Próximo</a>';
                    $html .= '</li>';
                }
        
                $html .= '</ul>';
                $html .= '</nav>';
            } else {
                $html = "";
            }
        
            return $html;
        }

        public static function generateStatusBadges(int $status_id, array $statusList):void {
            foreach ($statusList as $status) {
                if($status_id == $status['id']) {
                    $badge_color = $status['badge-color'];
                    $status_text = $status['status'];
                }
            }

            echo "<span class='badge rounded-pill text-bg-{$badge_color}'>{$status_text}</span>";
        }

        /**
         * Gera uma chave aleatória alfanumérica.
         *
         * Este método gera uma string aleatória de um determinado comprimento, contendo
         * caracteres alfanuméricos (letras maiúsculas e minúsculas e números).
         *
         * @param int $length O comprimento da chave a ser gerada.
         * @return string A chave aleatória gerada, ou uma string vazia em caso de erro.
         */
        public function generateRandomKey(int $length):string {
            // Definir o conjunto de caracteres possíveis
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            
            // Inicializar a chave vazia
            $chave = '';
            
            // Gerar a chave aleatória
            for ($i = 0; $i < $length; $i++) {
                $chave .= $caracteres[random_int(min: 0, max: strlen(string: $caracteres) - 1)];
            }
            
            return $chave;
        }
    }