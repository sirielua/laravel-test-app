<?php

namespace Tests\Feature\Google;

use Tests\TestCase;

use Google\Client;
use App\Components\Google\SheetsApi;

class SheetsApiTest extends TestCase
{
    private static $api;
    private static $spreadsheetId;

    private const EXISTING_ID = 'test-id';
    private const NON_EXISTING_ID = 'fail-id';

    public function setUp(): void
    {
        parent::setUp();

        if (self::$api === null) {
            $credentials = base_path(env('GOOGLE_API_CREDENTIALS', 'google-credentials.json'));
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentials);

            $client = new Client();
            $client->useApplicationDefaultCredentials();
            $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
            $service = new \Google_Service_Sheets($client);

            self::$api = new SheetsApi($service);
            self::$spreadsheetId = env('GOOGLE_API_TEST_SPREADSHEET_ID');
            self::$api->clearSheet(self::$spreadsheetId);
        }
    }

    public function testAdd()
    {
        $values = [[self::EXISTING_ID, 'new']];

        $response = self::$api->add(self::$spreadsheetId, $values);

        $this->assertInstanceOf(\Google_Service_Sheets_AppendValuesResponse::class, $response);
        $this->assertEquals(1, $response->getUpdates()->getUpdatedRows());
    }

    public function testSearchNonExistant()
    {
        $result = self::$api->search(self::$spreadsheetId, self::NON_EXISTING_ID);

        $this->assertFalse($result);
        $this->assertTrue($result === false);
    }

    /**
     * @depends testAdd
     */
    public function testSearch()
    {
        $result = self::$api->search(self::$spreadsheetId, self::EXISTING_ID);

        $this->assertEquals(0, $result);
        $this->assertTrue($result === 0);
    }

    /**
     * @depends testAdd
     */
    public function testUpdate()
    {
        $values = [[self::EXISTING_ID, 'updated']];

        $response = self::$api->update(self::$spreadsheetId, $values, $index = 0);
        $this->assertInstanceOf(\Google_Service_Sheets_UpdateValuesResponse::class, $response);
        $this->assertEquals(1, $response->getUpdatedRows());
    }

    /**
     * @depends testUpdate
     */
    public function testRemove()
    {
        self::$api->add(self::$spreadsheetId, [[\uniqid(), \uniqid()]]);

        $index = self::$api->search(self::$spreadsheetId, self::EXISTING_ID);
        $response = self::$api->clear(self::$spreadsheetId, $index);

        $found = self::$api->search(self::$spreadsheetId, self::EXISTING_ID);

        $this->assertInstanceOf(\Google_Service_Sheets_ClearValuesResponse::class, $response);
        $this->assertFalse($found);
        $this->assertTrue($found === false);
    }

    /**
     * @depends testUpdate
     */
    public function testClear()
    {
        self::$api->clearSheet(self::$spreadsheetId);

        $result = self::$api->search(self::$spreadsheetId, self::EXISTING_ID);

        $this->assertFalse($result);
        $this->assertTrue($result === false);
    }

    /**
     * @depends testClear
     */
    public function testSearchEmptyList()
    {
        $found = self::$api->search(self::$spreadsheetId, self::EXISTING_ID);

        $this->assertFalse($found);
        $this->assertTrue($found === false);
    }

    /**
     * @depends testClear
     */
    public function testSucess()
    {
        $result = self::$api->search(self::$spreadsheetId, self::EXISTING_ID);

        $this->assertFalse($result);
        $this->assertTrue($result === false);
    }
}
