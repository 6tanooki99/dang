<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <p>Welcome! Please enjoy the special invitation.</p>
    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTSXrx5-N1i5H_MqMfL1uc-OVDFH-lrTtH7_g&s" alt="Invitation Image">

    <?php
        require_once __DIR__ . "/IpLogger.php"; // Corrected path to IpLogger.php

        // Use a try-catch block to handle potential exceptions
        try {
            $logger = new IpLogger();
            $filePath = __DIR__ . '/../ipsLog.txt'; // Corrected path to ipsLog.txt
            error_log("Logging to file: " . $filePath);
            $logger->write($filePath, 'Europe/Athens');
        } catch (Exception $e) {
            // Log the error to PHP's error log
            error_log("Error logging IP: " . $e->getMessage());
        }
    ?>
</body>
</html>
