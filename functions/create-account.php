<?php
require_once 'dbconnection.php';
header('Content-Type: application/json');

function generateUsername($fname, $lname, $conn) {
    // Create base username from first letter of firstname + lastname
    $base_username = strtolower(substr($fname, 0, 1) . $lname);
    $base_username = preg_replace('/[^a-z0-9]/', '', $base_username);
    
    $username = $base_username;
    $counter = 1;
    
    // Check if username exists and append number if it does
    while(true) {
        $check = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows == 0) {
            $check->close();
            return $username;
        }
        
        $username = $base_username . $counter;
        $counter++;
    }
}

// Validate the input
if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) || 
    empty($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

try {
    // Generate username
    $username = generateUsername($_POST['fname'], $_POST['lname'], $conn);

    // Check if email exists
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $_POST['email']);
    $check_email->execute();
    $result = $check_email->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        exit;
    }

    // Insert new user
    $sql = "INSERT INTO users (fname, lname, email, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $stmt->bind_param("sssss", 
        $_POST['fname'],
        $_POST['lname'],
        $_POST['email'],
        $username,
        $hashed_password
    );

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Account created successfully',
            'username' => $username
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create account']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if(isset($stmt)) $stmt->close();
    $conn->close();
}
?>