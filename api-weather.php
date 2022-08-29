<?php
require_once('database.php');

/**
 * Class for using AccuWeather service 
 * https://developer.accuweather.com/
 */
class ApiWeather
{
    private const API_URL = "http://dataservice.accuweather.com/";

    private static $API_KEY;

    public function __construct(string $apiKey)
    {
        self::$API_KEY = $apiKey;
    }

    /**
     *  This function is used to get information for the Hourly Forecasts from an AccuWeather API 
     */
    public function getHourlyForecast($city): ?string
    {
        $url = self::API_URL . "forecasts/v1/hourly/12hour/$city?apikey=" . self::$API_KEY . "&language=it-it&details=true&metric=true";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = (object)array(
            'response'  =>  curl_exec($curl),
            'status'    =>  curl_getinfo($curl, CURLINFO_RESPONSE_CODE),
            'info'      =>  (object)curl_getinfo($curl),
            'errors'    =>  curl_error($curl)
        );
        $this->logRequest("forecast-hourly-12hour", $res);
        curl_close($curl);
        return $res->response;
    }

    /**
     *  This function is used to get information for the Daily Forecasts from an AccuWeather API 
     */
    public function getDailyForecasts($city): ?string
    {
        $url = self::API_URL . "forecasts/v1/daily/5day/$city?apikey=" . self::$API_KEY . "&language=it-it&details=true&metric=true";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = (object)array(
            'response'  =>  curl_exec($curl),
            'status'    =>  curl_getinfo($curl, CURLINFO_RESPONSE_CODE),
            'info'      =>  (object)curl_getinfo($curl),
            'errors'    =>  curl_error($curl)
        );
        $this->logRequest("forecast-daily-5day", $res);
        curl_close($curl);
        return $res->response;
    }

    /**
     *  This function is used to get information for Current Conditions from an AccuWeather API 
     */
    public function getCurrentConditions($city)
    {
        $url = self::API_URL . "currentconditions/v1/$city?apikey=" . self::$API_KEY . "&language=it-it&details=true";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = (object)array(
            'response'  =>  curl_exec($curl),
            'status'    =>  curl_getinfo($curl, CURLINFO_RESPONSE_CODE),
            'info'      =>  (object)curl_getinfo($curl),
            'errors'    =>  curl_error($curl)
        );

        $this->logRequest("currentConditions", $res);
        curl_close($curl);
        return $res->response;
    }

    /**
     *  This function is used to register action request in a txt file
     */
    private function logRequest(string $action, object $res)
    {
        $filename = (new DateTime())->format("Y-m-d") . ".log";
        $dir = "logs";
        $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
        // $db = new Database("dev_weather", "sticazzi", "dev_weather", "request_log");
        // $dataInsert = ["action" => $action, "created_at" => new DateTime(), "response" => $res->response, "status" => $res->status, "info" => json_encode($res->info), "errors" => $res->errors];
        // $db->insert($dataInsert);

        $date = (new DateTime())->format(DateTimeInterface::W3C);
        $status = $res->status;
        $info = json_encode($res->info);
        $errors =  $res->errors;
        $response = "KO";
        if ($status == 200) {
            $response = "OK";
        }
        $dataInsert = "$date;$action;$response;$status;$errors;$info\n";
        if (!\file_exists($dir)) {
            \mkdir($dir);
        }
        $handle = fopen($fullPath, "ab");
        fwrite($handle, $dataInsert);
        fclose($handle);
    }
}
