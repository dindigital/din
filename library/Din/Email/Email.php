<?php

namespace Din\Email;

use Exception;

/**
 * Classe para criar um objeto e-mail
 */
class Email implements iEmail {

    private $_from = array();
    private $_to = array();
    private $_cc = array();
    private $_co = array();
    private $_replyTo = array();
    private $_subject;
    private $_body;

    public function setFrom ($email, $name = '')
    {
        $this->_from = array(
            'email' => $email,
            'name' => $name
        );
    }

    public function getFrom ()
    {
        if (!count($this->_from)) {
            throw new Exception('E-mail FROM não configurado');
        }

        return $this->_from;
    }

    public function setTo ($email, $name = '')
    {
        $this->_to[] = array(
            'email' => $email,
            'name' => $name
        );
    }

    public function getTo ()
    {
        if (!count($this->_to)) {
            throw new Exception('E-mail TO não configurado');
        }

        return $this->_to;
    }

    public function setCc ($email, $name = '')
    {
        $this->_cc[] = array(
            'email' => $email,
            'name' => $name
        );
    }

    public function getCc ()
    {
        return $this->_cc;
    }

    public function setCo ($email, $name = '')
    {
        $this->_co[] = array(
            'email' => $email,
            'name' => $name
        );
    }

    public function getCo ()
    {
        return $this->_co;
    }

    public function setReplyTo ($email, $name = '')
    {
        $this->_replyTo = array(
            'email' => $email,
            'name' => $name
        );
    }

    public function getReplyTo ()
    {
        return $this->_replyTo;
    }

    public function setSubject ($subject)
    {

        $this->_subject = $subject;
    }

    public function getSubject ()
    {
        if (!$this->_subject) {
            throw new Exception('Assunto não informado');
        }

        return $this->_subject;
    }

    public function setBody ($body)
    {
        $this->_body = $body;
    }

    public function getBody ()
    {
        if (!$this->_body) {
            throw new Exception('Body não configurado');
        }

        return $this->_body;
    }

}
