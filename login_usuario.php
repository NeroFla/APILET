<?php
header("Content-Type: application/json");
include_once 'db.php'; // Inclui a configuração do banco de dados talvez funcione ai, pq aqui não funciona, nem o chat gpt deu jeito nessa merda que eu fiz
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->login) || !isset($data->senha)) {
        echo json_encode(["mensagem" => "Campos 'login' e 'senha' são obrigatórios."]);
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE login = :login";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':login', $data->login);
    $stmt->execute();


    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        // Comparando a senha sem criptografia pq eu esqueci e fiz merda, talvez isso não seja relevante né
        if ($data->senha === $usuario['senha_hash']) { 
            echo json_encode(["mensagem" => "Login bem-sucedido."]);
        } else {
            echo json_encode(["mensagem" => "Usuário ou senha incorretos."]);
        }
    } else {
        echo json_encode(["mensagem" => "Usuário ou senha incorretos."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["mensagem" => "Método não permitido"]);
}
?>
