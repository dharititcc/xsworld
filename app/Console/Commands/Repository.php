<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Repository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The path of the repository class.
     *
     * @var string
     */
    protected $path = 'App\\Repositories';

    /**
     * The folder of the repository class.
     *
     * @var string
     */
    protected $folder = 'Repositories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Repository class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // generate repository class
        $arguments  = $this->arguments();
        $location   = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR;
        $file       = $location.$arguments['name'].'.php';

        // check repository class exist
        if( file_exists($file) )
        {
            $this->error($arguments['name'].' is already exist.');
            exit;
        }

        $repository = fopen($file, "w") or die("Unable to open file!");
        $txt        = "<?php namespace App\Repositories;\n\n";
        fwrite($repository, $txt);

        $txt2       = "use App\Repositories\BaseRepository;\n\n";
        fwrite($repository, $txt2);

        $txt3       = "/**\n * Class {$arguments['name']}.\n*/\n";
        fwrite($repository, $txt3);

        $txt4       = "class UserRepository extends BaseRepository\n{\n}";
        fwrite($repository, $txt4);


        $this->info($arguments['name'].' created successfully.');
    }
}
