<?php
namespace App\Response;

use log;

class Response
{
  const FAILED  = 'FAILED';
  const SUCCESS = 'SUCCESS';

  private $_errors, $_data , $_status;

  public function __construct($data)
  {
    $this->_errors = $data['ERRORS'];
    $this->_data   = $data['DATA'];
    $this->_status = $data['STATUS'];
  }

  function errors(){
    return $this->_errors;
  }

  function errorsArray(){
    $errors = [];
    foreach($this->_errors as $err)
      $errors[$err->getCode()] = $err->getMessage();
    return $errors;
  }

  function throwFirst(){
    if(isset($this->_errors[0]))
      throw $this->_errors[0];
  }

  function data(){
    return $this->_data;
  }

  function success(){
    return ($this->_status == self::SUCCESS);
  }

  function failed(){
    return ($this->_status == self::FAILED);
  }

  function dumpDie(){
    if($this->failed()){
      echo '<pre>';
      echo 'FAILED'.PHP_EOL;
      echo '</pre>';
      $this->printErrors();
      exit;
    }
    echo '<pre>';
    echo 'SUCCESS'.PHP_EOL;
    echo '</pre>';
    $this->printData();
    exit;
  }

  function printErrors(){
    echo '<pre>';
    foreach($this->_errors as $err){
      echo PHP_EOL;
      if($err->getCode())
        echo 'ERROR CODE: '.$err->getCode() . ' - ';
      if($err->getMessage())
        echo 'ERROR Message:'.$err->getMessage();
    }
    echo '</pre>';
  }

  function printData(){
    echo '<pre>';
    print_r($this->_data);
    echo '</pre>';
  }

  function logErrors(){
    foreach($this->_errors as $err)
      log::error($err);

  }

}
