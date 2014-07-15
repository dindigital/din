<?php

namespace Din\TableFilter\Filters;

use Din\TableFilter\AbstractFilter;

class PhotoMetadata extends AbstractFilter
{

  public function filter ( $field )
  {
    $file = $this->getValue($field);
    $file = 'tmp/' . $file[0]['tmp_name'];

    if ( !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'tiff')) )
      return;

    /**
     * LEITUR4 XMP
     */
    $xmp_raw = $this->get_xmp_raw($file);
    if ( $xmp_raw ) {

      $xml = new \DOMDocument;
      $xml->loadXML($xmp_raw);

      $array = $this->xml_to_array($xml);

      $description_tag = @$array['x:xmpmeta']['rdf:RDF']['rdf:Description'];
      $this->_table->rating = @$description_tag['@attributes']['Rating'];
      $this->_table->author_title = @$description_tag['@attributes']['AuthorsPosition'];
      $this->_table->author_email = @$description_tag['@attributes']['WebStatement'];
      $this->_table->author = @$description_tag['dc:creator']['rdf:Seq']['rdf:li'];
      $this->_table->copyright_notice = @$description_tag['dc:rights']['rdf:Alt']['rdf:li']['_value'];
      $this->_table->document_title = @$description_tag['dc:title']['rdf:Alt']['rdf:li']['_value'];
      $this->_table->description = @$description_tag['dc:description']['rdf:Alt']['rdf:li']['_value'];
      $this->_table->keywords = @$description_tag['dc:subject']['rdf:Bag']['rdf:li'];

      if ( is_array($this->_table->keywords) ) {
        $this->_table->keywords = implode(', ', $this->_table->keywords);
      }
    }

    /**
     * LEITURA EXIF
     */
    $exif = exif_read_data($file);
    if ( isset($exif['DateTimeOriginal']) ) {
      $date_time = explode(' ', $exif['DateTimeOriginal']);
      $date_taken = str_replace(':', '-', $date_time[0]) . ' ' . $date_time[1];

      $this->_table->date_taken = $date_taken;
    }

    if ( isset($exif['ImageDescription']) ) {
      $this->_table->label = trim($exif['ImageDescription']);
    }

    if ( isset($exif['Artist']) ) {
      $this->_table->credit = trim($exif['Artist']);
    }

  }

  function get_xmp_raw ( $filepath )
  {

    $this->use_cache = true;
    $this->cache_dir = '/tmp/';
    if ( !is_dir($this->cache_dir) )
      mkdir($this->cache_dir);

    $max_size = 512000;     // maximum size read
    $chunk_size = 65536;    // read 64k at a time
    $start_tag = '<x:xmpmeta';
    $end_tag = '</x:xmpmeta>';
    $cache_file = $this->cache_dir . md5($filepath) . '.xml';
    $xmp_raw = null;

    if ( $this->use_cache == true && file_exists($cache_file) &&
            filemtime($cache_file) > filemtime($filepath) &&
            $cache_fh = fopen($cache_file, 'rb') ) {

      $xmp_raw = fread($cache_fh, filesize($cache_file));
      fclose($cache_fh);
    } elseif ( $file_fh = fopen($filepath, 'rb') ) {

      $chunk = '';

      $file_size = filesize($filepath);
      while (( $file_pos = ftell($file_fh) ) < $file_size && $file_pos < $max_size) {
        $chunk .= fread($file_fh, $chunk_size);
        if ( ( $end_pos = strpos($chunk, $end_tag) ) !== false ) {
          if ( ( $start_pos = strpos($chunk, $start_tag) ) !== false ) {

            $xmp_raw = substr($chunk, $start_pos, $end_pos - $start_pos + strlen($end_tag));

            if ( $this->use_cache == true && $cache_fh = fopen($cache_file, 'wb') ) {

              fwrite($cache_fh, $xmp_raw);
              fclose($cache_fh);
            }
          }
          break;  // stop reading after finding the xmp data
        }
      }
      fclose($file_fh);
    }
    return $xmp_raw;

  }

  function xml_to_array ( $root )
  {
    $result = array();

    if ( $root->hasAttributes() ) {
      $attrs = $root->attributes;
      foreach ( $attrs as $attr ) {
        $result['@attributes'][$attr->name] = $attr->value;
      }
    }

    if ( $root->hasChildNodes() ) {
      $children = $root->childNodes;
      if ( $children->length == 1 ) {
        $child = $children->item(0);
        if ( $child->nodeType == XML_TEXT_NODE ) {
          $result['_value'] = $child->nodeValue;
          return count($result) == 1 ? $result['_value'] : $result;
        }
      }
      $groups = array();
      foreach ( $children as $child ) {
        if ( !isset($result[$child->nodeName]) ) {
          $result[$child->nodeName] = $this->xml_to_array($child);
        } else {
          if ( !isset($groups[$child->nodeName]) ) {
            $result[$child->nodeName] = array($result[$child->nodeName]);
            $groups[$child->nodeName] = 1;
          }
          $result[$child->nodeName][] = $this->xml_to_array($child);
        }
      }
    }

    return $result;

  }

}
