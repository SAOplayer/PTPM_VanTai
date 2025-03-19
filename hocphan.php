<?php
include 'db_connect.php';
session_start();
$MaSV = isset($_SESSION['MaSV']) ? $_SESSION['MaSV'] : "0123456789"; // Kiểm tra đăng nhập

// Truy vấn dữ liệu từ bảng HocPhan
$query = "SELECT * FROM HocPhan";
$result = mysqli_query($conn, $query);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['MaHP'])) {
    $MaHP = $_GET['MaHP'];
    if (!in_array($MaHP, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $MaHP;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký học phần</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
        }
        .header a {
            color: #bbb;
            text-decoration: none;
            margin-right: 15px;
        }
        .header a:hover {
            color: #fff;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions a {
            color: #007BFF;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="register.php">Đăng Ký</a>
        <a href="cart.php">Đăng Ký Học Phần (<?php echo count($_SESSION['cart']); ?>)</a>
        <a href="login.php">Đăng Nhập</a>
    </div>

    <h2>Đăng ký học phần</h2>
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <table border="1">
            <tr>
                <th>Mã HP</th>
                <th>Tên HP</th>
                <th>Số Tín Chỉ</th>
                <th>Số lượng dự kiến</th>
                <th>Thao tác</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['MaHP']; ?></td>
                <td><?php echo $row['TenHP']; ?></td>
                <td><?php echo $row['SoTinChi']; ?></td>
                <td><?php echo $row['SoLuongDuKien']; ?></td>
                <td class="actions">
                    <a href="hocphan.php?MaHP=<?php echo $row['MaHP']; ?>">Đăng kí</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>Không có học phần nào để hiển thị.</p>
    <?php } ?>
    <a href="cart.php">Xem học phần đã đăng ký</a>
</body>
</html>
