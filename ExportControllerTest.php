<?php

declare(strict_types=1);

use App\Contracts\Product\ProductClientContract;
use GuzzleHttp\Psr7\Response as PsrResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

it('can export products', function (): void {
    $clientResponse = new Response(
        new PsrResponse(
            200,
            [
                'Content-Type' => 'text/csv',
            ],
            'id;name;sku',
        ),
    );


    $this->mock(ProductClientContract::class, function ($mock) use ($clientResponse): void {
        $mock
            ->shouldReceive('export')
            ->once()
            ->andReturn(
                Http::response(
                    'id;name;sku',
                    200,
                    [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="products.csv"',
                    ],
                ),
            );
    });

    $response = $this->get('/api/v1/products/export');

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8')
        ->assertHeader(
            'Content-Disposition',
            'attachment; filename="products.csv"',
        )
        ->assertContent('id;name;sku');
});

it('can export products with content disposition', function (): void {
    $clientResponse = new Response(
        new PsrResponse(
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="products.csv"',
            ],
            'id;name;sku',
        ),
    );

    $this->mock(ProductClientContract::class, function ($mock) use ($clientResponse): void {
        $mock
            ->shouldReceive('export')
            ->once()
            ->andReturn($clientResponse);
    });

    $response = $this->get('/api/v1/products/export');

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8')
        ->assertHeader(
            'Content-Disposition',
            'attachment; filename="products.csv"',
        )
        ->assertContent('id;name;sku');
});

it('can export products without content disposition', function (): void {
    $clientResponse = new Response(
        new PsrResponse(
            200,
            [
                'Content-Type' => 'text/csv',
            ],
            'id;name;sku',
        ),
    );

    $this->mock(ProductClientContract::class, function ($mock) use ($clientResponse): void {
        $mock
            ->shouldReceive('export')
            ->once()
            ->andReturn($clientResponse);
    });

    $response = $this->get('/api/v1/products/export');

    $response
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8')
        ->assertHeaderMissing('Content-Disposition')
        ->assertContent('id;name;sku');
});
