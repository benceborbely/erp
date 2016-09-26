<?php

namespace Bence\ErrorHandler\Action;

use Bence\Service\MailerService;

/**
 * Class Mail
 *
 * @author Bence Borbély
 */
class Mail implements ActionInterface
{

    /**
     * @var MailerService
     */
    protected $mailerService;

    /**
     * @var string
     */
    protected $recipient;

    /**
     * @param MailerService $mailerService
     * @param string $recipient
     */
    public function __construct(MailerService $mailerService, $recipient)
    {
        $this->mailerService = $mailerService;
        $this->recipient = $recipient;
    }

    /**
     * @param string $msg
     */
    public function run($msg)
    {
        $date = new \DateTime();

        $mail = new \Bence\Model\Mail();
        $mail->setRecipient($this->recipient);
        $mail->setSubject('[error log] ' . $date->format('Y-m-d H:i:s'));
        $mail->setMessage($msg);

        $this->mailerService->sendMail($mail);
    }

}
