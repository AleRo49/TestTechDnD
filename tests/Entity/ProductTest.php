<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ProductTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetFormattedArray()
    {
        $productData = [
            'sku' => 123,
            'created_at' => '2024-12-25 00:00:00',
            'description' => 'Test Description',
            'price' => 9.99,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'is_enabled' => true,
            'currency' => 'EUR',
        ];

        $product = Product::fromArray($productData);
        $formattedArray = $product->getFormattedArray();
        $this->assertIsArray($formattedArray);
    }

    /**
     * @throws Exception
     */
    public function testFromArray()
    {
        $productData = [
            'sku' => 123,
            'created_at' => '2024-12-25 00:00:00',
            'description' => 'Test Description',
            'price' => 9.99,
            'title' => 'Test Product',
            'slug' => 'test-product',
            'is_enabled' => true,
            'currency' => 'EUR',
        ];

        $product = Product::fromArray($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productData['sku'], $product->getSku());
        $this->assertEquals($productData['created_at'], $product->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals($productData['description'], $product->getDescription());
        $this->assertEquals($productData['price'], $product->getPrice());
        $this->assertEquals($productData['title'], $product->getTitle());
        $this->assertEquals($productData['slug'], $product->getSlug());
        $this->assertEquals($productData['is_enabled'], $product->isEnabled());
        $this->assertEquals($productData['currency'], $product->getCurrency());
    }
    /**
     * @throws Exception
     */
    public function testGetSlugFromTitle()
    {
        $testTitle = 'Cornelia, the dark unicorn';
        $expectedSlug = 'cornelia--the-dark-unicorn';

        $product = new Product();
        $product->setTitle($testTitle);
        $method = new ReflectionMethod(Product::class, 'getSlugFromTitle');

        $this->assertEquals($expectedSlug, $method->invoke($product));
    }
}
