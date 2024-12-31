<?php
header('Content-Type: application/json');

$pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=blog', getenv('DB_USER'), getenv('DB_PASSWORD'));

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (strlen($data['title']) > 50) {
            echo json_encode(['error' => 'Title exceeds 50 characters']);
            http_response_code(400);
            exit;
        }
        $stmt = $pdo->prepare('INSERT INTO posts (title, content) VALUES (?, ?)');
        $stmt->execute([$data['title'], $data['content']]);
        echo json_encode(['message' => 'Post created']);
        break;
    case 'GET':
        $stmt = $pdo->query('SELECT * FROM posts');
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($posts);
        break;
    // Add other CRUD operations
}
?>