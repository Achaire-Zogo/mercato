<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Product $product;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 50,
            'alert_threshold' => 10,
            'price' => 100.00
        ]);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $this->assertInstanceOf(Category::class, $this->product->category);
        $this->assertEquals($this->category->id, $this->product->category->id);
    }

    /** @test */
    public function it_can_decrease_stock()
    {
        $initialStock = $this->product->stock_quantity;
        $decreaseAmount = 5;

        $this->product->decrement('stock_quantity', $decreaseAmount);
        $this->product->refresh();

        $this->assertEquals($initialStock - $decreaseAmount, $this->product->stock_quantity);
    }

    /** @test */
    public function it_can_increase_stock()
    {
        $initialStock = $this->product->stock_quantity;
        $increaseAmount = 10;

        $this->product->increment('stock_quantity', $increaseAmount);
        $this->product->refresh();

        $this->assertEquals($initialStock + $increaseAmount, $this->product->stock_quantity);
    }

    /** @test */
    public function it_can_check_if_stock_is_low()
    {
        // Set stock below alert threshold
        $this->product->stock_quantity = $this->product->alert_threshold - 1;
        $this->product->save();

        $this->assertTrue($this->product->stock_quantity <= $this->product->alert_threshold);
    }

    /** @test */


    

    /** @test */
    public function it_has_required_fields()
    {
        $product = Product::factory()->create();

        $this->assertNotNull($product->matricule);
        $this->assertNotNull($product->name);
        $this->assertNotNull($product->price);
        $this->assertNotNull($product->stock_quantity);
        $this->assertNotNull($product->alert_threshold);
        $this->assertNotNull($product->category_id);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $product = Product::factory()->create();
        $productId = $product->id;

        $product->delete();

        $this->assertSoftDeleted('products', ['id' => $productId]);
        $this->assertNotNull($product->deleted_at);
    }

    /** @test */
    public function it_can_update_stock_and_check_threshold()
    {
        $product = Product::factory()->create([
            'stock_quantity' => 20,
            'alert_threshold' => 10
        ]);

        // Réduire le stock en dessous du seuil d'alerte
        $product->decrement('stock_quantity', 15);
        $product->refresh();

        $this->assertTrue($product->stock_quantity < $product->alert_threshold);
        $this->assertEquals(5, $product->stock_quantity);
    }
}
