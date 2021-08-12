<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$category_name = $category_description = "";
$name_err = $description_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_name = trim($_POST["category_name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a category name.";
    } else {
        $category_name = $input_name;
    }

    // Validate price
    $input_price = trim($_POST["category_description"]);
    if (empty($input_price)) {
        $description_err = "Please enter an price.";
    } else {
        $category_description = $input_price;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($description_err)) {

        $sql_exist = mysqli_query($link, "SELECT * FROM category WHERE category_name = '" . $_POST['category_name'] . "'");
        if (mysqli_num_rows($sql_exist)) {
            echo "<script>
                alert('Product Already Exists.');
                window.location.href='create.php';
                </script>";
        } else {

            // Prepare an insert statement
            $sql = "INSERT INTO category (category_name, category_description) VALUES (?, ?)";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_name, $param_desc);

                // Set parameters
                $param_name = $category_name;
                $param_desc = $category_description;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Records created successfully. Redirect to landing page
                    header("location: ../category.php");
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
    <meta charset="UTF-8">
    <title>Create Record</title>
    <?php require "../layouts/app.php"; ?>
    <link rel="stylesheet" href="../layouts/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Insert a New Category</h2>
                    <p>Please fill this form and submit to add a category.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="category_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $category_name; ?>" required>
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="category_description" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $category_description; ?>" required></textarea>
                            <span class="invalid-feedback"><?php echo $description_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../category.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>