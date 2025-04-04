<?php
include '../database/db_connect.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists
    $checkEmailStmt = $conn->prepare("SELECT id, username, password FROM userdata WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows == 0) {
        $message = "No user found with that email";
        $toastClass = "dc3545"; // danger color
    } else {
        // Fetch the user's data
        $checkEmailStmt->bind_result($id, $username, $hashedPassword);
        $checkEmailStmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Start a session and store user information in it for logged-in users
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            $message = "Login Successful";
            $toastClass = "28a745"; // success color
        } else {
            $message = "Incorrect password";
            $toastClass = "dc3545"; // danger color
        }
    }
    $checkEmailStmt->close();
    $conn->close();
}
?>
<!-- Optionally display message or toast here -->
<?php if ($message): ?>
    <div class="toast <?php echo $toastClass; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
