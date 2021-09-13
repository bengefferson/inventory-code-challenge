<?php
declare(strict_types=1);
namespace Inventory;

// Class that handles configuration values for the system
class Config
{
    //Settings for product purchse
    const MAX_WAIT_TIME = 2;
    const MIN_STOCK_BEFORE_PURCHASE = 10;
    const STOCK_REPLENISH_VAL = 20;


    //Input file settings
    const INPUT_FILE_PATH = '/var/www/input';
    const FILE_CHUNK_SIZE = 1024;

    //Products Table initialization/seeder
    const PRODUCTS_TABLE_INIT = [
        1 =>[
            'product' => 'Brownie',
            'sold' => 0,
            'received' => 0,
            'pending' => 0,
            'stock' => 20,
            'pending_wait' => 0,
        ],
        2 =>[
            'product' => 'Lamington',
            'sold' => 0,
            'received' => 0,
            'pending' => 0,
            'stock' => 20,
            'pending_wait' => 0,
        ],
        3 =>[
            'product' => 'Blueberry Muffin',
            'sold' => 0,
            'received' => 0,
            'pending' => 0,
            'stock' => 20,
            'pending_wait' => 0,
        ],
        4 =>[
            'product' => 'Croissant',
            'sold' => 0,
            'received' => 0,
            'pending' => 0,
            'stock' => 20,
            'pending_wait' => 0,
        ],
        5 =>[
            'product' => 'Chocolate Cake',
            'sold' => 0,
            'received' => 0,
            'pending' => 0,
            'stock' => 20,
            'pending_wait' => 0,
            
        ],
    ];

}