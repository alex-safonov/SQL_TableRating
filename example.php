<?

define('STOP_STATISTICS', true);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('SITE_ID', 's1');

$servername = "";
$database = "";
$username = "";
$password = "";
// Устанавливаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
// Проверяем соединение
if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
    exit();
} else {
    printf("Current character set: %s\n", $conn->character_set_name());
}
/**удаление таблицы**/
$sql = "DROP TABLE RatingVol";
if (mysqli_query($conn, $sql)) {
      echo "New record created successfully";
} else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
/**создание таблицы**/
$sql = "CREATE TABLE RatingVol(
ID_Feedback varchar(255),
ID_Volunteer varchar(255),
Name_Volunteer varchar(255),
ID_Task varchar(255),
Date varchar(255),
Rating_Quality varchar(255),
Rating_Punctuality varchar(255),
Rating_Proactivity varchar(255),
Rating_Operativeness varchar(255),
Rating_Politeness varchar(255)
);";

if (mysqli_query($conn, $sql)) {
      echo "New table RatingVol created successfully";
} else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
/**очистка таблиц**/
$sql = "TRUNCATE TABLE RatingVol";
if (mysqli_query($conn, $sql)) {
      echo "New record created successfully";
} else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule('iblock');

use Bureau\Site\Constant;
use Bureau\Site\Tools;
use Bureau\Site\Entities\FundUser;
use Bureau\Site\Entities\Volunteer;

// подключаем пространство имен класса HighloadBlockTable и даём ему псевдоним HLBT для удобной работы
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
// id highload-инфоблока
const MY_HL_BLOCK_ID = х;
//подключаем модуль highloadblock
CModule::IncludeModule('highloadblock');
//Напишем функцию получения экземпляра класса:
function GetEntityDataClass($HlBlockId) 
{
    if (empty($HlBlockId) || $HlBlockId < 1)
    {
        return false;
    }
    $hlblock = HLBT::getById($HlBlockId)->fetch();
    $entity = HLBT::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();
    return $entity_data_class;
}

$entity_data_class = GetEntityDataClass(MY_HL_BLOCK_ID);
$rsData = $entity_data_class::getList(array(
   'select' => array('*')
));
while($el = $rsData->fetch()){

	if ($el['UF_VOLUNTEER_ID']) {

	echo "<pre>";

		echo "ID отзыва - ".$el['ID'].",<br>";
		echo "ID Волонтёра - ".$el['UF_VOLUNTEER_ID'].",<br>";
		$res = CIBlockElement::GetByID($el['UF_VOLUNTEER_ID']);
		if($vol_res = $res->GetNext()) {
			echo "Имя Волонтёра - ".$vol_res['NAME']."<br>";
			};
		// echo "ID Фонда - ".$el['UF_FUND_ID'].",<br>";
		echo "ID задачи - ".$el['UF_TASK_ID'].",<br>";
		echo "Дата отзыва - ".$el['UF_DATE'].",<br>";
		echo "Оценки компетенций: <br>";

		$arr = explode(",", $el['UF_RESULT']);

		$Rating_Quality_temp = preg_replace('/[^0-9]/', '', $arr[0]);
		$Rating_Quality = substr($Rating_Quality_temp, -1);
		echo "Rating_Quality - $Rating_Quality <br>";
		

		$Rating_Punctuality_temp = preg_replace('/[^0-9]/', '', $arr[1]);
		$Rating_Punctuality = substr($Rating_Punctuality_temp, -1);	
		echo "Rating_Punctuality - $Rating_Punctuality <br>";
		

		$Rating_Proactivity_temp = preg_replace('/[^0-9]/', '', $arr[2]);
		$Rating_Proactivity = substr($Rating_Proactivity_temp, -1);	
		echo "Rating_Proactivity - $Rating_Proactivity <br>";
		

		$Rating_Operativeness_temp = preg_replace('/[^0-9]/', '', $arr[3]);
		$Rating_Operativeness = substr($Rating_Operativeness_temp, -1);
		echo "Rating_Operativeness - $Rating_Operativeness <br>";
		

		$Rating_Politeness_temp = preg_replace('/[^0-9]/', '', $arr[4]);
		$Rating_Politeness = substr($Rating_Politeness_temp, -1);
		echo "Rating_Politeness - $Rating_Politeness <br>";

	echo "</pre>";
	}
    $statFeedbackID = "'".$el['ID']."'";
    $statVolID = "'".$el['UF_VOLUNTEER_ID']."'";
    $statVolName = "'".$vol_res['NAME']."'";
	$statTaskID = "'".$el['UF_TASK_ID']."'"; 
	$statDate = "'".date("Y-m-d H:i:s", strtotime($el['UF_DATE']))."'";
	$statRating_Quality = $Rating_Quality;
	$statRating_Punctuality = $Rating_Punctuality;
	$statRating_Proactivity = $Rating_Proactivity;
	$statRating_Operativeness = $Rating_Operativeness;
	$statRating_Politeness = $Rating_Politeness;


// Передаём данные:

// 	$sql = "INSERT INTO `Statistics`(`ID`, `ID_Task`, `ID_Volunteer`, `Status`, `ID_Category`, `Date`) VALUES ($statisticsID,$statisticsID_Task,$statisticsID_Volunteer,$statisticsStatus,$statisticsID_Category,$statisticsDate)";

$sql = "INSERT INTO `RatingVol`(`ID_Feedback`, `ID_Volunteer`, `Name_Volunteer`, `ID_Task`, `Date`, `Rating_Quality`, `Rating_Punctuality`, `Rating_Proactivity`, `Rating_Operativeness`, `Rating_Politeness`) VALUES ($statFeedbackID, $statVolID, $statVolName, $statTaskID, $statDate, $statRating_Quality, $statRating_Punctuality, $statRating_Proactivity, $statRating_Operativeness, $statRating_Politeness)";


	if (mysqli_query($conn, $sql)) {
               	echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
