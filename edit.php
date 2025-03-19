<?php
include 'db_connect.php';
$MaSV = $_GET['MaSV'];
$result = mysqli_query($conn, "SELECT * FROM SinhVien WHERE MaSV='$MaSV'");
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    // Xử lý file ảnh (nếu có file mới được chọn)
    $Hinh = $row['Hinh']; // Giữ nguyên ảnh cũ nếu không chọn ảnh mới
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "images/"; // Thư mục lưu ảnh, phải tồn tại trước
        // Sử dụng đường dẫn tuyệt đối
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
            // Xóa ảnh cũ nếu tồn tại (tuỳ chọn)
            if (!empty($row['Hinh']) && file_exists($base_dir . basename($row['Hinh']))) {
                unlink($base_dir . basename($row['Hinh']));
            }
            // Lưu đường dẫn tương đối của ảnh mới
            $Hinh = $target_dir . time() . "_" . $file_name;
        } else {
            echo "Lỗi khi tải file ảnh lên! Đường dẫn: " . $target_file . ". Vui lòng kiểm tra quyền thư mục.";
            exit();
        }
    }

    // Cập nhật thông tin sinh viên vào cơ sở dữ liệu
    $sql = "UPDATE SinhVien SET HoTen='$HoTen', GioiTinh='$GioiTinh', NgaySinh='$NgaySinh', Hinh='$Hinh', MaNganh='$MaNganh' 
            WHERE MaSV='$MaSV'";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}

$nganh = mysqli_query($conn, "SELECT * FROM NganhHoc");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sửa sinh viên</title>
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
        .preview {
            margin-top: 10px;
        }
        .preview img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="#">Test1</a>
        <a href="index.php">Sinh Viên</a>
        <a href="hocphan.php">Học Phần</a>
        <a href="#">Đăng Ký</a>
        <a href="login.php">Đăng Nhập</a>
    </div>

    <h2>Sửa sinh viên</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Mã SV:</label>
        <input type="text" name="MaSV" value="<?php echo $row['MaSV']; ?>" readonly>

        <label>Họ Tên:</label>
        <input type="text" name="HoTen" value="<?php echo $row['HoTen']; ?>" required>

        <label>Giới Tính:</label>
        <select name="GioiTinh">
            <option value="Nam" <?php if ($row['GioiTinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
            <option value="Nữ" <?php if ($row['GioiTinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
        </select>

        <label>Ngày Sinh:</label>
        <input type="date" name="NgaySinh" value="<?php echo $row['NgaySinh']; ?>">

        <label>Hình:</label>
        <input type="file" name="Hinh" accept="image/*">
        <div class="preview">
            <?php if (!empty($row['Hinh'])) { ?>
                <img src="<?php echo $row['Hinh']; ?>" alt="Hình hiện tại">
            <?php } ?>
        </div>

        <label>Ngành:</label>
        <select name="MaNganh">
            <?php while ($n = mysqli_fetch_assoc($nganh)) { ?>
                <option value="<?php echo $n['MaNganh']; ?>" <?php if ($n['MaNganh'] == $row['MaNganh']) echo 'selected'; ?>>
                    <?php echo $n['TenNganh']; ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" value="Cập nhật">
    </form>
</body>
</html>