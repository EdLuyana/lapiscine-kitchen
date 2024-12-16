<?php

namespace App\service;

use PHPUnit\Framework\TestCase;

class UniqueFilenameGeneratorTest extends TestCase
{

    public function testGenerateUniqueFilename()
    {
        $uniqueFileNameGenerator = new UniqueFilenameGenerator();
        $uniqueFilename = $uniqueFileNameGenerator->generateUniqueFilename('hello','jpeg');

        $this->assertStringContainsString('jpeg', $uniqueFilename);

    }

}