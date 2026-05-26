<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$nome            = trim($data['fullname'] ?? '');
$dataNascimento  = trim($data['birthdate'] ?? '');
$email           = trim($data['email'] ?? '');
$senha           = $data['password'] ?? '';
$confirmarSenha  = $data['confirm_password'] ?? '';

if (!$nome || !$dataNascimento || !$email || !$senha || !$confirmarSenha) {
    http_response_code(400);
    echo json_encode(['error' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

if ($senha !== $confirmarSenha) {
    http_response_code(400);
    echo json_encode(['error' => 'As senhas não coincidem.']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Este email já está cadastrado.']);
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nome, email, data_nascimento, senha_hash)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$nome, $email, $dataNascimento, $senhaHash]);

// lembrar que aqui eu tô recebendo apenas o usuário created sem a tela do front -> lembrar de conferir no payload
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Usuario cadastrado com sucesso.',
        'user' => [
            'id' => $pdo->lastInsertId(),
            'nome' => $nome,
            'email' => $email
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno ao cadastrar usuário.']);
}