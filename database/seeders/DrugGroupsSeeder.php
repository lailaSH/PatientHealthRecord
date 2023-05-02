<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use mysqli;

class DrugGroupsSeeder extends Seeder
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
        $conn = new mysqli($host, $username, $password, $database);


    $path = public_path('data\GroupsDrug.text');
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
