<?php
// essa aqui é sua API, ou quase isso, basicamente as rotas ($uri) apontam para um arquivo de objetivo espessifico
// coloquei os dois verbos basicos, post e get
// isso aqui é uma gambiarra do caralho, uma mistura de esquizofrenia e chat gpt. mas ate certo ponto funciona.

header("Content-Type: application/json");

// Incluir a configuração do banco de dados

include_once 'db.php';
$database = new Database();
$db = $database->getConnection();

// Detectar o método da requisição HTTP
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Roteamento básico para a API de usuários
if ($uri === '/APIALLMARKETESPHP/cadastrar_usuario') {
    if ($method === 'POST') {
        // Rota para criar um novo usuário
        $data = json_decode(file_get_contents("php://input"));

        // Verificação para evitar valores nulos
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["mensagem" => "Erro ao decodificar JSON."]);
            exit;
        }

        // Verifique se os campos obrigatórios estão presentes
        if (!isset($data->nome_completo) || !isset($data->email)) {
            echo json_encode(["mensagem" => "Campos 'nome_completo' e 'email' são obrigatórios."]);
            exit;
        }

        // Prepara a declaração SQL com verificação de erros
        $sql = "INSERT INTO usuarios (nome_completo, email) VALUES (:nome_completo, :email)";
        $stmt = $db->prepare($sql);
        
        try {
            $stmt->bindParam(':nome_completo', $data->nome_completo);
            $stmt->bindParam(':email', $data->email);

            // Tenta executar a consulta
            if ($stmt->execute()) {
                echo json_encode(["mensagem" => "Usuário criado com sucesso"]);
            } else {
                echo json_encode(["mensagem" => "Erro ao executar a inserção no banco de dados"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["mensagem" => "Erro SQL: " . $e->getMessage()]);
        }

    } elseif ($method === 'GET') {
        // Rota para listar todos os usuários
        $stmt = $db->prepare("SELECT * FROM usuarios");
        
        try {
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($usuarios);
        } catch (PDOException $e) {
            echo json_encode(["mensagem" => "Erro ao buscar usuários: " . $e->getMessage()]);
        }
        
    } else {
        // Método não permitido
        http_response_code(405);
        echo json_encode(["mensagem" => "Método não permitido"]);
    }
} else {
    // Rota não encontrada
    http_response_code(404);
    echo json_encode(["mensagem" => "Rota não encontrada"]);
}
?>
