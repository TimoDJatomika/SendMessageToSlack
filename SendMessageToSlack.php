<?php

/**
 * user: Timo Stankowitz
 * mail: timo.stankowitz@gmail.com
 * Date: 03.04.16
 * Time: 21:12
 *
 * make sure php5-curl in installed on your system
 *
 */
class SendMessageToSlack {

    private $curlObject;
    private static $url = 'https://hooks.slack.com/services/XXXX/YYYYY/ZZZ'; // replace with your url

    private $message;
    private $color;

    private $payload;

    /**
     * SendMessageToSlack constructor.
     * @param string $color string color of the notification
     * @param $message string what message should be send.
     */
    public function __construct($color = 'CCCCCC', $message) {
        // create new curl object
        $this->curlObject = curl_init(self::$url);
        $this->color = $color;
        $this->message = $message;

        $this->buildPayload();
        $this->execute();
    }

    private function buildPayload() {
        $this->payload = [
            "channel" => "XXXX", // name of the channel
            "username" => "XXX", // name of the username

            "attachments" => [
                [
                    "fallback" => $this->message,
                    "color" => $this->color,
                    "text" => $this->message
                ]
            ]
        ];
    }


    private function execute() {
        // convert to json
        $enc = json_encode($this->payload);

        // curl stuff.
        curl_setopt($this->curlObject, CURLOPT_POST, 1);
        curl_setopt($this->curlObject, CURLOPT_POSTFIELDS, $enc);
        curl_setopt($this->curlObject, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->curlObject, CURLOPT_VERBOSE, 0); // don't show output on the screen.
        curl_setopt($this->curlObject, CURLOPT_RETURNTRANSFER, true);

        // and send !
        $result = curl_exec($this->curlObject);

        if($result != 'ok') {
            // curl error
            error_log("curl error: " . curl_error($this->curlObject));
        }

    }
}