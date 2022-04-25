<?php

namespace Dolphin\SpecificProductIndexing\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Customcommand extends Command
{
    const SKU = 'sku';
    const ID = 'id';
    protected $indexerRegistry;
    protected $productCollection;

    public function __construct(
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\Catalog\Model\Product $productCollection
    ) {
        $this->indexerRegistry = $indexerRegistry;
        $this->productCollection = $productCollection;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('indexer:reindex:specific');
        $this->setDescription('Reindex Specific Product.');
        $this->addOption(
            self::SKU,
            null,
            InputOption::VALUE_REQUIRED,
            'Reindex specific product by SKU'
        );
        $this->addOption(
            self::ID,
            null,
            InputOption::VALUE_REQUIRED,
            'Reindex specific product by ID'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $indexList = [
                'catalog_category_product',
                'catalog_product_category',
                'catalog_product_attribute',
                'cataloginventory_stock',
                'inventory',
                'catalogsearch_fulltext',
                'catalog_product_price',
            ];
            if ($input->getOption(self::SKU) || $input->getOption(self::ID)) {
                foreach ($indexList as $index) {
                    $Indexer = $this->indexerRegistry->get($index);
                    if($input->getOption(self::SKU)){
                        $productIds = explode(',', $input->getOption(self::SKU));
                    }else{
                        $productIds = explode(',', $input->getOption(self::ID));
                    }
                    foreach ($productIds as $id) {
                        if($input->getOption(self::SKU)){
                            $id = $this->getProductIdBySku($id);
                        }
                        $Indexer->reindexList([$id]);
                    }
                    $output->writeln('<info>'.$Indexer->getTitle().' index has been rebuilt successfully</info>');
                }
            }else{
                $output->writeln('<error>Please add --id=1 or --sku=24-MB01,24-MB04</error>');
                $output->writeln('<comment>For Ex: php bin/magento indexer:reindex:specific --sku=24-MB01</comment>');

            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getProductIdBySku($sku) {
        return $productId = $this->productCollection->getIdBySku($sku);
    }
}