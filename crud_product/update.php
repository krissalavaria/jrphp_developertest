<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$product_name = $product_price = $product_category = "";
$name_err = $price_err = $category_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {

    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["product_name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } else {
        $product_name = $input_name;
    }

    // Validate price
    $input_price = trim($_POST["product_price"]);
    if (empty($input_price)) {
        $price_err = "Please enter an price.";
    } else {
        $product_price = $input_price;
    }

    // Validate Category
    $input_category = trim($_POST["category_id"]);
    if (empty($input_category)) {
        $category_err = "Please enter the category amount.";
    } else {
        $product_category = $input_category;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($price_err) && empty($category_err)) {
        // Prepare an update statement
        $sql = "UPDATE products SET product_name=?, product_price=?, category_id=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_price, $param_category, $param_id);

            // Set parameters
            $param_name = $product_name;
            $param_price = $product_price;
            $param_category = $product_category;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: ../products.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM products WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $product_name = $row["product_name"];
                    $product_price = $row["product_price"];
                    $product_category = $row["category_id"];
                } else {
                    // URL doesn't contain valid id.
                    echo '<script>alert("An error occured.")</script>';
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    } else {
        // URL doesn't contain id parameter.
        echo '<script>alert("An error occured.")</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <?php require "../layouts/app.php"; ?>
    <link rel="stylesheet" href="../layouts/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the product record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_name; ?>" required>
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="product_price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_price; ?>" required>
                            <span class="invalid-feedback"><?php echo $price_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" required>
                                <?php
                                $category_query = "SELECT * FROM `category`";
                                if ($result = mysqli_query($link, $category_query)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                ?>
                                            <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name'] ?></option>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $category_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../products.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>