<?php

// +----------------------------------------------------------------------
// | HuyPham [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2023~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

namespace BlueStar\Commands;

use BlueStar\BlueStarAdmin;
use Illuminate\Console\Application;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use BlueStar\Support\Composer;

class InstallCommand extends BlueStarCommand
{
    protected $signature = 'bluestar:install';

    protected $description = 'install bluestar admin';

    /**
     * @var array|string[]
     */
    private array $defaultExtensions = ['BCMath', 'Ctype', 'DOM', 'Fileinfo', 'JSON', 'Mbstring', 'OpenSSL', 'PCRE', 'PDO', 'Tokenizer', 'XML'];

    /**
     * handle
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            // 如果没有 .env 文件
            if (!File::exists(app()->environmentFile())) {
                $this->detectionEnvironment();

                $this->copyEnvFile();

                $this->askForCreatingDatabase();
            }

            $this->publishConfig();
            $this->installed();
        } catch (\Throwable $e) {
            File::delete(app()->environmentFilePath());

            $this->error($e->getMessage());
        }
    }

    /**
     * 环境检测
     *
     * @return void
     */
    protected function detectionEnvironment(): void
    {
        $this->checkPHPVersion();

        $this->checkExtensions();
    }


    /**
     * check needed php extensions
     */
    private function checkExtensions()
    {
        /* @var  Collection $loadedExtensions */
        $loadedExtensions = Collection::make(get_loaded_extensions())->map(function ($item) {
            return strtolower($item);
        });

        Collection::make($this->defaultExtensions)
            ->each(function ($extension) use ($loadedExtensions, &$continue) {
                $extension = strtolower($extension);

                if (!$loadedExtensions->contains($extension)) {
                    $this->error("$extension extension 未安装");
                }
            });
    }

    /**
     * check php version
     */
    private function checkPHPVersion()
    {
        if (version_compare(PHP_VERSION, '8.1.0', '<')) {
            $this->error('php version should >= 8.1');
        }
    }


    /**
     * create database
     *
     * @param string $databaseName
     * @return void
     * @throws BindingResolutionException
     */
    private function createDatabase(string $databaseName): void
    {
        $databaseConfig = config('database.connections.' . DB::getDefaultConnection());

        $databaseConfig['database'] = null;

        app(ConnectionFactory::class)->make($databaseConfig)->select(sprintf("create database if not exists $databaseName default charset %s collate %s", 'utf8mb4', 'utf8mb4_general_ci'));
    }

    /**
     * copy .env
     *
     * @return void
     */
    protected function copyEnvFile(): void
    {
        if (!File::exists(app()->environmentFilePath())) {
            File::copy(app()->environmentFilePath() . '.example', app()->environmentFilePath());
        }

        if (!File::exists(app()->environmentFilePath())) {
            $this->error('【.env】Creation failed. Please try again or create manually！');
        }

        File::put(app()->environmentFile(), implode("\n", explode("\n", $this->getEnvFileContent())));
    }

    /**
     * get env file content
     *
     * @return string
     */
    protected function getEnvFileContent(): string
    {
        return File::get(app()->environmentFile());
    }

    /**
     * publish config
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        try {
            Process::run(Application::formatCommandString('key:generate'))->throw();

            Process::run(Application::formatCommandString('vendor:publish --tag=bluestar-config'))->throw();

            Process::run(Application::formatCommandString('vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"'))->throw();

            Process::run(Application::formatCommandString('bluestar:migrate user'))->throw();

            Process::run(Application::formatCommandString('bluestar:migrate develop'))->throw();

            Process::run(Application::formatCommandString('migrate'))->throw();

            Process::run(Application::formatCommandString('bluestar:db:seed user'))->throw();
        } catch (\Exception | \Throwable $e) {
            $this->error($e->getMessage());
            exit;
        }
    }

    /**
     * create database
     */
    protected function askForCreatingDatabase()
    {
        $appUrl = $this->askFor('请配置应用的 URL');

        if ($appUrl && !Str::contains($appUrl, 'http://') && !Str::contains($appUrl, 'https://')) {
            $appUrl = 'http://' . $appUrl;
        }

        $databaseName = $this->askFor('Please enter the name of the database.');

        $prefix = $this->askFor('Please enter the database table prefix.', '');

        $dbHost = $this->askFor('Please enter the database host address.', '127.0.0.1');

        $dbPort = $this->askFor('Please enter the port number of the database.', 3306);

        $dbUsername = $this->askFor('Please enter the username of the database.', 'root');

        $dbPassword = $this->askFor('Please enter the database password.');

        if (!$dbPassword) {
            $dbPassword = $this->askFor('Are you sure you want to leave the database password blank?');
        }

        // set env
        $env = explode("\n", $this->getEnvFileContent());

        foreach ($env as &$value) {
            foreach ([
                'APP_URL' => $appUrl,
                'DB_HOST' => $dbHost,
                'DB_PORT' => $dbPort,
                'DB_DATABASE' => $databaseName,
                'DB_USERNAME' => $dbUsername,
                'DB_PASSWORD' => $dbPassword,
                'DB_PREFIX' => $prefix
            ] as $key => $newValue) {
                if (Str::contains($value, $key) && !Str::contains($value, 'VITE_')) {
                    $value = $this->resetEnvValue($value, $newValue);
                }
            }
        }

        File::put(app()->environmentFile(), implode("\n", $env));

        app()->bootstrapWith([
            LoadEnvironmentVariables::class,
            LoadConfiguration::class
        ]);

        $this->info("Creating database [$databaseName]...");

        $this->createDatabase($databaseName);

        $this->info("Create database [$databaseName] Success");
    }

    /**
     * @param $originValue
     * @param $newValue
     * @return string
     */
    protected function resetEnvValue($originValue, $newValue): string
    {
        if (Str::contains($originValue, '=')) {
            $originValue = explode('=', $originValue);

            $originValue[1] = $newValue;

            return implode('=', $originValue);
        }

        return $originValue;
    }

    /**
     * add prs4 autoload
     */
    protected function addPsr4Autoload()
    {
        $composerFile = base_path() . DIRECTORY_SEPARATOR . 'composer.json';

        $composerJson = json_decode(File::get(base_path() . DIRECTORY_SEPARATOR . 'composer.json'), true);

        $composerJson['autoload']['psr-4'][BlueStarAdmin::getModuleRootNamespace()] = str_replace('\\', '/', config('bluestar.module.root'));

        File::put($composerFile, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info('composer dump autoload..., Please be patient');

        app(Composer::class)->dumpAutoloads();
    }

    /**
     * admin installed
     */
    public function installed()
    {
        $this->addPsr4Autoload();

        $this->info('🎉 BlueStarAdmin Installed successfully, welcome!');

        $this->output->info(sprintf(BlueStarAdmin::VERSION));

        $this->support();
    }

    /**
     * support
     *
     * @return void
     */
    protected function support(): void
    {
    }
}
