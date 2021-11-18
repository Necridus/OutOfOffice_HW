<?php
include("TimeOff.php");
    class TimeOffRequest extends TimeOff
    {
        private $isAccepted;
        public function __construct($id, $userId, $startDate ,$endDate)
        {
            parent::__construct($id, $userId, $startDate, $endDate);
        }

        public function getAccepted(){
            return $this->isAccepted;
        }
       
        public function accept(){
            $this->isAccepted = true;
        }

        public function deny(){
            $this->isAccepted = false;
        }
    }
?>