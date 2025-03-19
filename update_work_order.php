<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $work_id = intval($_POST["id"]);
    $work_date = $_POST["work_date"];

    // อัปเดต work_orders
    $sql = "UPDATE work_orders SET work_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $work_date, $work_id);
    $stmt->execute();

    // ลบ items เดิมก่อน
    $sql = "DELETE FROM items WHERE work_order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $work_id);
    $stmt->execute();

    // เพิ่ม items ใหม่
    foreach ($_POST["row_number"] as $index => $row_number) {
        $item_name = $_POST["item_name"][$index];
        $quality = $_POST["quality"][$index];
        $unit = $_POST["unit"][$index];
        $type = $_POST["type"][$index];
        $note = $_POST["note"][$index];

        $sql = "INSERT INTO items (work_order_id, row_number, item_name, quality, unit, type, note) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssss", $work_id, $row_number, $item_name, $quality, $unit, $type, $note);
        $stmt->execute();
    }

    echo "success";
}
?>
