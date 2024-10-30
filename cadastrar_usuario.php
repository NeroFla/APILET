<?php
header("Content-Type: application/json");

// Incluir a configuração do banco de dados
include_once 'db.php';
$database = new Database();
$db = $database->getConnection();

// Confirmar que a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["mensagem" => "Método não permitido"]);
    exit;
}

// Capturar e validar os dados recebidos via POST
$nome = htmlspecialchars(trim($_POST['nome_completo'] ?? ''));
$sexo = htmlspecialchars(trim($_POST['sexo'] ?? ''));
$dataNascimento = htmlspecialchars(trim($_POST['data_nascimento'] ?? ''));
$cpf = htmlspecialchars(trim($_POST['cpf'] ?? ''));
$nomeMaterno = htmlspecialchars(trim($_POST['nome_materno'] ?? ''));
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$telefoneCelular = htmlspecialchars(trim($_POST['telefone_celular'] ?? ''));
$telefoneFixo = htmlspecialchars(trim($_POST['telefone_fixo'] ?? ''));
$endereco = htmlspecialchars(trim($_POST['endereco_completo'] ?? ''));
$login = htmlspecialchars(trim($_POST['login'] ?? ''));
$senha = htmlspecialchars(trim($_POST['senha_hash'] ?? ''));
$confirmacaoSenha = htmlspecialchars(trim($_POST['confirmacao_senha'] ?? ''));

// Verificar se os campos obrigatórios foram preenchidos corretamente
if (empty($nome) || empty($email) || empty($senha) || empty($confirmacaoSenha)) {
    echo json_encode(["mensagem" => "Campos obrigatórios ausentes ou inválidos."]);
    exit;
}

// Exibir os dados para diagnóstico
echo json_encode([
    "nome" => $nome,
    "email" => $email,
    "senha" => $senha,
    "confirmacaoSenha" => $confirmacaoSenha,
    "outros_dados" => [
        "sexo" => $sexo,
        "data_nascimento" => $dataNascimento,
        "cpf" => $cpf,
        "nome_materno" => $nomeMaterno,
        "telefone_celular" => $telefoneCelular,
        "telefone_fixo" => $telefoneFixo,
        "endereco" => $endereco,
        "login" => $login
    ]
]);

// Descomentar as linhas a seguir após verificar os dados

if ($senha !== $confirmacaoSenha) {
    echo json_encode(["mensagem" => "As senhas não coincidem."]);
    exit;
}

// Inserir o usuário no banco de dados
$stmt = $db->prepare("INSERT INTO usuarios (nome_completo, sexo, data_nascimento, cpf, nome_materno, email, telefone_celular, telefone_fixo, endereco_completo, login, senha_hash) 
                      VALUES (:nome_completo, :sexo, :data_nascimento, :cpf, :nome_materno, :email, :telefone_celular, :telefone_fixo, :endereco_completo, :login, :senha_hash)");
$stmt->bindParam(':nome_completo', $nome);
$stmt->bindParam(':sexo', $sexo);
$stmt->bindParam(':data_nascimento', $dataNascimento);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':nome_materno', $nomeMaterno);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':telefone_celular', $telefoneCelular);
$stmt->bindParam(':telefone_fixo', $telefoneFixo);
$stmt->bindParam(':endereco_completo', $endereco);
$stmt->bindParam(':login', $login);
$stmt->bindParam(':senha_hash', $senha);

if ($stmt->execute()) {
    echo json_encode(["mensagem" => "Usuário criado com sucesso"]);
} else {
    echo json_encode(["mensagem" => "Erro ao criar usuário"]);
}

?>
