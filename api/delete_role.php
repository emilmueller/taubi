<?php
include "../config.php";
$role_id=$_POST["id"];
$sql="DELETE FROM roles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $role_id);
$res = $stmt->execute();

$sql = "DELETE FROM role_permissions WHERE role_id= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $role_id);

$res = $res && $stmt->execute();

if($res){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true]);
    
}else{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message'=> 'Rolle konnte nicht gelöscht werden.']);
    
}
$conn->close();
?>