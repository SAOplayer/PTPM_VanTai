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
        $target_dir = "images/"; // Thư mục lưu ảnh, phải tồn tại trước
        $base_dir = __DIR__ . DIRECTORY_SEPARATOR . $target_dir;

        // Kiểm tra xem thư mục tồn tại chưa
        if (!is_dir($base_dir)) {
            echo "Thư mục images/ không tồn tại! Vui lòng tạo thư mục images trong D:\\PTPM_VanTai\\.";
            exit();
        }

        $file_name = basename($_FILES['Hinh']['name']);
        $target_file = $base_dir . time() . "_" . $file_name; // Đường dẫn tuyệt đối
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra định dạng file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Chỉ cho phép các định dạng: JPG, JPEG, PNG, GIF!";
            exit();
        }

        // Kiểm tra kích thước file (giới hạn 5MB)
        if ($_FILES['Hinh']['size'] > 5 * 1024 * 1024) {
            echo "File ảnh quá lớn, tối đa 5MB!";
            exit();
        }

        // Di chuyển file vào thư mục images/
        if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $target_file)) {
            $Hinh = $target_dir . time() . "_" . $file_name;
        } else {
            echo "Lỗi khi tải file ảnh lên! Đường dẫn: " . $target_file . ". Vui lòng kiểm tra quyền thư mục.";
            exit();
        }
    } else {
        echo "Vui lòng chọn một file ảnh!";
        exit();
    }

    // Kiểm tra xem MaSV đã tồn tại chưa
    $check_sql = "SELECT MaSV FROM SinhVien WHERE MaSV='$MaSV'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        echo "Mã sinh viên đã tồn tại! Vui lòng chọn mã khác.";
        exit();
    }

    // Thêm sinh viên (tài khoản mới) vào cơ sở dữ liệu
    $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
            VALUES ('$MaSV', '$HoTen', '$GioiTinh', '$NgaySinh', '$Hinh', '$MaNganh')";
    if (mysqli_query($conn, $sql)) {
        echo "Đăng ký tài khoản thành công! <a href='login.php'>Đăng nhập ngay</a>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM NganhHoc");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 500px;
            margin: 20px auto;
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
        input[type="file"] {
            padding: 3px;
        }
    </style>
</head>
<body>
    <h2>Đăng ký tài khoản</h2>
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

        <input type="submit" value="Đăng ký">
    </form>
</body>
</html>