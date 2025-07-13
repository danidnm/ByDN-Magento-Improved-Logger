<?php

namespace Bydn\ImprovedLogger\Processor;

use Bydn\ImprovedLogger\Helper\Config;

class Extrainfo
{
    /**
     * @var Config
     */
    private $loggerConfig;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\ServerAddress
     */
    private $serverAddress;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param \Magento\Framework\HTTP\PhpEnvironment\ServerAddress $serverAddress
     */
    public function __construct(
        \Bydn\ImprovedLogger\Helper\Config $loggerConfig,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Framework\HTTP\PhpEnvironment\ServerAddress $serverAddress,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->loggerConfig = $loggerConfig;
        $this->request = $request;
        $this->remoteAddress = $remoteAddress;
        $this->serverAddress = $serverAddress;
        $this->url = $url;
    }

    public function __invoke($record) {

        // Check extra info enabled
        if (!$this->loggerConfig->isExtrainfoEnabled()) {
            return $record;
        }

        // Create context array
        if (!is_array($record->extra)) {
            $record->extra = [];
        }

        // Add IP addresses
        if ($this->loggerConfig->isExtrainfoIpEnabled()) {
            $record->extra['remote_address'] = $this->remoteAddress->getRemoteAddress() ?: 'unknown';
            $record->extra['server_address'] = $this->serverAddress->getServerAddress() ?: 'unknown';
            $record->extra['client_ip'] = $this->request->getClientIp() ?: 'unknown';
        }

        // Controller info
        if ($this->loggerConfig->isExtrainfoControllerEnabled()) {
            $record->extra['module'] = $this->request->getControllerModule() ?: 'unknown';
            $record->extra['controller'] = $this->request->getControllerModule() ?: 'unknown';
            $record->extra['action'] = $this->request->getControllerModule() ?: 'unknown';
        }

        // URL info
        if ($this->loggerConfig->isExtrainfoUrlEnabled()) {
            $record->extra['url'] = $this->url->getCurrentUrl() ?: 'unknown';
        }

        // Trace info
        if ($this->loggerConfig->isExtrainfoTraceEnabled()) {
            $e = new \Exception();
            $trace = $e->getTrace();
            $callInfo = $trace[3] ?? null;
            if ($callInfo) {
                $record->extra['file'] = $callInfo['file'] ?? 'unknown';
                $record->extra['line'] = $callInfo['line'] ?? 'unknown';
            }
        }

        return $record;
    }
}
