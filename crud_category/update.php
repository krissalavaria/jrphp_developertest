<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$category_name = $category_description = "";
$name_err = $description_err = "";

// Processing form data when form is submitted
if (isset($_POST["category_id"]) && !empty($_POST["category_id"])) {
    // Get hidden input value
    $category_id = $_POST["category_id"];

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
    if (empty($name_err) && empty($price_err) && empty($category_err)) {
        // Prepare an update statement
        $sql = "UPDATE category SET category_name=?, category_description=? WHERE category_id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $category_name, $category_description, $param_id);

            // Set parameters
            $param_name = $category_name;
            $param_price = $category_description;
            $param_id = $category_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: ../category.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["category_id"]) && !empty(trim($_GET["category_id"]))) {
        // Get URL parameter
        $id =  trim($_GET["category_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM category WHERE category_id = ?";
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
                    $category_name = $row["category_name"];
                    $category_description = $row["category_description"];
                } else {
                    // URL doesn't contain valid id.
                    echo '<script>alert("An error occured.")</script>';
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close connection
        mysqli_close($link);
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
                    <h2 class="mt-5">Update Category</h2>
                    <p>Please edit the input values and submit to update the category record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" name="category_name" class="form-control 
                            <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $category_name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="category_description" class="form-control 
                                 <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"><?php echo $category_description; ?>
                            </textarea>
                            <span class="invalid-feedback"><?php echo $category_err; ?></span>
                        </div>
                        <input type="hidden" name="category_id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../category.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>