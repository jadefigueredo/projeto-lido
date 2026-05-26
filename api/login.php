<?php
header('Content-Type: application/json');
// header('Access-Control-Allow-Origin: http://localhost');
// tá tudo na mesma origem, talvez eu não precise dele aqui mas lembrar que pode ser útil

require 'db.php';

// Lê o JSON enviado pelo JS
$data = json_decode(file_get_contents('php://input'), true);

$dataNascimento  = trim($data['birthdate'] ?? '');
$email           = trim($data['email'] ?? '');
$nome  = trim($data['fullname'] ?? '');
$senha = $data['password'] ?? '';
$confirmarSenha  = $data['confirm_password'] ?? '';

if (!$email || !$senha) {
    http_response_code(400);
    echo json_encode(['error' => 'Campos obrigatórios']);
    exit;
}
// lembrar que o PDO evita SQL injection
$stmt = $pdo->prepare('SELECT id, nome, senha_hash FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Email ou senha incorretos']);
    exit;
}

echo json_encode([
    'success' => true,
    'nome' => $usuario['nome']
]);