<?php
include '../database/db_connect.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM registration WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $message = "Email already exists";
        $toastClass = "007bff"; // primary color
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO registration (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $message = "Registration Successful";
            $toastClass = "28a745"; // success color
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "dc3545"; // danger color
        }
        $stmt->close();
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

