<?php
declare(strict_types=1);

namespace Bydn\ImprovedLogger\Console\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Console\Cli;

class SendAlert extends Command
{
    private const SUBJECT_OPTION = 'subject';
    private const BODY_OPTION = 'body';
    private const CHANNEL_OPTION = 'channel';

    /**
     * @var \Bydn\ImprovedLogger\Model\Email
     */
    private $email;

    /**
     * @var \Bydn\ImprovedLogger\Model\Telegram
     */
    private $telegram;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param \Bydn\ImprovedLogger\Model\Email $email
     * @param \Bydn\ImprovedLogger\Model\Telegram $telegram
     * @param LoggerInterface $logger
     * @param string|null $name
     */
    public function __construct(
        \Bydn\ImprovedLogger\Model\Email $email,
        \Bydn\ImprovedLogger\Model\Telegram $telegram,
        LoggerInterface $logger,
        ?string $name = null
    ) {
        $this->email = $email;
        $this->telegram = $telegram;
        $this->logger = $logger;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('bydn:logger:alert')
            ->setDescription('Send an alert using the ImprovedLogger module')
            ->addOption(
                self::CHANNEL_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Alert channel (email, telegram, or both)'
            )
            ->addOption(
                self::SUBJECT_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Email subject'
            )
            ->addOption(
                self::BODY_OPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Email body'
            );
            
        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->emergency('SendAlert console command executed.');
        $channel = $input->getOption(self::CHANNEL_OPTION) ? strtolower((string)$input->getOption(self::CHANNEL_OPTION)) : 'email';
        $subject = $input->getOption(self::SUBJECT_OPTION);
        $body = $input->getOption(self::BODY_OPTION);

        if (!$body) {
            $output->writeln('<error>The --body option is required.</error>');
            return Cli::RETURN_FAILURE;
        }

        if (in_array($channel, ['email', 'both']) && !$subject) {
            $output->writeln('<error>The --subject option is required for email channel.</error>');
            return Cli::RETURN_FAILURE;
        }

        try {
            if (in_array($channel, ['email', 'both'])) {
                $this->email->sendAlertEmail((string)$subject, (string)$body);
                $output->writeln('<info>Alert email sent successfully.</info>');
            }
            
            if (in_array($channel, ['telegram', 'both'])) {
                $telegramMessage = $subject ? "<b>{$subject}</b>\n{$body}" : $body;
                $this->telegram->sendTelegramMessage($telegramMessage);
                $output->writeln('<info>Telegram alert sent successfully.</info>');
            }

            if (!in_array($channel, ['email', 'telegram', 'both'])) {
                $output->writeln('<error>Invalid channel specified. Use email, telegram, or both.</error>');
                return Cli::RETURN_FAILURE;
            }

            return Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error sending alert: ' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
    }
}
