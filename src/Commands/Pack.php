<?php namespace Crocodic\LaravelDBPacker\Commands;

use App;
use Illuminate\Console\Command;
use Artisan;
use DB;
use Symfony\Component\Process\Process;

class Pack extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'packer:pack {--ignoreTable=} {--ignoreTableData=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Packing command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option("ignoreTable")) {
            $tableExcept = explode(",",$this->option("ignoreTable"));
        }else{
            $tableExcept = [];
        }

        if($this->option("ignoreTableData")) {
            $dataExcept = explode(",",$this->option("ignoreTableData"));
        }else{
            $dataExcept = [];
        }

        $dataExcept = array_merge(['cms_logs','migrations'],$dataExcept);


        $composer_path = '';
        if (file_exists(getcwd().'/composer.phar')) {
            $composer_path = '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }else{
            $composer_path = 'composer';
        }
        $this->info('LARAVEL DB PACKER');
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        $php_string = "";
        foreach($tables as $table) {

            if(in_array($table, $dataExcept)) continue;

            $this->info("Create seeder for table : ".$table);
            $rows = DB::table($table)->get();
            $data = [];
            foreach($rows as $i=>$row) {
                $data[$i] = [];
                foreach($row as $key=>$val) {
                    $data[$i][$key] = $val;
                }
            }
            if(count($data)!=0) {
                $php_string .= 'DB::table(\''.$table.'\')->insert('.min_var_export($data).');'."\n\t\t\t";
            }
        }
        $seederFileTemplate = '
<?php
use Illuminate\Database\Seeder;
class LaravelDBPackerSeeder extends Seeder
{
    public function run()
    {
        $this->command->info(\'Please wait updating the data...\');                
        $this->call(\'DefaultData\');        
        $this->command->info(\'Updating the data completed !\');
    }
}
class DefaultData extends Seeder {
    public function run() {        
    	'.$php_string.'
    }
}
	';

        $this->info('Generate migration...');
        //Clear File
        $files = glob('database/migrations/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file))
                unlink($file); // delete file
        }

        if($tableExcept) {
            Artisan::call('migrate:generate',['--no-interaction' => true,'--ignore'=>implode(",",$tableExcept)]);
        }else{
            Artisan::call('migrate:generate',['--no-interaction' => true]);
        }


        $this->info('Create seeder file');
        file_put_contents(base_path('database/seeds/LaravelDBPackerSeeder.php'), $seederFileTemplate);
        $this->info('Dumping auto loads new file seeder !');
        $process = new Process($composer_path.' dump-autoload');
        $process->setWorkingDirectory(base_path())->run();

        $this->info('Pack the table and data has been finished!. Next you only need to call "php artisan packer:unpack"');
    }
}
