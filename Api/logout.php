<?php
session_start();
require_once "conexao.php";
header("Content-Type: application/json; charset=UTF-8");

session_destroy();
echo json_encode(["mensagem" => "Logout realizado."]);
