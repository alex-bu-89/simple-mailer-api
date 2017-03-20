<?php

use \Jacwright\RestServer\RestException;

require __DIR__ . '/source/PHPMailer/PHPMailerAutoload.php';

class MailController
{

    const SENDER_EMAIL = 'info@vkommunare.ru';
    const SENDER_NAME = 'Коммунар';

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /
     */
    public function test()
    {
      return "Email API is running";
    }

    /**
     * Send simple email
     *
     * @url POST /mail
     */
    public function mailContactForm()
    {
      if (empty($_POST['email']) ||
          empty($_POST['subject']) ||
          empty($_POST['phone']) ||
          empty($_POST['body']) ||
          empty($_POST['name']))
      {
        self::throwError('Some parametrs in body are empty');
      }

      $email = $_POST['email'];
      $subject = $_POST['subject'];
      $phone = $_POST['phone'];
      $body = $_POST['body'];
      $name = $_POST['name'];

      // create header
      $template = file_get_contents( __DIR__ . '/email_template/header.html');

      // create body
      $template .= "<p><strong>Вопрос с формы обратной связи:</strong></p>";
      $template .= "<p>Имя: " . $name . "<br />";
      $template .= "Email: " . $email . "<br />";
      $template .= "Телефон: " . $phone . "<br />";
      $template .= "Сообщение: " . $body . "</p>";

      // create footer
      $template .= file_get_contents( __DIR__ . '/email_template/footer.html');

      $mail = self::createSMTPMailer();

      $mail->addAddress('alex.bu.89@gmail.com', 'Alexander Buyanov'); // TODO change email
      $mail->Subject = 'Вопрос с формы обратной связи: ' . $subject;
      $mail->msgHTML($template);

      // send the message, check for errors
      if (!$mail->send()) {
        self::throwError('Error while sending email. ' . $mail->ErrorInfo);
      } else {
        return array(
          "code" => 200,
          "message" => "Message sent!",
          "email" => $email,
          "subject" => $subject,
          "phone" => $phone,
          "body" => $body,
        );
      }
    }

    /**
     * Create PHPMailer obj
     * @return [PHPMailer]
     */
    private function createSMTPMailer()
    {
      $mail = new PHPMailer;

      $mail->isSMTP();
      $mail->Host = "mail.hostland.ru";
      $mail->Port = 25;
      $mail->SMTPAuth = true;
      $mail->Username = "mail@usaplastic.ru"; // TODO change when get new domain name
      $mail->Password = "plastic89";
      $mail->CharSet = 'UTF-8';
      $mail->setFrom(self::SENDER_EMAIL, self::SENDER_NAME);

      return $mail;
    }

    /**
     * Throws an error
     *
     * @url GET /error
     */
    public function throwError($err = '') {
      throw new RestException(401, "Error by sending an email. " . $err);
    }

}
