<?php
require_once "../model/database.php";

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
</head>
<body>
    <h1>Available Products</h1>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th><th>Description</th><th>Price</th><th>Stock</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["product_name"]; ?></td>
            <td><?php echo $row["description"]; ?></td>
            <td>$<?php echo $row["price"]; ?></td>
            <td><?php echo $row["stock"]; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
