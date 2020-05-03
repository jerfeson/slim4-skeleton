<?php

/**
 * Class FirstCest.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class FirstCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to SLIM!');
    }
}
