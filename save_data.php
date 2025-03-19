<?php
require "db.php";

$work_date = $_POST["work_date"];
$conn->query("INSERT INTO work_orders (work_date) VALUES ('$work_date')");
$work_order_id = $conn->insert_id;

foreach ($_POST["row_number"] as $i => $row_number) {
    $item_name = $_POST["item_name"][$i];
    $quality = $_POST["quality"][$i];
    $unit = $_POST["unit"][$i] === "other" ? $_POST["new_unit"][$i] : $_POST["unit"][$i];
    $type = $_POST["type"][$i] === "other" ? $_POST["new_type"][$i] : $_POST["type"][$i];
    $note = $_POST["note"][$i];

    $conn->query("INSERT INTO items (work_order_id, row_number, item_name, quality, unit, type, note) 
                  VALUES ('$work_order_id', '$row_number', '$item_name', '$quality', '$unit', '$type', '$note')");
}

echo "บันทึกสำเร็จ!";
?>
