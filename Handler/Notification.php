<?php

namespace Bydn\ImprovedLogger\Handler;

use Monolog\Logger;

class Notification extends \Monolog\Handler\AbstractHandler
{
    /**
     * Log minimum handling level
     * @var int
     */
    protected $level = Logger::ERROR;

    /**
     * @var bool
     */
    protected $bubble = false;

    /**
     * @var \Bydn\ImprovedLogger\Helper\Config
     */
    private $loggerConfig;

    /**
     * @var \Bydn\ImprovedLogger\Model\Email
     */
    private $emailSender;

    /**
     * @var \Bydn\ImprovedLogger\Model\Telegram
     */
    private $telegramSender;

    /**
     * @param \Bydn\ImprovedLogger\Helper\Config $loggerConfig
     */
    public function __construct(
        \Bydn\ImprovedLogger\Helper\Config $loggerConfig,
        \Bydn\ImprovedLogger\Model\Email $emailSender,
        \Bydn\ImprovedLogger\Model\Telegram $telegramSender
    ) {
        $this->loggerConfig = $loggerConfig;
        $this->emailSender = $emailSender;
        $this->telegramSender = $telegramSender;
    }

    /**
     * @param array $record
     * @return bool
     */
    public function handle(array $record): bool
    {
        if (
            $this->loggerConfig->isEmailNotificationEnabled() &&
            $this->loggerConfig->isTelegramNotificationEnabled()
        ) {
            $text = $record['message'];
            if (isset($record['context'])) {
                foreach ($record['context'] as $key => $value) {
                    $text = $text . " | " . $key . ' => ' . (!is_array($value) ? $value : 'array');
                }
            }
            if ($this->loggerConfig->isEmailNotificationEnabled()) {
                $this->telegramSender->sendTelegramMessage($text);
            }
            if ($this->loggerConfig->isTelegramNotificationEnabled()) {
                $this->emailSender->sendAlertEmail('Magento Log Alert', $text);
            }
        }

        return $this->bubble;
    }
}
