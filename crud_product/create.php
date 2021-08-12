<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$product_name = $product_price = $category_id = "";
$name_err = $price_err = $category_err = "";

$date_added = date("Y-m-d");

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $category_err = "Please enter the category.";
    } else {
        $category_id = $input_category;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($price_err) && empty($category_err)) {

        $sql_exist = mysqli_query($link, "SELECT * FROM products WHERE product_name = '" . $_POST['product_name'] . "'");
        if (mysqli_num_rows($sql_exist)) {
            echo "<script>
                alert('Product Already Exists.');
                window.location.href='create.php';
                </script>";
        } else {
            // Prepare an insert statement
            $sql = "INSERT INTO products (product_name, product_price, category_id, date_added) VALUES (?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sdss", $param_name, $param_price, $param_category, $param_date);

                // Set parameters
                $param_name = $product_name;
                $param_price = $product_price;
                $param_category = $category_id;
                $param_date = $date_added;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Records created successfully. Redirect to landing page
                    header("location: ../products.php");
                    exit();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Record</title>
    <?php require "../layouts/app.php"; ?>
    <link rel="stylesheet" href="../layouts/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Insert a New Product</h2>
                    <p>Please fill this form and submit to add a product.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_name; ?>" required>
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" min="0" step="0.01" name="product_price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_price; ?>" required>
                            <span class="invalid-feedback"><?php echo $price_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" required>
                                <?php
                                $category_query = "SELECT * FROM category";
                                if ($result = mysqli_query($link, $category_query)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                ?>
                                            <option value="<?php echo $row['category_id']; ?>"><?php echo $row['category_name'] ?>
                                            </option>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $category_err; ?></span>
                        </div>
                        <input type="text" name="date_added" value="<?php echo $date_added; ?>" hidden>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../products.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>