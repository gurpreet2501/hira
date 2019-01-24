<?php
namespace App\Libs;
use App\Models as M;

class MobileSms{
 
 function __construct(){
 	parent::__construct();
 }

 public static function send($mobileNo, $msg){

    if(is_null($mobileNo))
        return false;
      
    try{
      // echo 'Testing';
        // $mobileNo = '919814158141';
        $characters = strlen($mobileNo);
        if($characters <= 10)
           $mobileNo =  '91'.$mobileNo;
        $mobileNo = urlencode($mobileNo);
      
        $msg = urlencode($msg);
        // $url = "http://103.15.179.45:8085/MessagingGateway/SendTransSMS?Username={$username}&Password={$password}&MessageType=txt&Mobile={$mobileNo}&SenderID={$senderId}&Message={$msg}";


        $url = "http://sms.thinkbuyget.com/api.php?username=".env('sms_username','Gurpreet')."&password=".env('sms_password','472186')."&sender=".env('sms_sender','DGENIT')."&sendto=".$mobileNo."&message=".$msg;
        
        $ch = curl_init($url);
        // $ch = curl_init("https://jsonplaceholder.typicode.com/posts/1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content  = curl_exec($ch);
        
        if(!strstr(strtolower($content), "sent successfully to"))
              return false;

        return true;


      }catch(\Exception $e){
        return false;
     }

     return true;
  }

  public static function sendToMany($mobileNos, $msg){

 	  if(!count($mobileNos))
        return false;
      
    try{
      // echo 'Testing';
        // $mobileNo = '919814158141';
      foreach ($mobileNos as $key => &$mobileNo) {
        # code...
        $characters = strlen($mobileNo);
        if($characters <= 10)
           $mobileNo =  '91'.$mobileNo;
        $mobileNo = trim(urlencode($mobileNo));
      }

      
        $msg = urlencode($msg);
        // $url = "http://103.15.179.45:8085/MessagingGateway/SendTransSMS?Username={$username}&Password={$password}&MessageType=txt&Mobile={$mobileNo}&SenderID={$senderId}&Message={$msg}";

        $url = "http://sms.thinkbuyget.com/api.php?username=".env('sms_username','Gurpreet')."&password=".env('sms_password','472186')."&sender=".env('sms_sender','DGENIT')."&sendto=".implode(',',$mobileNos)."&message=".$msg;
        
        $ch = curl_init($url);
        // $ch = curl_init("https://jsonplaceholder.typicode.com/posts/1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content  = curl_exec($ch);
        
        if(!strstr(strtolower($content), "sent successfully to"))
              return false;

        return true;


 	    }catch(\Exception $e){
        return false;
     }

     return true;
  }
}