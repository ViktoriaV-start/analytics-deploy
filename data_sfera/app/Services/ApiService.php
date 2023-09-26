<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

const KEY = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
const INITIAL_PAGE = 1;
const LIMIT = 100;

class ApiService
{
    /**
     * @var array *
     * Params for API
     */
    private array $params =  [];

    /**
     * Set params for request.
     */
    public function setParams(array $params)
    {
        try {
            $this->params['dateFrom'] = $params['dateFrom'];
            $this->params['dateTo'] = $params['dateTo'];
            $this->params['page'] = $params['page'] ?? INITIAL_PAGE;
            $this->params['limit'] = $params['limit'] ?? LIMIT;
            $this->params['key'] = $params['key'] ?? KEY;
        } catch(\Exception $exception) {
            dump('Error: ' . $exception->getMessage());
        }
    }


    /**
     * Get data.
     */
    public function getData()
    {
        foreach (config('global.request_targets') as $value) {

            // TRUNCATE tables in DB
                DB::table($value)->truncate();

            $response = $this->getDataSection($value);

            if ($response['status'] === 'ok') {
               echo "Success in section $value <br>";
               echo "<script>console.log('Success in section $value')</script>";
            } else {

                echo $response['message'];
                echo "<script>console.log('{$response['message']}')</script>";
            }
        }
    }

    /**
     * Get data on the section.
     */
    public function getDataSection(string $section)
    {
        $data = [];
        $dbService = new DbService();

        try {
            $response = $this->getOnePageData($section, INITIAL_PAGE);

            $response = $this->checkResponseStatus($response, $section, INITIAL_PAGE);

            $responseJson = json_decode($response->body());

            $totalPages = $responseJson->meta->last_page;

            $data = $responseJson->data;

            if($data) {
                $dbService->saveToDb($data, $section);
            }

            for ($i = 2; $i <= $totalPages; $i++) {
                $response = $this->getOnePageData($section, $i);

                $response = $this->checkResponseStatus($response, $section, $i);

                $nextPage = json_decode($response->body())->data;
                if($nextPage) {
                    $dbService->saveToDb($nextPage, $section);
                }
            }

            return [
                'status' => 'ok'
            ];

        } catch(\Exception $exception) {
            return [
                'status' => 'error',
                'message' => "Error in section $section <br>"
            ];
        }
    }

    /**
     * Get one page data.
     */
    private function getOnePageData(string $section, int $page) {

        return Http::get("89.108.115.241:6969/api/$section", [
            'dateFrom' => $this->params['dateFrom'],
            'dateTo' => $this->params['dateTo'],
            'page' => $page,
            'limit' => $this->params['limit'],
            'key' => $this->params['key']
        ]);
    }

    private function checkResponseStatus(object $response, string $section, int $page): object
    {
        $checkedResponse = $response;
        if($response->status() === config('global.too_many_requests')) {
            sleep(5);
            echo 'Waiting ';
            $checkedResponse = $this->getOnePageData($section, $page);
        };

        return $checkedResponse;
    }
}
