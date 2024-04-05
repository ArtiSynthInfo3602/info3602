<?php
// Check if user is logged in
if (is_user_logged_in()) {
    // Display the art upload form for logged-in users
    echo '<h2>Upload Your Art</h2>';
    echo '<form action="upload-handler.php" method="post" enctype="multipart/form-data">';
    echo 'Art Title: <input type="text" name="art_title"><br>';
    echo 'Upload Image: <input type="file" name="art_image"><br>';
    echo '<input type="submit" value="Submit">';
    echo '</form>';
} else {
    // Display a message or login/register button for non-logged-in users
    echo '<p>You need to <a href="' . wp_login_url() . '">login</a> or <a href="' . wp_registration_url() . '">register</a> to upload your art.</p>';
}

// Display the submissions for everyone
echo '<h2>Art Submissions</h2>';
// Display the submissions here, fetched from the database

// Allow all users to vote for submissions
echo '<h2>Vote for Your Favorite</h2>';
// Display the voting options here

// Determine winners and display spotlight
echo '<h2>Weekly Winners Spotlight</h2>';
// Display the winners' submissions here
?>

