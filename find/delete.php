<?php
// delete_found_id.php

// Connect to the database
include 'db_connect.php';

// Check if 'id' is in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Safely get the ID

    // Delete the record from the database
    $delete = mysqli_query($conn, "DELETE FROM found_ids WHERE id = $id");

    if ($delete) {
        // Successfully deleted, redirect back to admin page
        header("Location: found_id.php");
        exit();
    } else {
        echo "Failed to delete ID. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
