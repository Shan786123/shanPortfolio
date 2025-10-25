<?php
// send_email.php

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get the raw POST data from the request body
    $json_data = file_get_contents('php://input');
    // Decode the JSON data into a PHP associative array
    $data = json_decode($json_data, true);

    // --- Data Validation and Sanitization ---
    // A crucial step to prevent security vulnerabilities like email injection.
    
    $name = filter_var(trim($data["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($data["email"]), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($data["subject"]), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($data["message"]), FILTER_SANITIZE_STRING);

    // --- Basic Validation ---
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject) || empty($message)) {
        // If any field is invalid, send a 400 Bad Request response.
        http_response_code(400);
        echo "Please fill out all fields correctly.";
        exit;
    }

    // --- Email Configuration ---
    
    // The email address you want to receive the messages at.
    $recipient_email = "bhaibiology66@gmail.com"; 

    // The subject line for the email you will receive.
    $email_subject = "New Contact Form Submission: " . $subject;

    // The headers for the email.
    $email_headers = "From: " . $name . " <" . $email . ">\r\n";
    $email_headers .= "Reply-To: " . $email . "\r\n";
    $email_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // The body of the email.
    $email_body = "You have received a new message from your website contact form.\n\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n\n";
    $email_body .= "Message:\n" . $message . "\n";

    // --- Send the Email ---
    
    // The mail() function is a simple way to send emails.
    // NOTE: This requires your web hosting server to have a configured mail service.
    if (mail($recipient_email, $email_subject, $email_body, $email_headers)) {
        // If the email is sent successfully, send a 200 OK response.
        http_response_code(200);
        echo "Thank you! Your message has been sent successfully.";
    } else {
        // If there's an error, send a 500 Internal Server Error response.
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
    }

} else {
    // If the request method is not POST, send a 403 Forbidden response.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>