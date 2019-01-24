<?php
namespace App\Response;

use \Exception;
use App\Response\Response;


// Can be improved with in memory caching
class Factory
{


    /**
     * Build raw response
     * @param Response $resp
     * @return Array
     */
    public static function raw(Response $resp)
    {
        return ResponseHandler::buildApiResponse($resp);
    }

    /**
     *
     * @param Response $resp
     * @return Void
     */
    public static function log(Response $resp)
    {
        ResponseHandler::logResponse($resp);
    }

    /**
     * @param Any $data
     * @return Array
     */
    public static function success($data=null)
    {
        return new Response([
            'ERRORS'  => [],
            'DATA'    => $data,
            'STATUS'  => Response::SUCCESS
        ]);
    }

    /**
     * @param String $str
     * @return Array
     */
    public static function error($str)
    {
        return self::errors([$str]);
    }

    /**
     * @param Array of String $strs
     * @return Array
     */
    public static function errors(Array $strs)
    {
        $exceptions = [];
        foreach($strs as $str)
          $exceptions[] = new Exception($str);

        return new Response([
          'ERRORS'   => $exceptions,
          'DATA'    => null,
          'STATUS'  => Response::FAILED
        ]);
    }


    /**
     * @param Integer $code
     * @return Array
     */
    public static function errorCode($code)
    {
        return self::fixdErrorCodes([$code]);
    }

    /**
     * @param Array of Integer $codes
     * @return Array
     */
    public static function errorCodes(Array $codes)
    {
        return self::fixdErrorCodes($codes);
    }

    /**
     * @param Integer $code
     * @return Array
     */
    public static function fixdErrorCode($code)
    {
        return self::fixdErrorCodes([$code]);
    }

    /**
     * @param Array of Integer $strs
     * @return Array
     */
    public static function fixdErrorCodes(Array $codes)
    {
        $exceptions = [];
        foreach($codes as $code)
          $exceptions[] = new Exception(config("error.{$code}"), $code);

        return new Response([
          'ERRORS'  =>  $exceptions,
          'DATA'    =>  null,
          'STATUS'  =>  Response::FAILED
        ]);
    }

    /**
     * @param Exception $e
     * @return Array
     */
    public static function exception(Exception $e)
    {
        return self::exceptions([$e]);
    }

    /**
     * @param Array of Exception $exceptions
     * @return Array
     */
    public static function exceptions(Array $exceptions)
    {
        return new Response([
          'ERRORS'  =>  $exceptions,
          'DATA'    =>  null,
          'STATUS'  =>  Response::FAILED
        ]);
    }

    public static function btErrors($result)
    {
      $exceptions = [];
      foreach($result->errors->deepAll() as $e)
        $exceptions[] = new Exception($e->__get('message'), $e->__get('code'));
      return self::exceptions($exceptions);
    }
}
