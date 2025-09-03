<?php

namespace Bydn\ImprovedLogger\Handler;

use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Psr\Log\LoggerInterface;

class Notification extends \Monolog\Handler\AbstractHandler
{
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
     * @param LoggerInterface $logger
     * @param \Bydn\ImprovedLogger\Helper\Config $loggerConfig
     * @param \Bydn\ImprovedLogger\Model\Email $emailSender
     * @param \Bydn\ImprovedLogger\Model\Telegram $telegramSender
     * @param int|string|Level $level
     * @param bool $bubble
     * @param bool $includeExtra
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Bydn\ImprovedLogger\Helper\Config $loggerConfig,
        \Bydn\ImprovedLogger\Model\Email $emailSender,
        \Bydn\ImprovedLogger\Model\Telegram $telegramSender,
        int|string|Level $level = Level::Emergency,
        bool $bubble = false,
        bool $includeExtra = false
    ) {
        parent::__construct($level, $bubble);
        $this->loggerConfig = $loggerConfig;
        $this->emailSender = $emailSender;
        $this->telegramSender = $telegramSender;
    }

    /**
     * @param array $record
     * @return bool
     */
    public function handle(LogRecord $record): bool
    {
        // Only notify emergencies and critical
        if (!$this->isHandling($record)) {
            return false;
        }

        // Check if notifications are enabled
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
