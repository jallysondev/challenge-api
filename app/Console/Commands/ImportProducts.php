<?php

namespace App\Console\Commands;

use App\Events\SendFailureSyncNotification;
use App\Models\Enum\ProductStatus;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command imports from open food facts data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info(' STARTING IMPORT PRODUCTS PROCESS ');

        try {
            DB::beginTransaction();

            $client = new Client();
            $response = $client->get(env('PRODUCT_LIST_URL'))->getBody();
            $productsList = explode("\n", $response);
            if (empty($productsList[count($productsList) - 1])) {
                unset($productsList[count($productsList) - 1]);
            }

            foreach ($productsList as $productList) {
                $response = $client->get(env('PRODUCT_IMPORT_BASE_URL').$productList);

                if ($response->getStatusCode() === Response::HTTP_OK) {
                    $fileContents = $response->getBody()->getContents();

                    Storage::disk('public')->put('file.gz', $fileContents);
                    $filePath = storage_path('/app/public/file.gz');

                    $lines = $this->chuckFile($filePath);
                    $this->storeProducts($lines);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error(' FATAL ERROR TO IMPORT PRODUCTS ', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            event(new SendFailureSyncNotification());

            return false;
        }

        \Log::info(' IMPORT PRODUCTS PROCESS FINISHED ');

        return true;
    }

    private function chuckFile($filePath): array
    {
        $handle = gzopen($filePath, 'r');

        $lineCount = 0;
        $lines = [];

        while (($line = fgets($handle)) !== false && $lineCount < 100) {
            $lines[] = json_decode($line);
            $lineCount++;
        }

        gzclose($handle);

        Storage::delete($filePath);

        return $lines;
    }

    private function storeProducts($lines): void
    {
        foreach ($lines as $line) {
            $formatedCode = (int) preg_replace('/^"*(0*)/', '', $line->code);
            Product::updateOrCreate(
                [
                    'code' => $formatedCode,
                ],
                [
                    'status' => ProductStatus::Published,
                    'imported_t' => now(),
                    'url' => $line->url,
                    'creator' => $line->creator,
                    'created_t' => $line->created_t,
                    'last_modified_t' => $line->last_modified_t,
                    'product_name' => $line->product_name,
                    'quantity' => $line->quantity,
                    'brands' => $line->brands,
                    'categories' => $line->categories,
                    'labels' => $line->labels,
                    'cities' => $line->cities,
                    'purchase_places' => $line->purchase_places,
                    'stores' => $line->stores,
                    'ingredients_text' => $line->ingredients_text,
                    'traces' => $line->traces,
                    'serving_size' => $line->serving_size,
                    'serving_quantity' => strlen($line->serving_quantity) != 0 ? $line->serving_quantity : null,
                    'nutriscore_score' => strlen($line->nutriscore_score) != 0 ? $line->nutriscore_score : null,
                    'nutriscore_grade' => $line->nutriscore_grade,
                    'main_category' => $line->main_category,
                    'image_url' => $line->image_url,
                ]);
        }
    }
}
