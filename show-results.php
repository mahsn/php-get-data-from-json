<?php

require_once 'StudyPortal.php';

$studiesJson = file_get_contents('data/studies.json');
$decodes = json_decode($studiesJson, true);


$StartDatesArr = array_column($decodes,'StartDate','StudyId');
$ApplicationDeadlinesArr = array_column($decodes,'ApplicationDeadline','StudyId'); 
$DescriptionsArr = array_column($decodes,'Description','StudyId');
$DurationsArr = array_column($decodes, 'Duration', 'StudyId');  
$DurationValuesArr = array_column($decodes, 'DurationValue','StudyId');
$LevelsArr = array_column($decodes, 'Level', 'StudyId');  
$LinksArr = array_column($decodes, 'Link','StudyId');
$LastUpdatedsArr = array_column($decodes, 'LastUpdated', 'StudyId');  
$TuitionsArr = array_column($decodes, 'Tuition','StudyId');
$TuitionValuesArr = array_column($decodes,'TuitionValue', 'StudyId');  


$result = new StudyPortal($StartDatesArr,$ApplicationDeadlinesArr, 
$DescriptionsArr, $DurationsArr,$DurationValuesArr,
$LevelsArr, $LinksArr,$LastUpdatedsArr, $TuitionsArr,$TuitionValuesArr);

echo '<pre>';
print_r($result->gatherAllData());
echo '</pre>';