# SimplePay Module Installation

1. Add to your application via composer:
    ```bash
    composer require vanilo/simplepay 
    ```
2. Add the module to `config/concord.php`:
    ```php
    <?php
    return [
        'modules' => [
             //...
             Vanilo\Simplepay\Providers\ModuleServiceProvider::class
             //...
        ]
    ]; 
    ```

---

**Next**: [Configuration &raquo;](configuration.md)
