<?php
include 'db_connect.php';
session_start();

if (!isset($_GET['MaSV'])) {
    header("Location: index.php");
    exit();
}

$MaSV = $_GET['MaSV'];

// Lấy thông tin sinh viên để hiển thị
$query = "SELECT * FROM SinhVien WHERE MaSV='$MaSV'";
$result = mysqli_query($conn, $query);
$sv = mysqli_fetch_assoc($result);

if (!$sv) {
    echo "Không tìm thấy sinh viên!";
    exit();
}

// Xử lý khi nhấn nút xác nhận xóa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql_delete = "DELETE FROM SinhVien WHERE MaSV='$MaSV'";
    if (mysqli_query($conn, $sql_delete)) {
        echo "<script>alert('Xóa sinh viên thành công!'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận xóa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-danger {
            background-color: red;
            color: white;
        }
        .btn-cancel {
            background-color: gray;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xác nhận xóa sinh viên</h2>
        <p>Bạn có chắc chắn muốn xóa sinh viên <strong><?php echo $sv['HoTen']; ?></strong> không?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="index.php" class="btn btn-cancel">Hủy</a>
        </form>
    </div>
</body>
</html>
