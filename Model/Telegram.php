<?php

namespace Bydn\ImprovedLogger\Model;

class Telegram
{
    /**
     * @var \Bydn\ImprovedLogger\Helper\Config
     */
    private $loggerConfig;

    /**
     * @param \Bydn\ImprovedLogger\Helper\Config $loggerConfig
     */
    public function __construct(
        \Bydn\ImprovedLogger\Helper\Config $loggerConfig
    ) {
        $this->loggerConfig = $loggerConfig;
    }

    /**
     * Sends a telegram alert
     *
     * @param string $message
     * @return void
     */
    public function sendTelegramMessage($message)
    {
        // Check if notification is enabled
        if (!$this->loggerConfig->isTelegramNotificationEnabled()) {
            return;
        }

        // Check API key exists or return
        $apiKey = $this->loggerConfig->getTelegramToken();
        if (empty($apiKey)) {
            return;
        }

        // Check destination or return
        $destination = $this->loggerConfig->getTelegramChatId();
        if (empty($destination)) {
            return;
        }

        // Build API URL
        $apiUrl = 'https://api.telegram.org/bot' . $apiKey . '/sendMessage';

        // Chuks of 4096 maximum
        $chunk = '';
        $chunks = [];
        $lines = explode("\n", $message);
        foreach ($lines as $line) {
            $chunk = $chunk . $line;
            if ((strlen($chunk) > 1000) || ($line == end($lines))) {
                $chunks[] = $chunk;
                $chunk = '';
            }
        }
        $chunks = array_slice($chunks, 0, 3); // Max 3 x 1000 characters is enough text to read

        // Send all messages
        foreach ($chunks as $chunk) {

            // Telegram limits to 4096 maximum
            if (strlen($chunk) > 4000) {
                $chunk = substr($chunk, 0, 4000) . '..................';
            }

            // Send message
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "chat_id={$destination}&parse_mode=HTML&text=$chunk");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
}
