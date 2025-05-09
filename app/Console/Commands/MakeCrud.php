<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a migration, seeder, factory, policy, resource controller, actions, and form request classes for the model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->call('make:model', [
            'name' => "$name",
            '--migration' => true,
            '--factory' => true,
            '--seed' => true,
        ]);

        $this->call('make:policy', [
            'name' => "$name" . 'Policy',
            '--model' => "App\\Models\\$name",
        ]);

        $this->call('make:observer', [
            'name' => "$name" . 'Observer',
            '--model' => "App\\Models\\$name",
        ]);

        $this->call('make:controller', [
            'name' => "Api/V1/{$name}Controller",
            '--api' => true,
            '--model' => "App\\Models\\$name",
        ]);

        $this->call('make:request', [
            'name' => "V1\\Store{$name}Request",
        ]);

        $this->call('make:request', [
            'name' => "V1\\Update{$name}Request",
        ]);

        $this->call('make:test', [
            'name' => "{$name}Test",
        ]);

        $this->makeFile('Index', $name, $this->IndexActionClass($name));
        $this->makeFile('Show', $name, $this->ShowActionClass($name));
        $this->makeFile('Store', $name, $this->StoreActionClass($name));
        $this->makeFile('Update', $name, $this->UpdateActionClass($name));
        $this->makeFile('Destroy', $name, $this->DestroyActionClass($name));


        $this->info('Full resource generated successfully.');
    }

    protected function makeDirectory($path)
    {
        if (! file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    protected function makeFile($action, $model, $content)
    {
        $path = app_path("Actions/V1/$model");
        $this->makeDirectory($path);
        file_put_contents(app_path("Actions/V1/$model/{$action}{$model}Action.php"), $content);
    }

    protected function IndexActionClass($model)
    {
        $namespace = "App\\Actions\\V1\\{$model}";
        $className = "Index{$model}Action";

        return <<<PHP
        <?php

        namespace $namespace;

        class $className
        {
            public function handle()
            {
                // authorize

                // return models with relationships
            }
        }
        PHP;
    }

    protected function ShowActionClass($model)
    {
        $namespace = "App\\Actions\\V1\\{$model}";
        $className = "Show{$model}Action";

        return <<<PHP
        <?php

        namespace $namespace;

        class $className
        {
            public function handle()
            {
                // authorize

                // return a model with relationships
            }
        }
        PHP;
    }

    protected function StoreActionClass($model)
    {
        $namespace = "App\\Actions\\V1\\{$model}";
        $className = "Store{$model}Action";

        return <<<PHP
        <?php

        namespace $namespace;

        class $className
        {
            public function handle()
            {
                // authorize

                // create a model

                // return created model
            }
        }
        PHP;
    }

    protected function UpdateActionClass($model)
    {
        $namespace = "App\\Actions\\V1\\{$model}";
        $className = "Update{$model}Action";

        return <<<PHP
        <?php

        namespace $namespace;

        class $className
        {
            public function handle()
            {
                // authorize

                // update resource

                // return updated resource
            }
        }
        PHP;
    }

    public function DestroyActionClass($model)
    {
        $namespace = "App\\Actions\\V1\\{$model}";
        $className = "Destroy{$model}Action";

        return <<<PHP
        <?php

        namespace $namespace;

        class $className
        {
            public function handle()
            {
                // authorize

                // delete resource

                // return nothing
            }
        }
        PHP;
    }
}
