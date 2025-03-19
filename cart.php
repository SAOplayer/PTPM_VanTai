<?php
include 'db_connect.php';
session_start();
$MaSV = isset($_SESSION['MaSV']) ? $_SESSION['MaSV'] : "0123456789"; // Kiểm tra đăng nhập

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xóa học phần khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $MaHP = $_GET['remove'];
    $_SESSION['cart'] = array_diff($_SESSION['cart'], [$MaHP]);
    header("Location: cart.php");
    exit();
}

// Xóa toàn bộ học phần trong giỏ hàng
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

// Lấy số học phần đã đăng ký
$soHocPhan = count($_SESSION['cart']);

// Lấy thông tin học phần trong giỏ hàng
$cart = $_SESSION['cart'];
$cart_items = [];
if (!empty($cart)) {
    $cart_list = "'" . implode("','", $cart) . "'";
    $query = "SELECT * FROM HocPhan WHERE MaHP IN ($cart_list)";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cart_items[] = $row;
        }
    } else {
        echo "Lỗi truy vấn: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng Kí Học Phần</title>
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
            margin-right: 10px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .save-btn, .clear-btn {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 3px;
            margin-top: 20px;
        }
        .clear-btn {
            background-color: red;
        }
        .save-btn:hover {
            background-color: #0056b3;
        }
        .clear-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="register.php">Đăng Ký</a>
        <a href="cart.php">Đăng Ký Học Phần (<?php echo $soHocPhan; ?>)</a>
        <a href="login.php">Đăng Nhập</a>
    </div>

    <h2>Đăng Kí Học Phần</h2>
    <?php if (!empty($cart_items)) { ?>
        <table border="1">
            <tr>
                <th>Mã HP</th>
                <th>Tên HP</th>
                <th>Số Tín Chỉ</th>
                <th>Thao tác</th>
            </tr>
            <?php foreach ($cart_items as $item) { ?>
            <tr>
                <td><?php echo $item['MaHP']; ?></td>
                <td><?php echo $item['TenHP']; ?></td>
                <td><?php echo $item['SoTinChi']; ?></td>
                <td class="actions">
                    <a href="cart.php?remove=<?php echo $item['MaHP']; ?>">Xóa</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <a href="save.php" class="save-btn">Lưu</a>
        <a href="cart.php?clear=1" class="clear-btn">Xóa Đăng Ký</a>
    <?php } else { ?>
        <p>Bạn chưa đăng kí học phần nào.</p>
    <?php } ?>
</body>
</html>