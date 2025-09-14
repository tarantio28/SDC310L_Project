<?php
require_once __DIR__ . "/../model/database.php";

use Model\Database;

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Database connection failed.");
}

$result = $conn->query("SELECT * FROM products");

echo "<h1>Product List</h1>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['product_id'] . "</td>";
    echo "<td>" . $row['product_name'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "<td>$" . $row['price'] . "</td>";
    echo "<td>" . $row['stock'] . "</td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();
?>

