<?php

namespace Losofacebook\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Keboola\Csv\CsvFile;
use Losofacebook\Service\ImageService;
use Losofacebook\Image;
use Doctrine\DBAL\Connection;

use DateTime;

class ReCreateImagesCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('dev:recreate-images')
            ->setDescription('ReCreates images for users');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Will parse images.");

        $is = $this->getImageService();
        $images = $this->getDb()->fetchAll("SELECT * FROM image WHERE type = 1");
        
        foreach ($images as $image) {
            $is->createVersions($image['id']);
            $output->writeln("Recreating image id: {$image['id']}");
        }
        
    }

    /**
     * @return ImageService
     */
    public function getImageService()
    {
        return $this->getSilexApplication()['imageService'];
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return $this->getSilexApplication()['db'];
    }
}
