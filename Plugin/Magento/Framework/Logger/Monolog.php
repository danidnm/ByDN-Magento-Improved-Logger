<?php

namespace Bydn\ImprovedLogger\Plugin\Magento\Framework\Logger;

class Monolog
{
    /**
     * @var \Bydn\ImprovedLogger\Handler\Notification
     */
    private $notificationHandler;

    /**
     * @param \Bydn\ImprovedLogger\Handler\Notification $notificationHandler
     */
    public function __construct(\Bydn\ImprovedLogger\Handler\Notification $notificationHandler)
    {
        $this->notificationHandler = $notificationHandler;
    }

    /**
     * @param \Magento\Framework\Logger\Monolog $subject
     * @return void
     */
    public function afterSetHandlers(\Magento\Framework\Logger\Monolog $subject, $result)
    {
        // Check if the handler is already added to avoid duplication
        foreach ($subject->getHandlers() as $handler) {
            if ($handler instanceof \Bydn\ImprovedLogger\Handler\Notification) {
                return; // Handler already exists, do nothing
            }
        }

        // Add the custom handler
        $subject->pushHandler($this->notificationHandler);

        return $result;
    }
}
