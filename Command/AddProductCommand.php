<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;


class AddProductCommand extends Command 
{
    protected static $defaultName = 'app:add-products';
    
    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;

        parent::__construct();
    }
    
    protected function configure()
    {

        $this->setDescription('Adds a product.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // csv
        $inputFile = $this->projectDir . '/public/products.csv';

        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        $rows = $decoder->decode(file_get_contents($inputFile), 'csv');

        $cache = new FilesystemAdapter($namespace = '', $defaultTime = 60, $directory = null);

        $cacheItem = $cache->getItem('array.products');

        $cacheItem->set($rows);

        $cache->save($cacheItem);  
        
        //dd($cache);
        //dd($rows);
        dd($cacheItem);

    }
}