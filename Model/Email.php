<?php

namespace Bydn\ImprovedLogger\Model;
class Email
{
    public const EMAIL_TEMPLATE = 'debug_email';
    public const XML_PATH_EMAIL_IDENTITY = 'contact/email/sender_email_identity';

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Bydn\ImprovedLogger\Helper\Config
     */
    private $loggerConfig;

    /**
     * Prevents recursive exception by email
     * @var bool
     */
    private $nestedEmailException = false;

    /**
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Bydn\ImprovedLogger\Helper\Config $loggerConfig
     */
    public function __construct(
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Bydn\ImprovedLogger\Helper\Config $loggerConfig
    ) {
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->loggerConfig = $loggerConfig;
    }

    /**
     * Sends an email alert
     *
     * @param string $subject
     * @param string $message
     * @return void
     */
    public function sendAlertEmail($subject, $message)
    {
        // Check if notification is enabled
        if (!$this->loggerConfig->isEmailNotificationEnabled()) {
            return;
        }

        // Check destination or return
        $destinations = $this->loggerConfig->getNotificationEmail();
        if (empty($destinations)) {
            return;
        }
        $destinations = explode(',', $destinations);
        $destinations = array_map('trim', $destinations);
        $destinations = array_filter($destinations);

        // Get stack trace to append to the email
        $trace = (new \Exception())->getTraceAsString();
        $trace = nl2br($trace);

        $this->inlineTranslation->suspend();
        $this->transportBuilder
            ->setTemplateIdentifier(self::EMAIL_TEMPLATE)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars([
                'subject' => $subject,
                'message' => $message,
                'trace' => $trace
            ])
            ->setFromByScope(
                $this->scopeConfig->getValue(
                    self::XML_PATH_EMAIL_IDENTITY,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );

        // Add destinations
        foreach ($destinations as $destination) {
            $this->transportBuilder->addTo($destinations);

        }

        // Get transport and send
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();

        $this->inlineTranslation->resume();
    }
}
