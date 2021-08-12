<?php
// Process delete operation after confirmation
if (isset($_POST["category_id"]) && !empty($_POST["category_id"])) {
    // Include config file
    require_once "../config.php";

    // Prepare a delete statement
    $sql = "DELETE FROM category WHERE category_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_POST["category_id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: ../category.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["category_id"]))) {
        // URL doesn't contain id parameter. Redirect to error page
        echo '<script>alert("An error occured.")</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <?php require "../layouts/app.php"; ?>
    <link rel="stylesheet" href="../layouts/style.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="category_id" value="<?php echo trim($_GET["category_id"]); ?>" />
                            <p>Are you sure you want to delete this category?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="../category.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>