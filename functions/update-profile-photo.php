<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

if (!isset($_FILES['profile_photo'])) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
    exit;
}

$userId = $_SESSION['user_id'];
$file = $_FILES['profile_photo'];
$fileName = $file['name'];
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($fileExt, $allowedExt)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    exit;
}

$uploadDir = '../profile-photo/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Generate unique filename
$newFileName = $userId . '_' . time() . '.' . $fileExt;
$filePath = $uploadDir . $newFileName;

require_once 'firebase-service.php';

$firebaseService = new FirebaseService();

try {
    // Fetch current user data
    $user = $firebaseService->getUserById($userId);
    $currentAvatar = $user['avatar'] ?? null;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $relativeFilePath = 'profile-photo/' . $newFileName;

        // Update user avatar in Firebase
        $updateData = ['avatar' => $relativeFilePath];
        if ($firebaseService->updateUser($userId, $updateData)) {
            if ($currentAvatar && $currentAvatar !== 'profile-photo/default-avatar.png') {
                $oldFilePath = '../' . $currentAvatar;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Profile photo updated successfully',
                'filepath' => $relativeFilePath
            ]);
        } else {
            throw new Exception('Failed to update user avatar in Firebase');
        }
    } else {
        throw new Exception('Failed to upload file');
    }
} catch (Exception $e) {
    // Delete uploaded file if it exists
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>