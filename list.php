<?php
require "db.php"; // ไฟล์เชื่อมต่อฐานข้อมูล

$sql = "SELECT * FROM work_orders ORDER BY work_order_id DESC";
$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row["work_order_id"] . "</td>";
    echo "<td>" . $row["work_date"] . "</td>";
    echo "<td><button class='btn btn-warning edit-work' data-id='" . $row["work_order_id"] . "'>แก้ไข</button></td>";
    echo "<td><button class='btn btn-danger delete-work' data-id='" . $row["work_order_id"] . "'>ลบ</button></td>";
    echo "</tr>";
}
?>
