<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Listing</title>
    <?php require "layouts/app.php"; ?>
    <link rel="stylesheet" href="../layouts/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">List of Category</h2>
                        <a href="crud_category/create.php" class="btn btn-primary pull-right mr-2">Add Category</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM category";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>ID</th>";
                            echo "<th>Category Name</th>";
                            echo "<th>Description</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['category_id'] . "</td>";
                                echo "<td>" . $row['category_name'] . "</td>";
                                echo "<td>" . $row['category_description'] . "</td>";
                                echo "<td>";
                                echo '<a href="crud_category/update.php?category_id=' . $row['category_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                echo '<a href="crud_category/delete.php?category_id=' . $row['category_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                echo "</td>";
                                echo "</tr>";
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