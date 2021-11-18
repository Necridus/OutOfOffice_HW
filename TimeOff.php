<?php
    class TimeOff
    {
        private $id;
        private $userId;
        private $startDate;
        private $endDate;

        public function __construct($id, $userId, $startDate ,$endDate)
        {
            $this->id = $id;
            $this->userId = $userId;
            $this->startDate = $startDate;
            $this->endDate = $endDate;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getUserId()
        {
            return $this->UserId;
        }

        public function getStartDate()
        {
            return $this->startDate;
        }

        public function getEndDate()
        {
            return $this->endDate;
        }

        public function getDuration(){
            return date_diff($this->endDate,$this->startDate);
        }

       public function daysUntil(){
            return date_diff(new DateTime("now"), $this->startDate);
       }
    }
?>