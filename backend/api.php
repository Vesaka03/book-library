<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON response header
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Database configuration from environment variables
    $dbHost = getenv('DB_HOST') ?: 'db';
    $dbName = getenv('DB_NAME') ?: 'book_library';
    $dbUser = getenv('DB_USER') ?: 'root';
    $dbPassword = getenv('DB_PASSWORD') ?: 'rootpassword';

    // Connect to MySQL database using PDO
    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPassword,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );

    // Get action from GET parameter
    $action = $_GET['action'] ?? '';

    // API Endpoint 1: GET all books
    if ($action === 'getBooks') {
        $stmt = $pdo->query('SELECT id, title, author, year FROM books ORDER BY id DESC');
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'books' => $books]);
    }
    // API Endpoint 2: POST new book
    elseif ($action === 'addBook') {
        // Ensure it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
        }

        // Parse JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate input
        if (!isset($data['title'], $data['author'], $data['year'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit();
        }

        // Insert book into database
        $stmt = $pdo->prepare('INSERT INTO books (title, author, year) VALUES (?, ?, ?)');
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['year']
        ]);

        echo json_encode(['success' => true, 'message' => 'Book added successfully']);
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
