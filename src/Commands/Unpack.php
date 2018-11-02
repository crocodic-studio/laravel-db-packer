<?php namespace Crocodic\LaravelDBPacker\Commands;

use App;
use Illuminate\Console\Command;
use Artisan;
use DB;
use Schema;
use Symfony\Component\Process\Process;

class Unpack extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'packer:unpack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpacking command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Laravel DB Packer : Unpacking");
        Schema::disableForeignKeyConstraints();
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        foreach($tables as $table) {
            Schema::drop($table);
        }
        $this->info('Migrating the tables...');
        $this->call("migrate");

        $this->info('Seeding the data...');
        $this->call("db:seed",["--class"=>"LaravelDBPackerSeeder"]);
        Schema::enableForeignKeyConstraints();
        $this->info("Unpacking completed!");
    }
}
