<?php
//携程风控接口 PHP版本
class CtripRisk
{
        static $secret    = 'd84974a23c604edfb66b12190xx';//密钥
        static $token     = 'df2f8ced635a4f8a8d6d8559bxx';//token
        static $url       = 'http://api-security.ctrip.com/secsaas-service/services/risk'; //请求地址

        //post请求
        public function ctrip_risk($url,$jsonStr)
        {
                $ch  = curl_init($url);
                $ch  = curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                $ch  = curl_setopt($ch,CURLOPT_POST,1);
                $ch  = curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonStr);
                $out = curl_exec($ch);
                return json_decode($out,true);
        }

        //获取到签名
        public function getIpRiskRes($ip)
        {
                $jsonStr = $this->getJsonStr($ip);
                $this->ctrip_risk(self::$url,$jsonStr);
        }

        //使用加密算法生成加密签名
        public function getJsonStr($ip)
        {
                $origin    = '{"type":"IP","value":"'.$ip.'","scene":"login","token":"'.self::$token.'"}';
                $signature = base64_encode(hash_hmac("sha1", $origin, self::$secret, true));  //加密签名
                return     '{"type":"IP","value":"'.$ip.'","scene":"login","token":"'.self::$token.'","sign":"'.$signature.'"}'
        }
}
            
$obj = new CtripRisk();
$ip  = $_GET['ip']; 
$res = $obj->getIpRiskRes($ip);
var_dump($res); 

       

        