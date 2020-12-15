<?php

namespace App\Components\Google;

class SheetsApi
{
    private $service;

    public function __construct(\Google_Service_Sheets $service)
    {
        $this->service = $service;
    }

    public function search($spreadsheetId, $value, $range = 'A:A')
    {
        $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);

        if ($response->getValues() !== null) {
            foreach ($response->getValues() as $key => $row) {
                if (isset($row[0]) && ($row[0] == $value)) {
                    return $key;
                }
            }
        }

        return false;
    }

    public function add($spreadsheetId, $values)
    {
        $range = 'A1';
        $body = $this->formatValues($values);
        $params = ['valueInputOption' => 'RAW'];

        return $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    }

    public function formatValues($values = [])
    {
        return new \Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
    }

    public function update($spreadsheetId, $values, $index)
    {
        $range = 'A' . ($index + 1);
        $body = $this->formatValues($values);
        $params = ['valueInputOption' => 'RAW'];

        return $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
    }

    public function clear($spreadsheetId, $index)
    {
        $range = 'A'.($index+1).':'.($index+1);

        return $this->service->spreadsheets_values->clear($spreadsheetId, $range, new \Google_Service_Sheets_ClearValuesRequest());
    }

    public function clearSheet($spreadsheetId)
    {
        $request = $this->getClearSheetRequest();

        return $this->service->spreadsheets->batchUpdate($spreadsheetId, $request);
    }

    private function getClearSheetRequest()
    {
        $requests = [
            new \Google_Service_Sheets_Request([
                'updateCells' => [
                    'range' => ['sheetId' => 0],
                    'fields' => 'userEnteredValue',
                ],

            ]),
        ];

        return new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => $requests,
        ]);
    }
}
