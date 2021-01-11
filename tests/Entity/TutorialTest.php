<?php

namespace App\Tests\Entity;

use App\Entity\Tutorial;
use PHPUnit\Framework\TestCase;

class TutorialTest extends TestCase
{

    public function testTutoTitle() {
        $tutorial = new Tutorial();
        $tutorial->setTitle('blabla');

        $result = $tutorial->getTitle();

        $this->assertEquals('blabla', $result);
    }


}

