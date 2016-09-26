<?php

namespace Bence\Service;

use Bence\Model\Mail;

/**
 * Class MailerService
 *
 * @author Bence Borbély
 */
class MailerService
{

    /**
     * @param Mail $mail
     */
    public function sendMail(Mail $mail)
    {
        mail($mail->getRecipient(), $mail->getSubject(), $mail->getMessage());
    }

}
