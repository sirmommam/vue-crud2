<?php
require "db.php";

if (isset($_POST["work_order_id"])) {
    $work_order_id = intval($_POST["work_order_id"]);
    $sql = "SELECT * FROM items WHERE work_order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $work_order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td><input type='text' class='form-control row-number' name='row_number[]' value='{$row["row_number"]}'></td>
            <td><input type='text' class='form-control' name='item_name[]' value='{$row["item_name"]}'></td>
            <td><input type='number' class='form-control' name='quality[]' value='{$row["quality"]}'></td>
            <td><input type='text' class='form-control' name='unit[]' value='{$row["unit"]}'></td>
            <td><input type='text' class='form-control' name='type[]' value='{$row["type"]}'></td>
            <td><input type='text' class='form-control' name='note[]' value='{$row["note"]}'></td>
            <td><button type='button' class='btn btn-danger remove-item' data-id='{$row["work_order_id"]}'>ลบ</button></td>
        </tr>";
    }
}
?>
