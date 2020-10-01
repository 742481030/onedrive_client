<?php
namespace jane;
class oendrive_client
{
      
      public $client_id  ;
      public $client_secret;
      public $redirect_uri;
      public $refresh_token;
      public $token;
      public $apiurl;
      public $graphurl          =   "https://microsoftgraph.chinacloudapi.cn/v1.0";
      public $authurl           =   "https://login.chinacloudapi.cn/common/oauth2/v2.0/authorize";
      public $tokenurl          =   "https://login.chinacloudapi.cn/common/oauth2/v2.0/token";
      public $expires_on;
    
    function __construct( array $config)
    {
         $this->client_id        =   $config["client_id"]       ?? "3447f073-eef3-4c60-bb68-113a86f2c39a";
         $this->client_secret    =   $config['client_secret']   ?? "v4[Nq:4=rmFS78BwYi[@x3sGk-iY.U:S";
         $this->redirect_uri     =   $config['redirect_uri']    ?? "https://coding.mxin.ltd/";
         $this->apiurl           =   $config['apiurl']??"https://microsoftgraph.chinacloudapi.cn/v1.0/me/drive";
         $this->refresh_token    =   $config['refresh_token'];
         $this->expires_on       =   $config["expires_on"];
             $this->token        =   $config["access_token"];
        if (parse_url($config['apiurl'],PHP_URL_HOST)!="microsoftgraph.chinacloudapi.cn")
            {
          
        $this->graphurl          =   "https://graph.microsoft.com/v1.0";
        $this->authurl           =   "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";
        $this->tokenurl          =   "https://login.microsoftonline.com/common/oauth2/v2.0/token";
         $this->apiurl           =   $config['apiurl']??"https://graph.microsoft.com/v1.0/me/drive";
        
    
             }
            
    }
    
    
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
      
      public function get_token()
    {
       
        $request['url']         =   $this->tokenurl ;
        $request['post_data']   =   "client_id={$this->client_id}&redirect_uri={$this->redirect_uri}&client_secret={$this->client_secret}&refresh_token={$this->refresh_token}&grant_type=refresh_token";
        $request['headers']     =   'Content-Type: application/x-www-form-urlencoded';
        $resp                   =   fetch::post($request);
        $data                   =   json_decode($resp->content, true);
    
        $this->token            =   $data["access_token"];
        $this->expires_on       =   $data["expires_on"];
        $this->expires_in       =   $data["expires_in"];
      
    }
    public function token()
    {
        $config                 =    config('@config');
     
        if ($this->refresh_token !==null) {//已经授权
        
            if ($this->expires_on> time() + 600) {
            return;//   
            } else {
            $this->get_token();
            $config['expires_on']       =   time() + $this->expires_in;
            $config['access_token']     =   $this->token;
            $config['expires_in']       =   $this->expires_in;
            config('@config', $config);
        
              }
         }else//没有授权
         {
             
             die("没有授权");
         }
        
    }//
    
    public function downloadbyid($id)
    {
        $request['headers']     =   "Authorization: bearer {$this->token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url']         =   $this->apiurl."/items/".$id.$itemid;
        $resp                   =   fetch::get($request);
        
        $g=json_decode($resp->content, true)['@microsoft.graph.downloadUrl'];
        header('Location:'.$g);
        
        exit;
        
        
    }
    
    public function dir($path='/'){
        
        $path                 =   surlencode($path);
        $path                 =   empty($path) ? '/' : ":/{$path}:/";
        $request['headers']   =   "Authorization: bearer {$this->token}".PHP_EOL.'Content-Type: application/json'.PHP_EOL;
        $request['url']       =   $this->apiurl."/root".$path."/children?select=name,size,folder,id,lastModifiedDateTime";
        $resp                 =   fetch::get($request);
        $data                 =   json_decode($resp->content, true);
       
        
        if ($data["value"][0]=="")
        {
            //echo "是文件";
        $request['url']      =   $this->apiurl."/root".$path."content";
        $res                 =   fetch::get($request);
        if($res->redirect_url!="" && $res->http_code==302)
        header('Location:'.$res->redirect_url);
       
        exit;
        }
        return ($data);
     }
     
     
     
         //验证URL，浏览器访问、授权
    public  function authorize_url()
    {
        $client_id = $this->client_id;
      //  $scope = urlencode('offline_access files.readwrite.all');
       

     
     $q= $_SERVER['HTTP_HOST'];
     $redirect_uris = ('http://'.$_SERVER['HTTP_HOST'].'/oauth.php');
  return $授权地址 = $this->authurl.'?client_id='.$this->client_id.'&scope=offline_access+files.readwrite.all+Sites.ReadWrite.All&response_type=code&redirect_uri=https://coding.mxin.ltd/&state='.$redirect_uris;
     
     
     
     

       //  echo $授权地址;
    }
     
     
     
     
     
     
     
     
}
