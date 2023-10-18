<?php
 
session_start();

// Initialize the Shopping Cart array or retrieve it from the session
if (!isset($_SESSION['shoppingCart'])) {
    $_SESSION['shoppingCart'] = [];
}

// Initialize the Discount Table  
$discountTable = [
    'Vendor 1' => ['TRADE A' => 12, 'TRADE B' => 12, 'TRADE C' => 0, 'TRADE D' => 6],
    'Vendor 2' => ['TRADE A' => 10, 'TRADE B' => 8, 'TRADE C' => 20, 'TRADE D' => 0],
    'Vendor 3' => ['TRADE A' => 0, 'TRADE B' => 25, 'TRADE C' => 3, 'TRADE D' => 16],
    'Vendor 4' => ['TRADE A' => 9, 'TRADE B' => 0, 'TRADE C' => 16, 'TRADE D' => 30],
    'Vendor 5' => ['TRADE A' => 5, 'TRADE B' => 11, 'TRADE C' => 0, 'TRADE D' => 30],
];

// Check if the Add To Cart button is clicked and add items to the cart
if (isset($_POST['add_to_cart'])) {
    $product = [
        'product_name' => $_POST['product_name'],
        'org_price' => floatval($_POST['price']),
        'tags' => explode(",", $_POST['tags']),
        'vendor' => $_POST['vendor'],
    ];

    // Initialize variables for the new cart item
    $max_discount = 0;
    $discounted_percentage = 0;

    // Calculate the maximum discount percentage based on tags and vendor
    if (isset($discountTable[$product['vendor']])) {
        foreach ($product['tags'] as $tag) {
            if (isset($discountTable[$product['vendor']][$tag])) {
                $discountValue = $discountTable[$product['vendor']][$tag];
                if ($discountValue > $max_discount) {
                    $max_discount = $discountValue;
                }
            }
        }
    }

    // Calculate the discounted price
    $discounted_price = $product['org_price'] - ($product['org_price'] * $max_discount / 100);

    // Add the item to the shopping cart
    $_SESSION['shoppingCart'][] = [
        'product_name' => $product['product_name'],
        'org_price' => $product['org_price'],
        'discounted_percentage' => $max_discount,
        'discounted_price' => $discounted_price,
        'vendor' => $product['vendor'],
    ];
}

// Check if the Remove Cart button is clicked and remove items from the cart
if (isset($_POST['remove_from_cart'])) {
    $index = $_POST['remove_item_index'];
    if (isset($_SESSION['shoppingCart'][$index])) {
        unset($_SESSION['shoppingCart'][$index]);
        $_SESSION['shoppingCart'] = array_values($_SESSION['shoppingCart']);  
    }
}

// Assign the session shopping cart data to a local variable for easy use
$shoppingCart = $_SESSION['shoppingCart'];



 // product table data with Add To Cart buttons
$productTable = [
    
    ["1", "Test Product 1", "12.5", "TRADE A,ice", "Vendor 5", "1"],
    ["2", "Test Product 2", "42.5", "TRADE B,ice2", "Vendor 4", "2"],
    ["3", "Test Product 3", "200", "TRADE C,test", "Vendor 3", "3"],
    ["4", "Test Product 4", "52.5", "TRADE C,test", "Vendor 2", "4"],
    ["5", "Test Product 5", "712.5", "TRADE D,test", "Vendor 1", "5"],
    ["6", "Test Product 6", "17", "TRADE A,test", "Vendor 3", "6"],
    ["7", "Test Product 7", "55", "TRADE A,test", "Vendor 3", "7"],
    ["8", "Test Product 8", "37", "TRADE D,test", "Vendor 4", "8"],
    ["9", "Test Product 9", "82", "TRADE C,test", "Vendor 4", "9"],
    ["10", "Test Product 10", "52", "TRADE B,test", "Vendor 2", "10"],
    ["11", "Test Product 11", "102", "TRADE B,test", "Vendor 1", "11"],
    ["12", "Test Product 12", "172.5", "TRADE B,test", "Vendor 4", "12"],
    ["13", "Test Product 13", "100", "TRADE D,test", "Vendor 5", "13"],
    ["14", "Test Product 14", "99.5", "TRADE D,test", "Vendor 5", "14"],
    ["15", "Test Product 15", "87.5", "TRADE A,test", "Vendor 5", "15"],
    ["16", "Test Product 16", "57", "TRADE A,test", "Vendor 1", "16"],
    ["17", "Test Product 17", "10", "TRADE C,test", "Vendor 2", "17"],
    ["18", "Test Product 18", "412.5", "TRADE D,test", "Vendor 3", "18"],
    ["19", "Test Product 19", "112.5", "TRADE B,test", "Vendor 4", "19"],
    ["20", "Test Product 20", "102", "TRADE B,test", "Vendor 5", "20"],
    ["21", "Test Product 21", "52", "TRADE A,test", "Vendor 3", "21"],
    ["22", "Test Product 22", "32", "TRADE C,test", "Vendor 2", "22"],
    ["23", "Test Product 23", "12", "TRADE B,test", "Vendor 4", "23"],
    ["24", "Test Product 24", "584.5", "TRADE D,test", "Vendor 1", "24"],
    ["25", "Test Product 25", "67", "TRADE D,test", "Vendor 5", "25"],
    ["26", "Test Product 26", "132.5", "TRADE A,test", "Vendor 5", "26"],
    ["27", "Test Product 27", "43.5", "TRADE B,test", "Vendor 2", "27"],
];

?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Discount Table</h2>
<table class="discount-table">
    <tr>
        <th>Vendor</th>
        <th>Trade A</th>
        <th>Trade B</th>
        <th>Trade C</th>
        <th>Trade D</th>
    </tr>
    <?php
    foreach ($discountTable as $vendor => $discounts) {
        echo "<tr>";
        echo "<td>$vendor</td>";
        foreach ($discounts as $tag => $discount) {
            echo "<td>$discount</td>";
        }
        echo "</tr>";
    }
    ?>
</table>

<h2>Product Table</h2>
<table class="product-table">
    <tr>
        <th>#</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Tags</th>
        <th>Vendor</th>
        <th>Cart</th>
    </tr>
    <?php
    foreach ($productTable as $row) {
        echo "<tr>";
        foreach ($row as $index => $cell) {
            if ($index == 0) {
                echo "<td>$cell</td>";
            } elseif ($index == 5) {
                echo '<td><form method="post" action=""><input type="hidden" name="product_name" value="' . $row[1] . '"><input type="hidden" name="price" value="' . $row[2] . '"><input type="hidden" name="tags" value="' . $row[3] . '"><input type="hidden" name="vendor" value="' . $row[4] . '"><input type="submit" name="add_to_cart" value="Add To Cart" class="cart-cell"></form></td>';
            } else {
                echo "<td>$cell</td>";
            }
        }
        echo "</tr>";
    }
    ?>
</table>
<h2>Shopping Cart</h2>
    <table class="shopping-cart">
        <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Org Price</th>
            <th>Discounted Percentage</th>
            <th>Discounted Price</th>
            <th>Vendor</th>
            <th>Remove Cart</th>
        </tr>
        <?php
        foreach ($shoppingCart as $index => $item) {
            echo "<tr>";
            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>" . $item['product_name'] . "</td>";
            echo "<td>" . $item['org_price'] . "</td>";
            echo "<td>" . $item['discounted_percentage']  . "</td>";
            echo "<td>" . number_format($item['discounted_price'], 2) . "</td>";
            echo "<td>" . $item['vendor'] . "</td>";
            echo '<td><form method="post" action=""><input type="hidden" name="remove_item_index" value="' . $index . '"><input type="submit" name="remove_from_cart" value="Remove Cart" class="remove-cart-cell"></form></td>';
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>