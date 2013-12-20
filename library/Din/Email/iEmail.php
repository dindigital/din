<?php

namespace Din\Email;

interface iEmail {

    public function setTo ($email, $name = '');

    public function getTo ();

    public function setFrom ($email, $name = '');

    public function getFrom ();

}
