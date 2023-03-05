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
use BlueStar\Facade\Module;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleInstallCommand extends BlueStarCommand
{
    protected $signature = 'bluestar:module:install {module} {--f}';

    protected $description = 'install bluestar module';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        if (! $this->option('f')) {
            if ($input->hasArgument('module')
                && Module::getEnabled()->pluck('name')->merge(Collection::make(config('bluestar.module.default')))->contains(lcfirst($input->getArgument('module')))
            ) {
                $this->error(sprintf('Module [%s] Has installed', $input->getArgument('module')));
                exit;
            }
        }
    }

    public function handle(): void
    {
        $installer = BlueStarAdmin::getModuleInstaller($this->argument('module'));

        $installer->install();
    }
}
