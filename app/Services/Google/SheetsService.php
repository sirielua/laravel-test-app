<?php

namespace App\Services\Google;

use App\Components\Google\SheetsApi;
use App\Models\Participant;

class SheetsService
{
    private $spreadsheetId;
    private $api;

    public function __construct($spreadsheetId, SheetsApi $api)
    {
        $this->spreadsheetId = $spreadsheetId;
        $this->api = $api;
    }

    public function updateParticipant($id)
    {
        $participant = Participant::confirmed()->findOrFail($id);
        $data = [$this->formatParticipantAsRow($participant)];

        $index = $this->api->search($this->spreadsheetId, $participant->id);

        if ($index === false) {
            $response = $this->api->add($this->spreadsheetId, $data);
        } else {
            $response = $this->api->update($this->spreadsheetId, $data, $index);
        }
    }

    private function formatParticipantAsRow(Participant $participant)
    {
        return [
            $participant->id,
            $participant->phone,
            $participant->first_name . ' ' . $participant->last_name,
            $participant->referral_quantity,
        ];
    }

    public function exportParticipants()
    {
        $this->api->clearSheet($this->spreadsheetId);

        Participant::confirmed()->chunk(200, function ($participants) {
            $data = [];
            foreach ($participants as $participant) {
                $data[] = $this->formatParticipantAsRow($participant);
            }

            $this->api->add($this->spreadsheetId, $data);
        });
    }
}
