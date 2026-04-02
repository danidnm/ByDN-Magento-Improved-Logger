<?php

namespace Bydn\ImprovedLogger\Handler;

use Monolog\Logger;

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
     * @param \Bydn\ImprovedLogger\Helper\Config $loggerConfig
     * @param \Bydn\ImprovedLogger\Model\Email $emailSender
     * @param \Bydn\ImprovedLogger\Model\Telegram $telegramSender
     * @param int|string|Level $level
     * @param bool $bubble
     */
    public function __construct(
        \Bydn\ImprovedLogger\Helper\Config $loggerConfig,
        \Bydn\ImprovedLogger\Model\Email $emailSender,
        \Bydn\ImprovedLogger\Model\Telegram $telegramSender,
        $level = Logger::EMERGENCY,
        bool $bubble = false
    ) {
        parent::__construct($level, $bubble);
        $this->loggerConfig = $loggerConfig;
        $this->emailSender = $emailSender;
        $this->telegramSender = $telegramSender;
    }

    /**
     * @param array|\Monolog\LogRecord $record
     * @return bool
     */
    public function handle($record): bool
    {
        // For compatibility with Monolog 2 and 3
        // In Monolog 3, isHandling() expects a LogRecord object.
        // If we are in Monolog 3 (Level class exists) and $record is an array, we handle it manually
        if (is_array($record) && class_exists('\Monolog\Level')) {
            $recordLevel = Logger::toMonologLevel($record['level']);
            if ($recordLevel->value < $this->level->value) {
                return false;
            }
        } elseif (!$this->isHandling($record)) {
            return false;
        }

        // Check if notifications are enabled
        if (
            $this->loggerConfig->isEmailNotificationEnabled() ||
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
