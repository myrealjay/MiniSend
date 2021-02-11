<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new repository';

    /**
     * The repository name
     *
     * @var string
     */
    protected string $repositoryName;

    /**
     * The namespace for the repository
     *
     * @var string
     */
    protected string $repositoryNamespace;

    /**
     * The repository class
     *
     * @var string
     */
    protected string $repositoryClass;

    /**
     * The repository interface(contract)
     *
     * @var string
     */
    protected string $repositoryInterface;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->repositoryName = Str::studly($this->argument('name'));
            $repositoryArray = Str::of($this->repositoryName)->explode('/');

            $this->repositoryClass = $repositoryArray->last() . 'Repository';
            $this->repositoryInterface = "{$this->repositoryClass}Interface";

            // If $this->argument('name') looks like a path(e.g Sample/Profile),
            // we make sure "Profile" is the target class and interface.
            if ($repositoryArray->count() > 1) {
                $this->repositoryName = str_replace("/{$repositoryArray->last()}", '', $this->repositoryName);
            }

            $this->repositoryNamespace = 'App\\Repositories\\' . str_replace(
                    ['/', "\\$this->repositoryClass"], ['\\', ''], $this->repositoryName
                );

            if (class_exists("$this->repositoryNamespace\\$this->repositoryClass")) {
                $this->alert("$this->repositoryName repository seems to already exist.");
                $this->error("$this->repositoryNamespace\\$this->repositoryClass, already created.");

                if (interface_exists("$this->repositoryNamespace\\$this->repositoryInterface")) {
                    $this->error("$this->repositoryNamespace\\$this->repositoryInterface, already created.");
                }

                return;
            }

            $this->generateRepositoryClassAndInterface()
                ->returnSuccessResponses();
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * Generates repository class and interface (contract)
     *
     * @return \App\Console\Commands\MakeRepositoryCommand
     */
    private function generateRepositoryClassAndInterface(): self
    {
        $repositoryClassContents = $this->buildContentsFromStub(
            'Class', ['{NAMESPACE}', '{CLASS}', '{INTERFACE}'],
            [$this->repositoryNamespace, $this->repositoryClass, $this->repositoryInterface],
        );

        $repositoryInterfaceContents = $this->buildContentsFromStub(
            'Interface', ['{NAMESPACE}', '{INTERFACE}'],
            [$this->repositoryNamespace, $this->repositoryInterface]
        );

        $directory = str_replace($this->repositoryClass, '', app_path("Repositories/{$this->repositoryName}"));

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $classFile = "{$directory}/{$this->repositoryClass}.php";
        $interfaceFile = "{$directory}/{$this->repositoryInterface}.php";

        File::put($classFile, $repositoryClassContents);
        File::put($interfaceFile, $repositoryInterfaceContents);

        return $this;
    }

    /**
     * Returns success responses
     *
     * @return void
     */
    private function returnSuccessResponses(): void
    {
        $this->info("$this->repositoryName Repository Created Successfully.");
        $this->line("1. $this->repositoryNamespace\\$this->repositoryClass::class");
        $this->line("2. $this->repositoryNamespace\\$this->repositoryInterface::class");
    }

    /**
     * Build contents for repository files
     *
     * @param string $stubName
     * @param array $templates
     * @param array $replacers
     *
     * @return string|string[]
     */
    private function buildContentsFromStub(string $stubName, array $templates, array $replacers)
    {
        return str_replace(
            $templates,
            $replacers,
            File::get(app_path("Stubs/Repository{$stubName}.txt"))
        );
    }
}
