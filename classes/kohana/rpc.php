<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_RPC  {
    /**
    * RPC Instance
    * @var object
    */

    protected static $config;
   /*
     *    function appaends all GLOBAL vars that declared in included files
    */
    private function __globalvars()
    {
        $result=array();
        $skip=array('GLOBALS','_ENV','HTTP_ENV_VARS',
                            '_POST','HTTP_POST_VARS','_GET',
                            'HTTP_GET_VARS',
                            '_COOKIE',
                            'HTTP_COOKIE_VARS','_SERVER',
                            'HTTP_SERVER_VARS',
                            '_FILES','HTTP_POST_FILES',
                            '_REQUEST','HTTP_SESSION_VARS',
                            '_SESSION');
        foreach($GLOBALS as $k=>$v)
            if(!in_array($k,$skip))
                $result[$k]=$v;
        return $result;
    }
    /**
     * Automatically executed before the controller action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        require_once Kohana::find_file('vendor', 'xmlrpc/lib/xmlrpc','inc');
        require_once Kohana::find_file('vendor', 'xmlrpc/lib/xmlrpcs','inc');
        require_once Kohana::find_file('vendor', 'xmlrpc/lib/xmlrpc_wrappers','inc');

        foreach($this->__globalvars() as $key =>$value)
        {
            $this->{$key} = $value;
        }
        // register all classes declared in included files
        spl_autoload_call('xmlrpcresp');
        spl_autoload_call('xmlrpcval');
        spl_autoload_call('xmlrpc_client');
        spl_autoload_call('xmlrpcmsg');
        spl_autoload_call('xmlrpc_server');

    }

    public function __construct()
    {

        $this->before();$this->check_user();
    }
    
    public static function factory($config_name = 'default')
    {
        self::$config = Kohana::$config->load('rpc')->get($config_name);
        return new self();
    }

    public function check_user()
    {
        $config = self::$config;
        if($config['check_user_agent'])
        {
            $user_agent = Request::user_agent( 'browser');
            if($user_agent && !in_array($user_agent,$config['allowed_user_agents']))
            {
                exit;
            }
            else
            {
                preg_match('/'.implode($config['allowed_user_agents'],'|').'/',Request::$user_agent, $matches, PREG_OFFSET_CAPTURE)  ;
                if(count($matches) == 0)
                     exit;
            }


        }
    }
    public function xmlRPCServer($dispMap = array())
    {
        if(count($dispMap) > 0)
            $this->xmlrpc_server = new xmlrpc_server($dispMap,TRUE);
        else
            $this->xmlrpc_server = new xmlrpc_server($dispMap,FALSE);
        return $this;
    }
}