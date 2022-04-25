## Installation

If you want to install this extension using composer then make sure your composer version is **Composer 2.0**

```base
composer require mayankdolphin/magento2-module-specificproductindexing
```

```base
bin/magento module:enable Dolphin_SpecificProductIndexing
```

```base
bin/magento setup:upgrade
```

## How to use this Module? 

We've created a new command `indexer:reindex:specific` with 2 options `--id` and `--sku`.

You can use that commad like below examples.

**Reindex single product by ID/SKU**

```base
php bin/magento indexer:reindex:specific --id=1
```
```base
php bin/magento indexer:reindex:specific --sku=24-MB01
```

**Reindex multiple products by IDs/SKU**

```base
php bin/magento indexer:reindex:specific --id=1,2
```
```base
php bin/magento indexer:reindex:specific --sku=24-MB01,24-MB04
```

You need to add IDs/SKU by `,` seprate if you want to reindex multiple products.
