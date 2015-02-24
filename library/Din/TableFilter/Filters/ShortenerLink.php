<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;
use Din\UrlShortener\Bitly\Bitly;

class ShortenerLink extends AbstractFilter
{

  public function filter ( $field )
  {
    if ( URL && BITLY && $this->_table->uri ) {
      $url = URL . $this->_table->uri;

      try {
        $bitly = new Bitly(BITLY);

        if ( defined('BITLY_DOMAIN') && BITLY_DOMAIN ) {
          $bitly->setDomain(BITLY_DOMAIN);
        }

        $bitly->shorten($url);
        $this->_table->short_link = (string) $bitly;
      } catch (\Exception $e) {
        //die($e->getMessage());
      }
    }

  }

}
