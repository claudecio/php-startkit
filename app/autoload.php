<?php
    /**
     * Função de autoload para carregar automaticamente classes do namespace 'app'.
     *
     * @param string $class O nome completo da classe com namespace.
     * @return void
     */
    spl_autoload_register(callback: function ($class):void {
        $prefix = 'app\\';
        $base_dir = __DIR__ . '/';

        $len = strlen(string: $prefix);
        if (strncmp(string1: $prefix, string2: $class, length: $len) !== 0) {
            return;
        }

        $relative_class = substr(string: $class, offset: $len);
        $file = $base_dir . str_replace(search: '\\', replace: '/', subject: $relative_class) . '.php';

        if (file_exists(filename: $file)) {
            require $file;
        }
    });