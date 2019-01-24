<?php

namespace App\Response;

use Log;
use App\Http\FxApi;

class ResponseHandler
{

   /**
    * @param App\Response\Response $response
    * @return Array
    */
    public static function buildApiResponse(Response $response)
    {
        if ($response->success())
        {
            return FxApi::rawResp($response->data());
        }
        else
        {
            return FxApi::rawError(array_map(
                function($e){ return $e->getCode(); }, 
                $response->errors()
            ));
        }
    }

   /**
    * log error if status is failed
    * @param App\Response\Response $response
    * @return Array
    */
    public static function logResponse(Response $response)
    {
        if ($response->failed())
        {
            array_map(['Log', 'error'], $response->errors());
        }
    }

}