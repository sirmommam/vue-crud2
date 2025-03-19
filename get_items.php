<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $work_order_id = $_POST["work_order_id"];
    $stmt = $conn->prepare("SELECT * FROM items WHERE work_order_id = ?");
    $stmt->bind_param("i", $work_order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td><input type='hidden' class='item-id' value='{$row['item_id']}'><input type='text' class='row-number' value='{$row['row_number']}'></td>
            <td><input type='text' class='item_name' value='{$row['item_name']}'></td>
            <td><input type='text' class='quality' value='{$row['quality']}'></td>
            <td><input type='text' class='unit' value='{$row['unit']}'></td>
            <td><input type='text' class='type' value='{$row['type']}'></td>
            <td><input type='text' class='note' value='{$row['note']}'></td>
            <td><button class='delete-item' data-id='{$row['item_id']}'>ลบ</button></td>
        </tr>";
    }
}
?>
