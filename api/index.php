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
        require_once __DIR__ . "/IpLogger.php"; // Ensure correct path

        try {
            $logger = new IpLogger();
            $logger->write(__DIR__ . '/../ipsLog.txt', 'Europe/Athens'); // Correct path to ipsLog.txt
        } catch (Exception $e) {
            error_log("Error logging IP: " . $e->getMessage());
        }
    ?>
</body>
</html>
