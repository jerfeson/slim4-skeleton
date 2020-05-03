<?php

namespace App\Test\acceptance;

use AcceptanceTester;

/**
 * Class WelcomeCest.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class WelcomeCest
{
    /**
     * @param AcceptanceTester $I
     *
     * @return bool
     */
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to SLIM4!');

        return true;
    }
}
