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

$email = trim($dados["email"] ?? "");
$senha =      $dados["senha"] ?? "";

if (!$email || !$senha) {
  http_response_code(400);
  echo json_encode(["erro" => "Preencha e-mail e senha."]);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if (!$usuario || !password_verify($senha, $usuario["senha"])) {
  http_response_code(401);
  echo json_encode(["erro" => "E-mail ou senha incorretos."]);
  exit;
}

$_SESSION["usuario_id"] = $usuario["id"];
$_SESSION["nome"]       = $usuario["nome"];

echo json_encode([
  "mensagem" => "Login realizado.",
  "nome"     => $usuario["nome"]
]);
