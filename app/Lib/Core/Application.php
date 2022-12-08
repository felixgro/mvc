<?php

namespace App\Lib\Core;



class Application
{
   private Container $container;
   private array $providers = [];

   public function __construct()
   {
      $this->container = new Container();
      $this->registerProviders();
   }

   public function boot()
   {
      $this->bootProviders();
   }

   public function handleRequest()
   {
      app(Router::class)->handleRequest();
   }

   public function bind(string $abstract, callable $factory)
   {
      $this->container->bind($abstract, $factory);
   }

   public function get(string $abstract): mixed
   {
      return $this->container->resolve($abstract);
   }

   private function registerProviders()
   {
      $this->providers = (require_once 'config/app.php')['providers'];

      foreach ($this->providers as $provider) {
         ($provider)::register($this->container);
      }
   }

   private function bootProviders()
   {
      foreach ($this->providers as $provider) {
         ($provider)::boot($this->container);
      }
   }
}
