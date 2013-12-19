<?php

namespace Din\Email\SendEmail;

use Din\Email\Email;
use \PHPMailer;
use Exception;

class SendEmail
{

  private $_host;
  private $_port = 587;
  private $_smtpAuth = true;
  private $_smtpSecure = '';
  private $_smtpUser = '';
  private $_smtpPass = '';
  private $_sender;
  private $_email;

  public function __construct ( Email $email )
  {
    $this->_email = $email;

    $this->_sender = new PHPMailer();
    $this->_sender->isSMTP();
    $this->_sender->isHTML(true);

    $this->setFrom();
    $this->setTo();
    $this->setCc();
    $this->setCo();
    $this->setReplyTo();
  }

  public function setHost ( $host )
  {
    $this->_host = $host;
  }

  public function setPort ( $port )
  {
    $this->_port = $port;
  }

  public function setSmtpAuth ( $smtpAuth )
  {
    $this->_smtpAuth = $smtpAuth;
  }

  public function setSmtpSecure ( $smtpSecure )
  {
    $this->_smtpSecure = $smtpSecure;
  }

  public function setUser ( $user )
  {
    $this->_smtpUser = $user;
  }

  public function setPass ( $pass )
  {
    $this->_smtpPass = $pass;
  }

  public function send ()
  {
    $this->_sender->Host = $this->_host;
    $this->_sender->SMTPAuth = $this->_smtpAuth;
    $this->_sender->Username = $this->_smtpUser;
    $this->_sender->Password = $this->_smtpPass;
    $this->_sender->SMTPSecure = $this->_smtpSecure;
    $this->_sender->Subject = $this->_email->getSubject();
    $this->_sender->Body = $this->_email->getBody();
    if ( !$this->_sender->send() ) {
      throw new Exception($this->_sender->ErrorInfo);
    }
  }

  private function setFrom ()
  {
    $this->_sender->From = $this->_email->getFrom()['email'];
    $this->_sender->FromName = $this->_email->getFrom()['name'];
  }

  private function setTo ()
  {
    foreach ( $this->_email->getTo() as $row ) {
      $this->_sender->addAddress($row['email'], $row['name']);
    }
  }

  private function setCc ()
  {
    foreach ( $this->_email->getCc() as $row ) {
      $this->_sender->addCC($row['email'], $row['name']);
    }
  }

  private function setCo ()
  {
    foreach ( $this->_email->getCo() as $row ) {
      $this->_sender->addBCC($row['email'], $row['name']);
    }
  }

  private function setReplyTo ()
  {
    $replyTo = $this->_email->getReplyTo();
    if ( count($replyTo) ) {
      $this->_sender->addReplyTo($replyTo['email'], $replyTo['email']);
    }
  }

}
