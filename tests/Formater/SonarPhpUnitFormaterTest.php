<?php

namespace App\Tests\Formater;

use App\Formater\SonarPhpUnitFormater;
use PHPUnit\Framework\TestCase;

/**
 * Class SonarPhpUnitFormaterTest
 */
class SonarPhpUnitFormaterTest extends TestCase
{
    /* @var SonarPhpUnitFormater */
    private $formater;

    /**
     * Initialisation du helper
     */
    public function setUp(): void
    {
        $this->formater = new SonarPhpUnitFormater('data/report.xml');
    }

    public function testFormat()
    {
        $this->assertTrue(true); // todo
    }

    public function testFormatWithRecursiveIterator()
    {
        $this->assertTrue(true); // todo
    }
}
