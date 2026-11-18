<?php

session_start();

$envPath = __DIR__ . '/../../.env';

if (file_exists($envPath)) {
    $env = parse_ini_file($envPath, false, INI_SCANNER_RAW);

    if ($env !== false) {
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Recuperar variáveis de ambiente
$host     = $_ENV["DB_HOST"] ?? getenv("DB_HOST");
$dbname   = $_ENV["DB_NAME"] ?? getenv("DB_NAME");
$username = $_ENV["DB_USER"] ?? getenv("DB_USER");
$password = $_ENV["DB_PASS"] ?? getenv("DB_PASS");
$sslmode  = $_ENV["DB_SSLMODE"] ?? getenv("DB_SSLMODE") ?: "require";

try {

    $pdo = new PDO(
        "pgsql:host=$host;port=5432;dbname=$dbname;sslmode=$sslmode",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

} catch (PDOException $e) {

    error_log("Erro de conexão com PostgreSQL: " . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        "erro" => "Erro na conexão com o banco de dados.",
        "detalhes" => "$e"
    ]);

    exit;
}