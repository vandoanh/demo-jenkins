<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // regenerate the list of all classes.
        exec('composer dump-autoload');

        // Find and run all seeders
        $classes = include base_path() . '/vendor/composer/autoload_classmap.php';
        foreach ($classes as $class) {
            if (strpos($class, 'TableSeeder') !== false) {
                $seederClass = substr(last(explode('/', $class)), 0, -4);
                $this->call($seederClass);
            }
        }
    }
}
