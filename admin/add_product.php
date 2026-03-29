<?php include '../config/database.php';

if ($_POST) {
    $description = $_POST['description'];
    $price_php = $_POST['price_php'];

    // Conversion rates (example)
    $usd_rate = 0.018;
    $thb_rate = 0.65;

    $price_usd = $price_php * $usd_rate;
    $price_thb = $price_php * $thb_rate;

    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);

    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO products (image, description, price_php, price_usd, price_thb)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$image, $description, $price_php, $price_usd, $price_thb]);

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Add Product</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Price (PHP)</label>
            <input type="number" name="price_php" class="form-control" required>
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>

</body>
</html>