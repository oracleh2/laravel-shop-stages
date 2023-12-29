<?php

namespace App\Http\Controllers;

use Database\Factories\ProductFactory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

     /** @test */
     public function it_generated_success():void
     {
         $size = '100x100';
         $method = 'resize';
         $storage = Storage::disk('images');
         config()->set('thumbnail', ['allowed_sizes' => [$size]]);
         $product = ProductFactory::new()->create();
         $response = $this->get($product->makeThumbnail($size, $method));
         $response->assertOk();
         $storage->assertExists(
             "products/{$method}/{$size}/" . File::basename($product->thumbnail)
         );
     }

     /** @test */
    public function it_created_success_by_me(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');

        $dir = 'images';
        $method = 'resize';
        $size = '100x100';
        $file = $image->hashName();

        Storage::disk('images')->putFileAs($dir, $image, $file);

        config()->set('thumbnail', ['allowed_sizes' => [$size]]);
        $response = $this->get(route('thumbnail', [$dir, $method, $size, $file]));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $this->assertTrue(Storage::disk('images')->exists("$dir/$method/$size/$file"));
    }
    public function it_created_fail_by_me(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');

        $dir = 'images';
        $method = 'resize';
        $size = '100x100';
        $file = $image->hashName();

        Storage::disk('images')->putFileAs($dir, $image, $file);

        $response = $this->get(route('thumbnail', [$dir, $method, $size, $file]));
        $response->assertStatus(404);

    }
}
