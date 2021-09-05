<?php

namespace App\Test\api\v1;

use ApiTester;
use Codeception\Util\HttpCode;

class AccessTokenCest
{
    private $clientCredentials;

    public function _before(ApiTester $I)
    {
        $this->clientCredentials = [
            'grant_type' => 'client_credentials',
            'client_id' => 'Mxv85bGRnZMpKtIfN82k5jEn3lYUPh6omYB7xVid',
            'client_secret' => 'GH3pN8AWZNEfoxa304LtReaFiLO90K2eFUPC7EXgjBxbbgFFxCBqANNihaOl',
        ];
    }

    // tests
    public function getTokenSuccessAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('auth/token', $this->clientCredentials);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Bearer');
        $I->seeResponseContains('expires_in');
        $I->seeResponseContains('access_token');
    }
}
