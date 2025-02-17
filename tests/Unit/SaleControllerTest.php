<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\SaleItem;
use App\Http\Controllers\SaleController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private SaleController $saleController;
    private User $user;
    private Sale $sale;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur de test
        $this->user = User::factory()->create();
        
        // Créer une vente de test
        $this->sale = Sale::factory()->create([
            'user_id' => $this->user->id,
            'total_amount' => 100.00,
            'payment_method' => 'cash',
            'invoice_number' => 'INV-TEST123'
        ]);

        $this->saleController = new SaleController();
    }

    /** @test */
    public function it_can_generate_invoice()
    {
        $response = $this->saleController->generateInvoice($this->sale);
        
        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('message', $response->getData(true));
    }

    /** @test */
    public function it_can_calculate_totals_correctly()
    {
        // Créer un produit
        $product = Product::factory()->create([
            'price' => 50.00
        ]);

        // Créer un item de vente
        SaleItem::factory()->create([
            'sale_id' => $this->sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
            'subtotal' => $product->price * 2
        ]);

        // Appeler la méthode calculateTotals via reflection puisqu'elle est private
        $reflection = new \ReflectionClass($this->saleController);
        $method = $reflection->getMethod('calculateTotals');
        $method->setAccessible(true);
        $method->invoke($this->saleController, $this->sale);

        // Vérifier les calculs
        $this->assertEquals(100.00, $this->sale->subtotal);
        $this->assertEquals(20.00, $this->sale->tax); // 20% TVA
        $this->assertEquals(120.00, $this->sale->total_amount);
    }

    /** @test */
    public function it_can_get_daily_report()
    {
        $response = $this->saleController->dailyReport();
        
        $this->assertEquals(200, $response->status());
        $this->assertIsArray($response->getData(true));
    }
}
