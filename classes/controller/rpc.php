<?php defined('SYSPATH') or die('No direct script access.');

class Controller_RPC extends Controller {
    protected $_rpc;
    public function action_index()
    {
        $this->_rpc = RPC::factory();
        $methods =  array(
                          "sum" => array(
                            "function" => array($this,'foobar'),
                            "signature" => array(
                              array( $this->_rpc->xmlrpcInt, $this->_rpc->xmlrpcInt, $this->_rpc->xmlrpcInt)  ,
                              array( $this->_rpc->xmlrpcString)
                            )
                          )
                        );
        $this->_rpc->xmlRPCServer($methods);

    }
    public function foobar($m)
    {
       $params = php_xmlrpc_decode($m);

        $count = $params[0] + $params[1];
       return new xmlrpcresp(new xmlrpcval($count, "int"));
    }
}