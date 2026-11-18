<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require "conexao.php";

$dados = json_decode(file_get_contents("php://input"), true);

$nome  = trim($dados["nome"] ?? "");
$email = trim($dados["email"] ?? "");
$senha = trim($dados["senha"] ?? "");

if (!$nome || !$email || !$senha) {
  echo json_encode(["erro" => "Preencha todos os campos."]);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["erro" => "E-mail inválido."]);
  exit;
}

if (strlen($senha) < 8) {
  echo json_encode(["erro" => "Senha deve ter pelo menos 8 caracteres."]);
  exit; 
}

$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
  echo json_encode(["erro" => "E-mail já cadastrado."]);
  exit;
}

$hash = password_hash($senha, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->execute([$nome, $email, $hash]);

echo json_encode(["sucesso" => "Cadastro realizado."]);
