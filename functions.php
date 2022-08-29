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
        logRequest($action,new ArrayObject(["status"=>901,"info"=>"","response"=>"SELECT","errors"=>""], ArrayObject::ARRAY_AS_PROPS));
        return json_encode($current);
    } else {
        // else get data from weather web service then storing into database
        if ($jsonData = $api->{$action}($city)) {
            $repository->updateAll(["status" => 0]);
            $repository->insert(["json_data" => $jsonData, "status" => 1, "created_at" => $now]);
            logRequest($action,new ArrayObject(["status"=>902,"info"=>"","response"=>"INSERT","errors"=>""], ArrayObject::ARRAY_AS_PROPS));
        }
        // and get last active record and return
        $current = $repository->findBy(["status" => 1], "created_at DESC", 1);
        logRequest($action,new ArrayObject(["status"=>903,"info"=>"","response"=>"SELECT","errors"=>""], ArrayObject::ARRAY_AS_PROPS));
        return json_encode($current);
    }
    logRequest($action,new ArrayObject(["status"=>400,"info"=>"","response"=>"ERROR","errors"=>"ERROR"], ArrayObject::ARRAY_AS_PROPS));
    return json_encode($jsonData);
}

/**
 * This function is used to check if exist a directory and a file for the logs, 
 * if they don't exist it will create them and write the logs in the txt file
*/
function logRequest(string $action, object $res)
{
   
    $filename = (new DateTime())->format("Y-m-d") . ".log";
    $dir = "logs";
    $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;

    $date = (new DateTime())->format(DateTimeInterface::W3C);
    $status = $res->status;
    $info = json_encode($res->info);
    $errors =  $res->errors;
    $response = "KO";
    if ($status == 200) {
        $response = "OK";
    } elseif($status >900) {
        $response = $res->response;
    }
    $dataInsert = "$date;$action;$response;$status;$errors;$info\n";
    if (!\file_exists($dir)) {
        \mkdir($dir);
    }
    $handle = fopen($fullPath, "ab");
    fwrite($handle, $dataInsert);
    fclose($handle);
}
