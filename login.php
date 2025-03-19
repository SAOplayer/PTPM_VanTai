<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaSV = $_POST['MaSV'];
    $sql = "SELECT * FROM SinhVien WHERE MaSV='$MaSV'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['MaSV'] = $MaSV;
        header("Location: hocphan.php");
        exit();
    } else {
        $error = "Mã sinh viên không tồn tại!";
    }
}

// Lấy số học phần đã đăng ký
$soHocPhan = 0;
if (isset($_SESSION['MaSV'])) {
    $MaSV = $_SESSION['MaSV'];
    $query = "SELECT COUNT(*) as total FROM ChiTietDangKy 
              INNER JOIN DangKy ON ChiTietDangKy.MaDK = DangKy.MaDK 
              WHERE DangKy.MaSV = '$MaSV'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $soHocPhan = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
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
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .error {
            color: red;
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

    <div class="container">
        <h2>Đăng nhập</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <label for="MaSV">Mã SV:</label>
            <input type="text" name="MaSV" required>
            <input type="submit" value="Đăng nhập">
        </form>
    </div>
</body>
</html>
