<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * This function is used to dump a variable and die the code
 */
function dd($var = null)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}

/**
 * This function is used to get the weather's informations from the database and return them
 * if the information saved is outadated, get new information from weather service
 */
function getWeatherDataFromDbOrService(Database $repository, ApiWeather $api, string $action, int $city)
{
    $jsonData = [];
    $now = new DateTime();
    // check if current hourly request was stored in database then return
    if ($current = $repository->findBy(["status" => 1, "created_at" => $now], "created_at DESC", 1)) {
        return json_encode($current);
    } else {
        // else get data from weather web service then storing into database
        if ($jsonData = $api->{$action}($city)) {
            $repository->updateAll(["status" => 0]);
            $repository->insert(["json_data" => $jsonData, "status" => 1, "created_at" => $now]);            
        }
        // and get last active record and return
        $current = $repository->findBy(["status" => 1], "created_at DESC", 1);        
        return json_encode($current);
    }
    return json_encode($jsonData);
}
