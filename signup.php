<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method Not Allowed',
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

require_once __DIR__ . '/db_connect.php';

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!is_array($input)) {
    $input = $_POST;
}

$name = trim((string) ($input['name'] ?? ''));
$email = trim((string) ($input['email'] ?? ''));
$password = (string) ($input['password'] ?? '');

if ($name === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'name, email, password are required',
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email format',
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Password must be at least 8 characters',
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    $checkSql = 'SELECT id FROM users WHERE email = ? LIMIT 1';
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$email]);

    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            'status' => 'error',
            'message' => 'Email already exists',
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertSql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->execute([$name, $email, $hashedPassword]);

    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'User registered successfully',
        'user' => [
            'id' => (int) $pdo->lastInsertId(),
            'name' => $name,
            'email' => $email,
        ],
    ], JSON_UNESCAPED_UNICODE);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error',
    ], JSON_UNESCAPED_UNICODE);
}
?>