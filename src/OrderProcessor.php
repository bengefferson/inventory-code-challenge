<?php
declare(strict_types=1);
namespace Inventory;

use Inventory\Interfaces\OrderProcessorInterface;
use Inventory\Interfaces\OrderProductsInterface;
use Inventory\Interfaces\OutputSummaryInterface;
use Inventory\Config;
use Exception;

ini_set('include_path',Config::INPUT_FILE_PATH);

//Class to process orders and output a summary
class OrderProcessor implements OrderProcessorInterface
{
    //Property product id to get its stock level
    protected $orderProducts;
    protected $summary;

    public function __construct(OrderProductsInterface $orderProducts, OutputSummaryInterface $summary)
    {
        $this->orderProducts = $orderProducts;
        $this->summary = $summary;
    }

    /**
     * This function receives the path of the json for all the orders of the week,
     * processes all orders for the week,
     * while keeping track of stock levels, units sold and purchased
     * See `orders-sample.json` for example
     *
     * @param string $filePath
     */
    public function processFromJson(string $filePath): void
    {
        $chunkSize = Config::FILE_CHUNK_SIZE;
        try{
            if(file_exists(Config::INPUT_FILE_PATH.'/'.$filePath) && pathinfo($filePath)['extension'] == 'json'){
                $handle = fopen($filePath, "r", true) or die('Invalid input file');
                $contents="";
                while(!feof($handle)){
                    $contents .= fread($handle, $chunkSize);
                }
                fclose($handle);
                $orders = json_decode($contents, true);
                if (json_last_error() == 0){
                    $this->orderProducts->processOrders($orders);
                    $this->summary->echoSummary($this->orderProducts->orderSummary());
                }else{
                    throw new Exception('Invalid json file');
                } 
            }else{
                throw new Exception('Invalid input file, please enter valid file');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            echo "\n";
        } 


    }

}