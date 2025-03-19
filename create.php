<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    // Xử lý file ảnh
    $Hinh = '';
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "images/";
        $base_dir = __DIR__ . DIRECTORY_SEPARATOR . $target_dir;

        $file_name = basename($_FILES['Hinh']['name']);
        $target_file = $base_dir . time() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Chỉ cho phép các định dạng: JPG, JPEG, PNG, GIF!";
            exit();
        }

        if ($_FILES['Hinh']['size'] > 5 * 1024 * 1024) {
            echo "File ảnh quá lớn, tối đa 5MB!";
            exit();
        }

        if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $target_file)) {
            $Hinh = $target_dir . time() . "_" . $file_name;
        } else {
            echo "Lỗi khi tải file ảnh lên!";
            exit();
        }
    }

    $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
            VALUES ('$MaSV', '$HoTen', '$GioiTinh', '$NgaySinh', '$Hinh', '$MaNganh')";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM NganhHoc");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm sinh viên</title>
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
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
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

    <div class="container">
        <h2>Thêm sinh viên</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Mã SV:</label>
            <input type="text" name="MaSV" required>

            <label>Họ Tên:</label>
            <input type="text" name="HoTen" required>

            <label>Giới Tính:</label>
            <select name="GioiTinh">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>

            <label>Ngày Sinh:</label>
            <input type="date" name="NgaySinh">

            <label>Hình:</label>
            <input type="file" name="Hinh" accept="image/*" required>

            <label>Ngành:</label>
            <select name="MaNganh">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['MaNganh']; ?>"><?php echo $row['TenNganh']; ?></option>
                <?php } ?>
            </select>

            <input type="submit" value="Thêm">
        </form>
    </div>
</body>
</html>