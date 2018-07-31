<?php


namespace Ejarnutowski\LaravelApiKey\Providers;


use function app;

/**
 * The auth middleware will make this globally available in the service container.
 *
 * @author Niko Strijbol
 */
class ApiUser
{
    private $key;
    private $name;

    public static function get()
    {
        return app(ApiUser::class);
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key)
    {
        $this->key = $key;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
