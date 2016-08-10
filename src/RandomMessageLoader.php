<?php
namespace HouseOfDross\Skippy;

use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class RandomMessageLoader
{
    private $yamlFilename;

    public function __construct(Filesystem $fileSystem, string $yamlFilename)
    {
        $this->fileSystem = $fileSystem;
        $this->yamlFilename = $yamlFilename;
    }

    public function getRandomMessage() :string
    {
        $allMessages = Yaml::parse($this->getFileContents());
        $randomId = array_rand($allMessages);
        $randomMessage = $allMessages[$randomId];
        return ($randomMessage);
    }

    private function getFileContents() :string
    {
        return $this->fileSystem->read($this->yamlFilename);
    }

}
