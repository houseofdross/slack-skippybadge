<?php
namespace HouseOfDross\Skippy\Test;

use Concise\Core\TestCase;
use HouseOfDross\Skippy\RandomMessageLoader;

class RandomMessageLoaderTest extends TestCase
{
    public function testGetMessageReadsFromTheYamlFile()
    {
        $filesystemMock = $this->mock('\League\Flysystem\Filesystem')
            ->expect('read')->with('dummyYamlFile.yml')->once()->andReturn("- message 1\n- message 2")->get();

        $messageLoader = new RandomMessageLoader($filesystemMock, 'dummyYamlFile.yml');
        $randomMessage = $messageLoader->getRandomMessage();

        $this->verify(in_array($randomMessage, ['message 1', 'message 2']))->equals(true);
    }
}
