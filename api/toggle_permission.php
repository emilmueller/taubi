<?php
    include_once '../config.php';

    $role_id = $_POST['role_id'];
    $permission_id = $_POST['permission_id'];

    $stmt = $conn->prepare("SELECT * FROM role_permissions WHERE role_id = ? AND permission_id = ?");
    $stmt->bind_param('ii', $role_id, $permission_id);
    $stmt->execute();
    $result=$stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $stmt = $conn->prepare('DELETE FROM role_permissions WHERE role_id = ? AND permission_id = ?');
        $stmt->bind_param('ii',$role_id, $permission_id);
        $stmt->execute();        
        echo json_encode(['success' => true, 'assigned' => false]);
        exit;
    } else {
        $stmt = $conn->prepare('INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)');
        $stmt->bind_param('ii',$role_id, $permission_id);
        $stmt->execute();        
        echo json_encode(['success' => true, 'assigned' => true]);
        exit;
    }
?>