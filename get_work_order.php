<?php
require 'db_connect.php';
$work_order_id = $_POST["work_order_id"];
$stmt = $conn->prepare("SELECT * FROM work_orders WHERE work_order_id = ?");
$stmt->bind_param("i", $work_order_id);
$stmt->execute();
$result = $stmt->get_result();
echo json_encode($result->fetch_assoc());
?>
