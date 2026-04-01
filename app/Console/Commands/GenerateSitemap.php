<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for public pages';

    /**
     * Execute the console command.
     */
    public function handle()
{
    \Spatie\Sitemap\SitemapGenerator::create('https://tkaba54semarang.my.id/')->writeToFile(public_path('sitemap.xml'));
}
}
