<?php

namespace Bydn\ImprovedLogger\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    private const LOGGER_EXTRAINFO_ENABLE = 'bydn_improved_logger/log_files/enable';
    private const LOGGER_EXTRAINFO_IP = 'bydn_improved_logger/log_files/ip_addresses';
    private const LOGGER_EXTRAINFO_CONTROLLER = 'bydn_improved_logger/log_files/controller_info';
    private const LOGGER_EXTRAINFO_URL = 'bydn_improved_logger/log_files/browser_url';
    private const LOGGER_EXTRAINFO_TRACE = 'bydn_improved_logger/log_files/trace_info';
    private const LOGGER_EMAIL_NOTIFICATION_ENABLE = 'bydn_improved_logger/email/enable';
    private const LOGGER_EMAIL_NOTIFICATION_EMAIL = 'bydn_improved_logger/email/email';
    private const LOGGER_EMAIL_EXCEPTIONS = 'bydn_improved_logger/email/exceptions';
    private const LOGGER_TELEGRAM_NOTIFICATION_ENABLE = 'bydn_improved_logger/telegram/enable';
    private const LOGGER_TELEGRAM_NOTIFICATION_TOKEN = 'bydn_improved_logger/telegram/token';
    private const LOGGER_TELEGRAM_NOTIFICATION_CHAT_ID = 'bydn_improved_logger/telegram/chat_id';
    private const LOGGER_TELEGRAM_EXCEPTIONS = 'bydn_improved_logger/telegram/exceptions';

    /**
     * Check if extra info in logs is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isExtrainfoEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EXTRAINFO_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if extra info in logs is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isExtrainfoIpEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EXTRAINFO_IP,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if extra info in logs is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isExtrainfoControllerEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EXTRAINFO_CONTROLLER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if extra info in logs is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isExtrainfoUrlEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EXTRAINFO_URL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if extra info in logs is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isExtrainfoTraceEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EXTRAINFO_TRACE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if email notification is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isEmailNotificationEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EMAIL_NOTIFICATION_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if email exceptions by email is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isEmailExceptionsEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EMAIL_EXCEPTIONS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns notification email
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function getNotificationEmail($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_EMAIL_NOTIFICATION_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if email notification is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isTelegramNotificationEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_TELEGRAM_NOTIFICATION_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if email exceptions by email is enabled
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function isTelegramExceptionsEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_TELEGRAM_EXCEPTIONS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns telegram API token
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function getTelegramToken($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_TELEGRAM_NOTIFICATION_TOKEN,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Returns telegram chat ID
     *
     * @param null|int|string $storeId
     * @return mixed
     */
    public function getTelegramChatId($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::LOGGER_TELEGRAM_NOTIFICATION_CHAT_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
