<?php

$seller_id=$_GET["seller_id"];
$message=$_GET["message"];

//get email from seller id
include "../config.php";
$sql="SELECT email, username FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $seller_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$buyer_email="";
$buyer_username="";
mysqli_stmt_bind_result($stmt, $buyer_email,$buyer_username);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Email content
$fromEmail = $SENDGRID_EMAIL;
$fromName = "Taubi";
$toEmail = $buyer_email;
$toName = $buyer_username;
$subject = "Jemand ist an deinem Buch interessiert";
$body = "Hallo ".$buyer_username." Jemand interessiert sich für dein Buch auf Taubi.<br>Hier ist die Nachricht:<br><br>".$message;

// API endpoint
$url = "https://api.sendgrid.com/v3/mail/send";

// Prepare the email data as JSON
$data = [
    'personalizations' => [
        [
            'to' => [
                [
                    'email' => $toEmail,
                    'name' => $toName
                ]
            ],
            'subject' => $subject
        ]
    ],
    'from' => [
        'email' => $fromEmail,
        'name' => $fromName
    ],
    'content' => [
        [
            'type' => 'text/html',
            'value' => $body
        ]
    ]
];

// Initialize cURL session
$ch = curl_init($url);

// Set the request headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $SENDGRID_API_KEY,
    'Content-Type: application/json'
]);

// Set the request body
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Set options to return the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if(curl_errno($ch)) {
    // If there's a cURL error, return a failure status
    $responseArray = [
        'status' => 'failure',
        'message' => curl_error($ch)
    ];
} else {
    // Handle the response from SendGrid
    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check if SendGrid API responded with success (HTTP 202)
    if ($httpStatusCode === 202) {
        $responseArray = [
            'status' => 'success',
            'message' => 'Email sent successfully!'
        ];
    } else {
        // Handle failure case if SendGrid returns an error status
        $responseArray = [
            'status' => 'failure',
            'message' => 'Failed to send email. API response: ' . $response
        ];
    }
}

// Close the cURL session
curl_close($ch);

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($responseArray);
?>

