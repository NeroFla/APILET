<?php
// aqui é onde faz a conexaão com banco de dados, dependedo do que configurou ai, 
// recomendo usar o XAMMP.
// coloque os arquivos da api dentro da pasta htdocs, os arquivos do front podem ficar em outra pasta, 
// por exemplo na area de trabalho, voce só vai precisar espessificar o caminho em alguns casos de roteamento.
class Database {
    private $host = "localhost";
    private $db_name = "users";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
