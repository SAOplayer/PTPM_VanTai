<?php
include 'db_connect.php';
$MaSV = $_GET['MaSV'];
$result = mysqli_query($conn, "SELECT sv.*, nh.TenNganh FROM SinhVien sv JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh WHERE MaSV='$MaSV'");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết sinh viên</title>
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
        .info {
            max-width: 500px;
            margin: 0 auto;
        }
        .info p {
            margin: 10px 0;
        }
        .info img {
            max-width: 200px;
            height: auto;
            margin: 10px 0;
        }
        .actions {
            margin-top: 20px;
        }
        .actions a {
            color: #007BFF;
            text-decoration: none;
            margin-right: 15px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="#">Test1</a>
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="register.php">Đăng Ký</a>
        <a href="login.php">Đăng Nhập</a>
    </div>

    <h2>Thông tin chi tiết</h2>
    <div class="info">
        <p><strong>Họ Tên:</strong> <?php echo $row['HoTen']; ?></p>
        <p><strong>Giới Tính:</strong> <?php echo $row['GioiTinh']; ?></p>
        <p><strong>Ngày Sinh:</strong> <?php echo date('d/m/Y h:i A', strtotime($row['NgaySinh'])); ?></p>
        <p><img src="<?php echo $row['Hinh']; ?>" alt="Hình sinh viên"></p>
        <p><strong>Mã Ngành:</strong> <?php echo $row['MaNganh']; ?></p>
    </div>

    <div class="actions">
        <a href="edit.php?MaSV=<?php echo $row['MaSV']; ?>">Edit</a>
        <a href="index.php">Back to List</a>
    </div>
</body>
</html>