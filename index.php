<!DOCTYPE html>
<html lang="en">

<head>
    <?php require "layouts/app.php"; ?>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">List of Products</h2>
                        <a href="crud_product/create.php" class="btn btn-success pull-right">Add Product</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM products
                            INNER JOIN category as category ON category.category_id = products.category_id";
                    $count = 1;

                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>No.</th>";
                            echo "<th>Product</th>";
                            echo "<th>Category</th>";
                            echo "<th>Date Added</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $count . "</td>";
                                echo "<td>" . $row['product_name'] . ', ' . number_format($row['product_price'], 2, '.', '') . "</td>";
                                echo "<td>" . $row['category_name'] . "</td>";
                                echo "<td>" . date('F d, Y', strtotime($row['date_added'])) . "</td>";
                                echo "<td>";
                                echo '<a href="crud_product/update.php?id=' . $row['id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                echo '<a href="crud_product/delete.php?id=' . $row['id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                echo "</td>";
                                echo "</tr>";
                                $count = $count + 1;
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>