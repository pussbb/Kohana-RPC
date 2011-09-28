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
                          ),
                          "qhda.bookcheck" => array(
                            "function" => array($this,'bookcheck'),
                            "signature" => array(
                              array( $this->_rpc->xmlrpcInt, $this->_rpc->xmlrpcStruct)
                            )
                          ),
                          "qhda.catagories" => array(
                            "function" => array($this,'categories'),
                            "signature" => array(
                              array( $this->_rpc->xmlrpcInt, $this->_rpc->xmlrpcArray)
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
    public function categories($m)
    {
        $params = php_xmlrpc_decode($m);
        $book_id = Arr::path($params[0],'*.bookid');
        try
        {
        $query = DB::delete('bookcat')->where('book_id','=',$book_id[0])->execute();
        }
        catch (Database_Exception $e)
        {
             return new xmlrpcresp(0, 7802, $e->getMessage());
        }
        $insert = DB::insert('bookcat', array('name', 'cat_id','cat_parent','book_id',));
        foreach($params[0] as $cat)
        {
            $insert->values(
                array(
                    Arr::get($cat,'catname',''),
                    (int)Arr::get($cat,'id',0),
                    (int)Arr::get($cat,'parent',0),
                    (int)Arr::get($cat,'bookid',0),
                )
            );
        }
        try
        {
            $insert->execute();
        }
        catch (Database_Exception $e)
        {
             return new xmlrpcresp(0, 7802, $e->getMessage());
        }
        return  new xmlrpcval(1, 'int');
    }
    public function bookcheck($m)
    {
       $params = php_xmlrpc_decode($m);
       $data = $params[0];

       if(empty($data['userName']) || empty($data['apiKey']))
           return new xmlrpcresp(0, 301,__('Username or apikey are empty'));

       $member = ORM::factory('user')->where('username','=',$data['userName'])
                                       ->and_having('apikey','=',$data['apiKey'])
                                         ->find();

       if($member->has('roles',  ORM::factory('role', array('name' => 'login'))) == FALSE)
           return new xmlrpcresp(0, 401,__('You are not allowed to add new books'));

       $book = DB::select()->from('books')->where('name','=',$data['bookName'])
                                            ->join('book_access')
                                              ->on('book_access.book_id','=','books.id')
                                               ->execute();

        if(count($book) == 0)
             return $this->_addBook($data,$member->id);

       if(!in_array($member->id,Arr::pluck($book,'userid')))
                return new xmlrpcresp(0, 401,__('You are not allowed to change somthing in this books.'));

       $book_id = Arr::pluck($book,'id');
       return new xmlrpcresp(new xmlrpcval( $book_id[0],'int'));

    }

    private function _addBook($data,$user_id)
    {
         try
         {
             $image_file = 'public/' . $data['bookImage'];
             if(strpos($image_file,'.png') == FALSE)
                $image_file = preg_replace('/\..+$/', '.' . 'png',$image_file);
             list($insert_id, $total_rows) = DB::insert('books', array('name', 'description','image'))
                     ->values(array($data['bookName'], $data['bookDescription'],$image_file))
                     ->execute();

             DB::insert('book_access', array('book_id', 'userid'))
                     ->values(array($insert_id, $user_id))
                     ->execute();
             if (!empty($data['image'])) {
                 $data_img = base64_decode($data['image']);
                 $im = imagecreatefromstring($data_img);
                 imagesavealpha($im, TRUE);
                 imagepng($im, DOCROOT . $image_file, 5, PNG_ALL_FILTERS);
                 imagedestroy($im);
             }
             return new xmlrpcresp(new xmlrpcval($insert_id, 'int'));
         }
         catch (Database_Exception $e)
         {
             return new xmlrpcresp(0, 7802, $e->getMessage());
         }
    }
}