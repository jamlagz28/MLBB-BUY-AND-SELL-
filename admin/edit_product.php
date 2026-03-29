<?php
include '../config/database.php';

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $description = $_POST['description'];
    $price_php = $_POST['price_php'];

    $usd_rate = 0.018;
    $thb_rate = 0.65;

    $price_usd = $price_php * $usd_rate;
    $price_thb = $price_php * $thb_rate;

    $stmt = $conn->prepare("UPDATE products SET description=?, price_php=?, price_usd=?, price_thb=? WHERE id=?");
    $stmt->execute([$description, $price_php, $price_usd, $price_thb, $id]);

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Edit Product</h2>

    <form method="POST">
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?php echo $product['description']; ?></textarea>
        </div>

        <div class="mb-3">
            <label>Price (PHP)</label>
            <input type="number" name="price_php" class="form-control" value="<?php echo $product['price_php']; ?>">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>

</body>
</html>