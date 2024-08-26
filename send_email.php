<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set the recipient email address
    $to = "cryptotrend786@gmail.com"; // Replace with your email address

    // Set the email subject
    $subject = "New Submission from Profile Form";

    // Collect form data
    $userName = htmlspecialchars($_POST['userName']);
    $userEmail = htmlspecialchars($_POST['userEmail']);
    $projectName = htmlspecialchars($_POST['projectName']);
    $rating = htmlspecialchars($_POST['rating']);
    $review = htmlspecialchars($_POST['review']);

    // Prepare the email body
    $message = "User Name: $userName\n";
    $message .= "Email: $userEmail\n";
    $message .= "Project Name: $projectName\n";
    $message .= "Rating: $rating stars\n";
    $message .= "Review:\n$review\n";

    // Set the email headers
    $headers = "From: $userEmail";

    // Check if a file was uploaded
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
        $filePath = $_FILES['profilePicture']['tmp_name'];
        $fileName = $_FILES['profilePicture']['name'];
        $fileType = $_FILES['profilePicture']['type'];
        $fileContent = chunk_split(base64_encode(file_get_contents($filePath)));

        $boundary = md5(uniqid(time()));

        // Update headers for file attachment
        $headers .= "\r\nMIME-Version: 1.0\r\n" .
            "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";

        // Update message body for file attachment
        $message = "--{$boundary}\r\n" .
            "Content-Type: text/plain; charset=\"utf-8\"\r\n" .
            "Content-Transfer-Encoding: 7bit\r\n\r\n" .
            $message . "\r\n\r\n" .
            "--{$boundary}\r\n" .
            "Content-Type: {$fileType}; name=\"{$fileName}\"\r\n" .
            "Content-Transfer-Encoding: base64\r\n" .
            "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n" .
            $fileContent . "\r\n\r\n" .
            "--{$boundary}--";
    }

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "Your message has been sent successfully.";
    } else {
        echo "There was an error sending your message.";
    }
} else {
    echo "Invalid request.";
}
