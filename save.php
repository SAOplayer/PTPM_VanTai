<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];
$NgayDK = date('Y-m-d');

if (!empty($_SESSION['cart'])) {
    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    mysqli_begin_transaction($conn);

    try {
        // Thêm vào bảng DangKy
        $sql_dk = "INSERT INTO DangKy (NgayDK, MaSV) VALUES ('$NgayDK', '$MaSV')";
        if (!mysqli_query($conn, $sql_dk)) {
            throw new Exception("Lỗi khi thêm vào bảng DangKy: " . mysqli_error($conn));
        }

        $MaDK = mysqli_insert_id($conn); // Lấy ID của lần đăng ký vừa tạo

        foreach ($_SESSION['cart'] as $MaHP) {
            // Kiểm tra số lượng còn lại
            $check = mysqli_query($conn, "SELECT SoLuongDuKien FROM HocPhan WHERE MaHP='$MaHP'");
            $row = mysqli_fetch_assoc($check);

            if ($row['SoLuongDuKien'] > 0) {
                // Thêm vào bảng ChiTietDangKy
                $sql_ct = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES ('$MaDK', '$MaHP')";
                if (!mysqli_query($conn, $sql_ct)) {
                    throw new Exception("Lỗi khi thêm vào ChiTietDangKy: " . mysqli_error($conn));
                }

                // Cập nhật số lượng dự kiến
                $sql_update = "UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien - 1 WHERE MaHP='$MaHP'";
                if (!mysqli_query($conn, $sql_update)) {
                    throw new Exception("Lỗi khi cập nhật số lượng học phần: " . mysqli_error($conn));
                }
            } else {
                throw new Exception("Học phần $MaHP đã hết chỗ.");
            }
        }

        // Nếu không có lỗi, commit transaction
        mysqli_commit($conn);
        $_SESSION['cart'] = []; // Xóa giỏ hàng sau khi đăng ký thành công
        $message = "Đăng ký thành công!";
    } catch (Exception $e) {
        // Nếu có lỗi, rollback transaction
        mysqli_rollback($conn);
        $message = "Lỗi: " . $e->getMessage();
    }
} else {
    $message = "Bạn chưa chọn học phần nào để đăng ký!";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kết quả đăng ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .message {
            font-size: 18px;
            color: <?php echo (strpos($message, 'thành công') !== false) ? 'green' : 'red'; ?>;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Kết quả đăng ký</h2>
    <p class="message"><?php echo $message; ?></p>
    <a href="hocphan.php" class="btn">Quay lại đăng ký</a>
</body>
</html>
