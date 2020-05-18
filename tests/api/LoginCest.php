<?php

namespace App\Test\api;

use ApiTester;
use Codeception\Util\HttpCode;

/**
 * Class LoginCest.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class LoginCest
{
    private $token;
    private $tokenParams;

    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
        $this->tokenParams = [
            'grant_type' => 'password',
            'client_id' => '1',
            'client_secret' => 'Administration',
            'username' => 'admin',
            'password' => '123',
            'redirect_uri' => 'TESTE',
        ];
    }

    // tests
    public function getTokenAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('authentication/authentication/token', $this->tokenParams);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Bearer');
        $I->seeResponseContains('expires_in');
        $I->seeResponseContains('access_token');

        //get the token for use after
        $response = json_decode($I->grabResponse());
        $this->token = $response->access_token;
    }

    //test
    public function loginApi(ApiTester $I)
    {
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('authentication/authentication/login');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"result":"success","message":"Login Successful","redirect_url":""}');
    }
}
