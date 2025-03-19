<?php
include 'db_connect.php';
$result = mysqli_query($conn, "SELECT sv.*, nh.TenNganh FROM SinhVien sv JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh");

// Xử lý thông báo từ delete.php
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sinh viên</title>
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
            margin: 0;
            display: inline;
        }
        .add-btn {
            float: right;
            background-color: #007BFF;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }
        .add-btn:hover {
            background-color: #0056b3;
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
        img {
            width: 100px;
            height: auto;
        }
        .actions a {
            margin-right: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            max-width: 500px;
            margin: auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="register.php">Đăng Ký</a>
        <a href="cart.php">Đăng Ký Học Phần</a>
        <a href="login.php">Đăng Nhập</a>
    </div>

    <?php if ($message) { echo "<div class='message'>$message</div>"; } ?>

    <h2>TRANG SINH VIÊN</h2>
    <a href="create.php" class="add-btn">Add Student</a>

    <table>
        <tr>
            <th>MaSV</th>
            <th>HoTen</th>
            <th>GioiTinh</th>
            <th>NgaySinh</th>
            <th>Hinh</th>
            <th>MaNganh</th>
            <th>Thao tác</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['MaSV']; ?></td>
            <td><?php echo $row['HoTen']; ?></td>
            <td><?php echo $row['GioiTinh']; ?></td>
            <td><?php echo date('d/m/Y', strtotime($row['NgaySinh'])); ?></td>
            <td><img src="<?php echo $row['Hinh']; ?>" alt="Hình sinh viên"></td>
            <td><?php echo $row['TenNganh']; ?></td>
            <td class="actions">
                <a href="edit.php?MaSV=<?php echo $row['MaSV']; ?>">Edit</a> |
                <a href="detail.php?MaSV=<?php echo $row['MaSV']; ?>">Details</a> |
                <a href="delete.php?MaSV=<?php echo $row['MaSV']; ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
