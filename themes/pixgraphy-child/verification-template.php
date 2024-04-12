<?php
/**
 * Template Name: Verification Template
 *
 * @package Theme Freesia
 * @subpackage Pixgraphy
 * @since Pixgraphy 1.0
 */
get_header();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    $to_email = "piercedoman25@gmail.com";
    $subject = "Verification Request";
    $artist_or_moderation = $_POST["artist_or_moderation"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Compose email message
    $email_message = "Name: $name\n";
    $email_message .= "Email: $email\n";
    $email_message .= "Request Type: $artist_or_moderation\n";
    $email_message .= "Message:\n$message";

    // Send email
    $headers = "From: $email";
    if (mail($to_email, $subject, $email_message, $headers)) {
        echo "<p>Thank you for your request! We will get back to you soon.</p>";
    } else {
        echo "<p>Sorry, there was an error sending your request. Please try again later.</p>";
    }
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="artist_or_moderation">Request Type:</label><br>
    <select id="artist_or_moderation" name="artist_or_moderation">
        <option value="Artist">Artist</option>
        <option value="Moderation">Moderation</option>
    </select><br><br>
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <label for="message">Message:</label><br>
    <textarea id="message" name="message" rows="4" required></textarea><br><br>
    <input type="submit" value="Submit">
</form>

<?php
get_footer();
?>
