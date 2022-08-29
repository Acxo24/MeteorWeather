<?php
$userdb = ""; //This is the variable for the user's database
$passw = ""; //This is the variable for the database's password 
$dbname = ""; //This is the variable for database's name
$api_key = ""; //This is the variable for the AccuWeather api's key
$city = 190390; //This is the code of the city for the api

require_once("functions.php");
require_once("database.php");
require_once("api-weather.php");

$todayRepo = new Database($userdb, $passw, $dbname, "today");
$twelveHourRepo = new Database($userdb, $passw, $dbname, "twelve_hour");
$fiveDayRepo = new Database($userdb, $passw, $dbname, "five_day");
$apiWeather = new ApiWeather($api_key);

// setup the content of response to json type
header("Content-Type: application/json; charset=UTF-8");

//a -> action
/**
 *  This switch is used to identify the type of the request and use the appropriate one
 */
switch ($_GET['a']) {
    case "getCurrentConditions":
        echo getWeatherDataFromDbOrService($todayRepo,$apiWeather,$_GET['a'], $city);
        break;
    case "getHourlyForecast":
        echo getWeatherDataFromDbOrService($twelveHourRepo,$apiWeather,$_GET['a'], $city);

        break;
    case "getDailyForecasts":
        echo getWeatherDataFromDbOrService($fiveDayRepo,$apiWeather,$_GET['a'], $city);
        break;
    default:
        echo json_encode("Errore: azione non supportata!");
        break;
}
