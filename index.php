<?php
$api_key = "";
$userdb = "";
$passw = "";
$city = 190390;
require_once("config.php"); // It contains the API's key, the username of the database and the password of the database

require_once("functions.php");
require_once("database.php");
require_once("api-weather.php");

$todayRepo = new Database($userdb, $passw, "dev_weather", "today");
$twelveHourRepo = new Database($userdb, $passw, "dev_weather", "twelve_hour");
$fiveDayRepo = new Database($userdb, $passw, "dev_weather", "five_day");
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
