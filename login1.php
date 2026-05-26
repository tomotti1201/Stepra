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

// ユーザー名、email、パスワードを受け取る
$name = trim((string) ($input['name'] ?? ''));
$email = trim((string) ($input['email'] ?? ''));
$password = (string) ($input['password'] ?? '');

// 通常のログインは email と password を使いますが、安全のためチェックします
if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'email と password は必須です',
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // データベースから email に一致するユーザーを検索します
    $sql = 'SELECT id, name, email, password FROM users WHERE email = ? LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ユーザーが存在し、パスワードが一致するか（password_verify）チェックします
    if ($user && password_verify($password, $user['password'])) {

        // リクエストにユーザー名が含まれていて、DBの名前と違う場合はエラーにする（オプション）
        // ※ 通常のログインはemailとパスワードだけで判定するため、この処理は不要であれば消してください
        if ($name !== '' && $user['name'] !== $name) {
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'ユーザー名が一致しません',
            ], JSON_UNESCAPED_UNICODE);
            exit();
        }

        // 成功時にはパスワード情報を削除して安全にユーザー情報を返す
        unset($user['password']);

        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'ログインに成功しました',
            'user' => $user
        ], JSON_UNESCAPED_UNICODE);
    } else {
        // email間違い、またはパスワード間違い
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'メールアドレスまたはパスワードが間違っています',
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'データベースエラー',
    ], JSON_UNESCAPED_UNICODE);
}
?>