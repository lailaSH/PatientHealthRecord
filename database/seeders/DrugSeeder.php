<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use mysqli;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $username = env('DB_USERNAME');
            $password =env('DB_PASSWORD');
            $host = env('DB_HOST');
            $database = env('DB_DATABASE');
            $conn = new mysqli_connect($host, $username, $password, $database);


        $path = public_path('data\Drugs.text');
        $sql=fopen($path,"r");
        if($sql)
        {
            while($line=fgets($sql))
            {
                $stmt = $conn->prepare($line);
                if(!$stmt){
                    error_log($conn->error);
                }else{
                $stmt->execute();
                error_log($line);

            }
            }
            fclose($sql);
        }

        }
}
