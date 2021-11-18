<?php
    include("User.php");
    class Admin extends User
    {

        public function __construct($id, $userName)
        {
            parent::__construct($id, $userName);
        }
        
        public function acceptRequest($request){
            $request->accept();
        }
        
        public function denyRequest($request){
            $request->deny();
        }
        //TODO
        public function createUser($userName){
            
        }
        //TODO
        public function removeUser($userId){
            
        }
    }
?>