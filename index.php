<?php
require "db.php"; // ไฟล์เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลประเทศจากฐานข้อมูล
$countries = $conn->query("SELECT country_name FROM countries");
$units = $conn->query("SELECT unit_name FROM units");
$types = $conn->query("SELECT type_name FROM types");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบใบงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</head>
<body class="container mt-4">

    <h2>สร้างใบงาน!!</h2>

    <!-- ฟอร์มสร้างใบงาน -->
    <form id="workOrderForm">
        <label>วันที่:</label>
        <input type="date" name="work_date" id="work_date" class="form-control mb-3" required>

        <h4>รายการ</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รายการ</th>
                    <th>Quality</th>
                    <th>หน่วย</th>
                    <th>ประเภท</th>
                    <th>หมายเหตุ</th>
                    <th>ลบ</th>
                </tr>
            </thead>
            <tbody id="itemsTable"></tbody>
        </table>

        <button type="button" id="addRow" class="btn btn-success">Add Row</button>
        <button type="submit" class="btn btn-primary">บันทึก</button>
    </form>

    <h2 class="mt-5">รายการใบงาน</h2>
    <table class="table">
        <thead>
            <tr>
                <th>รหัส</th>
                <th>วันที่</th>
                <th>แก้ไข</th>
                <th>ลบ</th>
            </tr>
        </thead>
        <tbody id="workOrders"></tbody>
    </table>

  <!-- Modal แก้ไขใบงาน -->
<div class="modal fade" id="edit-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">แก้ไขใบงาน</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-form">
                    <input type="hidden" id="edit-work-id" name="id">
                    
                    <div class="form-group">
                        <label>วันที่</label>
                        <input type="date" id="edit-work-date" name="work_date" class="form-control">
                    </div>

                    <h5>รายการ</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>รายการ</th>
                                <th>Quality</th>
                                <th>หน่วย</th>
                                <th>ประเภท</th>
                                <th>หมายเหตุ</th>
                                <th>ลบ</th>
                            </tr>
                        </thead>
                        <tbody id="edit-items-body">
                            <!-- โหลดรายการจาก fetch_items.php -->
                        </tbody>
                    </table>
                    <button type="button" id="add-item" class="btn btn-success">+ เพิ่ม</button>

                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on("click", ".edit-work", function() {
    var work_id = $(this).data("id");
    
    $.ajax({
        url: "fetch_work_order.php",
        type: "POST",
        data: { id: work_id },
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                $("#edit-work-id").val(response.data.work_order_id);
                $("#edit-work-date").val(response.data.work_date);

                // โหลดรายการ items
                //alert(response.data.work_order_id);
                loadItems(response.data.work_order_id);

                $("#edit-modal").modal("show");
            } else {
                alert("ไม่พบข้อมูลใบงาน");
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

// โหลดรายการ items
function loadItems(work_id) {
    $.ajax({
        url: "fetch_items.php",
        type: "POST",
        data: { work_order_id: work_id },
        success: function(data) {
            $("#edit-items-body").html(data);
        }
    });
}    
    $("#edit-form").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "update_work_order.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                alert("อัปเดตใบงานสำเร็จ");
                $("#edit-modal").modal("hide");
                location.reload(); // รีเฟรชหน้า
            }
        });
    });
    $(document).on("click", "#add-item", function() {
    var newRow = `<tr>
        <td><input type='text' class='form-control row-number' name='row_number[]'></td>
        <td><input type='text' class='form-control' name='item_name[]'></td>
            <td><input type='number' class='form-control' name='quality[]'></td>
            <td><input type='text' class='form-control' name='unit[]'></td>
            <td><input type='text' class='form-control' name='type[]'></td>
            <td><input type='text' class='form-control' name='note[]'></td>
            <td><button type='button' class='btn btn-danger remove-item'>ลบ</button></td>
        </tr>`;
        $("#edit-items-body").append(newRow);
    });

    // ลบแถวรายการ
    $(document).on("click", ".remove-item", function() {
        $(this).closest("tr").remove();
    });

</script>
    <script>
        $(document).ready(function () {
            loadWorkOrders();

            $("#addRow").click(function () {
                let rowIndex = $("#itemsTable tr").length + 1;
                let newRow = `
                    <tr>
                        <td><input type="text" class="form-control row-number" name="row_number[]" value="${rowIndex}" readonly></td>
                        <td><input type="text" class="form-control item_name" name="item_name[]" required></td>
                        <td><input type="number" class="form-control quality" name="quality[]" required></td>
                        <td>
                            <select class="form-control unit select2" name="unit[]">
                                <option value="kg">กิโลกรัม</option>
                                <option value="litre">ลิตร</option>
                                <option value="other">อื่นๆ</option>
                            </select>
                            <input type="text" class="form-control new-unit d-none" name="new_unit" placeholder="กรอกหน่วยใหม่">
                        </td>
                        <td>
                            <select class="form-control type select2" name="type[]">
                                <option value="food">อาหาร</option>
                                <option value="equipment">อุปกรณ์</option>
                                <option value="other">อื่นๆ</option>
                            </select>
                            <input type="text" class="form-control new-type d-none" name="new_type" placeholder="กรอกประเภทใหม่">
                        </td>
                        <td><input type="text" class="form-control note" name="note[]"></td>
                        <td><button type="button" class="btn btn-danger removeRow">ลบ</button></td>
                    </tr>`;

                $("#itemsTable").append(newRow);
                $(".select2").select2();
            });

            $(document).on("click", ".removeRow", function () {
                $(this).closest("tr").remove();
            });

            $(document).on("change", ".unit", function () {
                $(this).next(".new-unit").toggleClass("d-none", $(this).val() !== "other").focus();
            });

            $(document).on("change", ".type", function () {
                $(this).next(".new-type").toggleClass("d-none", $(this).val() !== "other").focus();
            });

            $("#workOrderForm").submit(function (e) {
                e.preventDefault();
                $.post("save_data.php", $(this).serialize(), function () {
                    alert("บันทึกสำเร็จ!");
                    loadWorkOrders();
                });
            });

            function loadWorkOrders() {
                $.get("list.php", function (data) {
                    $("#workOrders").html(data);
                });
            }
        });
    </script>
</body>
</html>
