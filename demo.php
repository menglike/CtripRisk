<?php
//携程风控接口 PHP版本
class CtripRisk
{
        static $secret    = 'd84974a23c604edfb66b12190xx';//密钥
        static $token     = 'df2f8ced635a4f8a8d6d8559bxx';//token
        static $url       = 'http://api-security.ctrip.com/secsaas-service/services/risk'; //请求地址

        //post请求
        public function ctrip_risk($url,$jsonStr)
        {       $head[] = 'Content-Type:application/json';
                $ch  = curl_init($url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonStr);
                curl_setopt($ch,CURLOPT_HTTPHEADER,$head);
                $out = curl_exec($ch);
                curl_close($ch);
                return json_decode($out,true);
        }

        //获取到签名
        public function getIpRiskRes($ip,$type='IP')
        {
                $jsonStr = $this->getJsonStr($ip,$type);
                $this->ctrip_risk(self::$url,$jsonStr);
        }

        //使用加密算法生成加密签名
        //type : IP | Mobile
        public function getJsonStr($ip,$type)
        {
                $origin    = '{"type":"'.$type.'","value":"'.$ip.'","scene":"login","token":"'.self::$token.'"}';
                $signature = base64_encode(hash_hmac("sha1", $origin, self::$secret, true));  //加密签名
                return     '{"type":"'.$type.'","value":"'.$ip.'","scene":"login","token":"'.self::$token.'","sign":"'.$signature.'"}';
        }
}
            
$obj = new CtripRisk();
$ip  = $_GET['ip']; 
//$phone = $_GET['phone'];
$res = $obj->getIpRiskRes($ip);
var_dump($res); 

       

        