<?php namespace App\Console\Commands;

   use Illuminate\Console\Command;
   use Illuminate\Support\Facades\DB;

   class CreateDatabase extends Command
   {
       protected $signature = 'db:create {name}';
       protected $description = 'Create a new MySQL database';

       public function handle()
       {
           $database = $this->argument('name');
           try {
               DB::statement("CREATE DATABASE IF NOT EXISTS `$database`");
               $this->info("Database '$database' created successfully.");
           } catch (\Exception $e) {
               $this->error("Failed to create database: " . $e->getMessage());
           }
       }
   }