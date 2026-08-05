<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $company = htmlspecialchars(trim($_POST['company']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Check required fields
    if ($name && $email && $message) {
        $to = 'message.abdulazeez@gmail.com';
        $subject = "Portfolio Contact: Message from $name";
        
        // Build email body
        $email_body = "New Contact Form Submission\n";
        $email_body .= "============================\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        
        // Add company if provided
        if (!empty($company)) {
            $email_body .= "Company: $company\n";
        }
        
        $email_body .= "\nMessage:\n";
        $email_body .= "--------\n";
        $email_body .= "$message\n\n";
        $email_body .= "============================\n";
        $email_body .= "Sent from your portfolio website";
        
        // Email headers
        $headers = "From: Portfolio Contact Form <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
        $headers .= "Reply-To: $name <$email>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Attempt to send email
        if (mail($to, $subject, $email_body, $headers)) {
            // Also send auto-reply to the sender
            $auto_subject = "Thanks for reaching out, $name!";
            $auto_message = "Hi $name,\n\n";
            $auto_message .= "Thank you for contacting me through my portfolio website. ";
            $auto_message .= "I've received your message and will get back to you within 2 hours.\n\n";
            $auto_message .= "Best regards,\n";
            $auto_message .= "Abdul-Azeez Adeleye\n";
            $auto_message .= "Senior Software Engineer";
            
            $auto_headers = "From: Abdul-Azeez Adeleye <message.abdulazeez@gmail.com>\r\n";
            $auto_headers .= "Reply-To: message.abdulazeez@gmail.com\r\n";
            
            mail($email, $auto_subject, $auto_message, $auto_headers);
            
            // Redirect with success
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=success');
            exit;
        } else {
            // Log error for debugging
            error_log("Email sending failed for: $email");
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=error');
            exit;
        }
    } else {
        // Missing required fields
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=validation_error');
        exit;
    }
} else {
    // Not a POST request
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?status=invalid_request');
    exit;
}
?>