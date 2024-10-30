<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=users", "root", "");
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
// isso aqui foi só pra testar a conexão mesmo, voce pode reutiliazar pra testar, mas
// fique atenta as credenciais de acesso ai banco.
?>
