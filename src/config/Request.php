<?php
include_once 'IRequest.php';

class Request implements IRequest
{
    function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    public function getBody()
    {
        if ($this->requestMethod === "GET") {
            if (isset($_SERVER['QUERY_STRING'])) {
                $data = array();
                parse_str($_SERVER['QUERY_STRING'], $data);
                header('Content-Type: application/json');
                return $data;
            }
            return;
        }


        if ($this->requestMethod == "POST") {
            $data = json_decode(file_get_contents('php://input'), true);
            header('Content-Type: application/json');
            // $body = array();
            // foreach ($_POST as $key => $value) {
            //     $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            // }

            return $data;
        }
    }
}
