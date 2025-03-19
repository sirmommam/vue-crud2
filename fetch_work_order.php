<?php
require "db.php";

if (isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    $sql = "SELECT * FROM work_orders WHERE work_order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "success", "data" => $result->fetch_assoc()]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>
