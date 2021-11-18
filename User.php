<?php
    class User
    {
        private $id;
        private $userName;
        private $passWord;

        public    function __construct($id, $userName)
        {
            $this->id = $id;
            $this->userName = $userName;
            $this->password = "initial";
        }

        public    function getId()
        {
            return $this->id;
        }

        public  function getUserName()
        {
            return $this->UserName;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function setPassword($password)
        {
            $this->password = $password;
        }
    }
?>