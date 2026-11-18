<?php
session_start();
require_once "conexao.php";
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION["usuario_id"])) {
  http_response_code(401);
  echo json_encode(["erro" => "Não autorizado."]);
  exit;
}

$stmt = $pdo->prepare(
  "SELECT id, titulo, conteudo, criado_em FROM notas WHERE usuario_id = ? ORDER BY criado_em DESC"
);
$stmt->execute([$_SESSION["usuario_id"]]);

echo json_encode($stmt->fetchAll());

