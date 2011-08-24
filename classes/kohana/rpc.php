<?php defined('SYSPATH') or die('No direct script access.');

require_once Kohana::find_file('vendor', 'xmlrpc/lib/xmlrpc','inc');
require_once Kohana::find_file('vendor', 'xmlrpc/lib/xmlrpcs','inc');
require_once Kohana::find_file('vendor', 'xmlrpc/lib/xmlrpc_wrappers','inc');

class Kohana_RPC  {
    /**
    * RPC Instance
    * @var object
    */
    protected static $instance;
    protected $config;
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
        $this->config = Kohana::$config->load('default');


        foreach($this->__globalvars() as $key =>$value)
        {
            $this->{$key} = $value;
        }
        spl_autoload_call('xmlrpcresp');
        spl_autoload_call('xmlrpcval');
        spl_autoload_call('xmlrpc_client');
        spl_autoload_call('xmlrpcmsg');
        spl_autoload_call('xmlrpc_server');

    }

    public function __construct()
    {
        $this->before();
    }
    
    public static function factory()
    {
       /// $_config = Kohana::$config->load($config) ;
        return new self();
    }
    public function xmlRPCServer($dispMap = array())
    {
        if(count($dispMap) > 0)
            $this->xmlrpc_server = new xmlrpc_server($dispMap);
        else
            $this->xmlrpc_server = new xmlrpc_server($dispMap,0);
        return $this;
    }
    /**
     * Singleton pattern
     *
     * @access public
     * @param  string mailer_name
     * @return PDF
     * 
     **/
    public static function instance() 
    {
       if ( is_null(self::$instance) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

}