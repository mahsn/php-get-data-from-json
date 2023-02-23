<?php

class StudyPortal {

    public $totalScore = 0;
    public $newArr = [];
    const MAX_BACHELOR_DURATION = 84;
    const MIN_BACHELOR_DURATION = 12;

    const MAX_MASTER_DURATION = 48;
    const MIN_MASTER_DURATION = 8;

    public  $maxYearTuitionValues = [
        'EUR' => 65000,
        'USD' => 83800,
        'GBP' => 90250
    ];
    public  $maxSemesterTuitionValues = [
        'EUR' => 15000,
        'USD' => 30000,
        'GBP' => 30000
    ];

    public  $maxCreditsTuitionValues = [
        'EUR' => 2200,
        'USD' => 2300,
        'GBP' => 1700
    ];
    public $startDatesArr;
    public $applicationDeadlinesArr;
    public $descriptionsArr;
    public $durationsArr;
    public $durationValuesArr;
    public $levelsArr;
    public $linksArr;
    public $tuitionsArr;
    public $tuitionValuesArr;
    public $lastUpdatedsArr;

    
    public function __construct($startDatesArr,
    $applicationDeadlinesArr,
    $descriptionsArr,
    $durationsArr,
    $durationValuesArr,
    $levelsArr,
    $linksArr,
    $tuitionsArr,
    $tuitionValuesArr,
    $lastUpdatedsArr) {
        $this->startDatesArr = $startDatesArr;
        $this->applicationDeadlinesArr = $applicationDeadlinesArr;
        $this->descriptionsArr = $descriptionsArr;
        $this->durationsArr = $durationsArr;
        $this->durationValuesArr = $durationValuesArr;
        $this->levelsArr = $levelsArr;
        $this->linksArr = $linksArr;
        $this->tuitionsArr = $tuitionsArr;
        $this->tuitionValuesArr = $tuitionValuesArr;
        $this->lastUpdatedsArr = $lastUpdatedsArr;
    }


    public function gatherAllData() {
        $arr = $this->compareStarDateApplicationDeadlines();
        $arr = $this->isStartDatesInFuture();
        $arr = $this->descriptionsLength();
        $arr = $this->durationRange();
        $arr = $this->websiteLink();
        $arr = $this->tuitionRange();
        $arr = $this->LastUpdated();
        $studyID1Total = array_sum(array_map(function($curr) {
            if($curr['StudyId'] == 1) return  $curr['score'];
        },$arr));
        $studyID2Total =  array_sum(array_map(function($curr) {
            if($curr['StudyId'] == 2) return  $curr['score'];
        },$arr));
        $studyID3Total = array_sum(array_map(function($curr) {
            if($curr['StudyId'] == 3) return  $curr['score'];
        },$arr));
        $newArray = [
                [
                    "studyId"=> 1,
                    "score"=> $studyID1Total
                ],
                [
                    "studyId"=> 2,
                    "score"=> $studyID2Total
                ],
                [
                    "studyId"=> 3,
                    "score"=> $studyID3Total
                ]
            ];
        return json_encode($newArray) ;
    }
   
    function compareStarDateApplicationDeadlines() {
        foreach($this->startDatesArr as $key => $value) {
            $days = ((strtotime($value) - strtotime($this->applicationDeadlinesArr[$key])) / 86400); 
            if (($days / 30) > 5) {
                $json = ["StudyId" => $key, "score" => 20];
                array_push($this->newArr, $json);
            }
           
        }
        return $this->newArr;
    }

    function isStartDatesInFuture() {
        foreach($this->startDatesArr as $key => $value) {
            $isInFuture = (date($value) > date('d-m-y')); 
            if ($isInFuture) {
                $json = ["StudyId" => $key, "score" => 10];
                array_push($this->newArr, $json);
            }
        }
        return $this->newArr;
    }

    function descriptionsLength() {
        
        foreach($this->descriptionsArr as $key => $value) {
            if (strlen($value) > 5) {
                $json = ["StudyId" => $key, "score" => 15];
                array_push($this->newArr, $json);
            }
        }

        return $this->newArr;
    }  
    
    function durationRange() {
        
        foreach ($this->durationValuesArr as $key => $value) {
            if ($this->levelsArr[$key] === 'bachelor'
            &&($value <= self::MAX_BACHELOR_DURATION && $value >= self::MIN_BACHELOR_DURATION)) {
                $json = ["StudyId" => $key, "score" => 20];
                array_push($this->newArr, $json);
                
            }
            
            if ($this->levelsArr[$key] === 'master'
            && ($value <= self::MAX_MASTER_DURATION && $value >= self::MIN_MASTER_DURATION)) {
                $json = ["StudyId" => $key, "score" => 20];
                array_push($this->newArr, $json);
                
            }
        }

        return $this->newArr;
    } 


    function websiteLink() {
        
        foreach($this->linksArr as $key => $value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                $json = ["StudyId" => $key, "score" => 10];
                array_push($this->newArr, $json);
            }
        }

        return $this->newArr;
    } 

    function tuitionRange() {
        
        foreach($this->tuitionValuesArr as $key => $values) {
            if ($this->tuitionsArr[$key] === 'yearly') {
                if (($values['EUR'] <= $this->maxYearTuitionValues['EUR']) 
                    &&($values['USD'] <= $this->maxYearTuitionValues['USD'])
                    && ($values['GBP'] <= $this->maxYearTuitionValues['GBP'])) {
                     $json = ["StudyId" => $key, "score" => 10];
                     array_push($this->newArr, $json);
                }
                   
               
                
            }
            if ($this->tuitionsArr[$key] === 'semester') {
                if (($values['EUR'] <= $this->maxYearTuitionValues['EUR']) 
                    &&($values['USD'] <= $this->maxYearTuitionValues['USD'])
                    && ($values['GBP'] <= $this->maxYearTuitionValues['GBP'])) {
                    $json = ["StudyId" => $key, "score" => 10];
                    array_push($this->newArr, $json);
                }
            }
                
        }

        return $this->newArr;
    }

    function LastUpdated() {
        foreach($this->lastUpdatedsArr as  $key => $value) {
                $json = ["StudyId" => $key, "score" => 10];
                array_push($this->newArr, $json);
        }
        return $this->newArr;
    }



}
