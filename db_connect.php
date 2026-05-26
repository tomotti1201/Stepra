<?php
$host = 'localhost'; // ホスト名（例: localhost または IPアドレス）
$db = 'stepra_db'; // データベース名
$user = 'root'; // ユーザー名
$pass = '1201'; // パスワード
$charset = 'utf8mb4'; // 文字セット

// データソース名（DSN）の設定
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDOのオプション設定
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラー発生時に例外を投げる
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // デフォルトのフェッチモードを連想配列に設定
    PDO::ATTR_EMULATE_PREPARES => false,                  // プリペアドステートメントのエミュレーションを無効化（セキュリティ向上）
];

try {
    // データベースへの接続
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // 接続失敗時のエラーハンドリング
    echo "接続エラー: " . $e->getMessage() . "\n";
    exit;
}
?>