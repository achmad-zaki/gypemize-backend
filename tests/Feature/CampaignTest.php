<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class EnvelopeTestController extends Controller
{
    public function envelope(): JsonResponse
    {
        return $this->success(
            data: ['id' => 1],
            message: 'Campaign berhasil dibuat.',
            meta: ['total' => 1],
            links: ['self' => 'http://localhost/api/campaigns/1'],
            status: 201,
        );
    }

    public function emptyEnvelope(): JsonResponse
    {
        return $this->success(data: null, message: 'Campaign dihapus.');
    }
}

it('menghasilkan envelope success, message, data, meta, links', function () {
    $controller = new EnvelopeTestController();
    $response = $controller->envelope();
    $body = $response->getData(true);

    expect($response->getStatusCode())->toBe(201)
        ->and($body)->toHaveKeys(['success', 'message', 'data', 'meta', 'links'])
        ->and($body['success'])->toBeTrue()
        ->and($body['message'])->toBe('Campaign berhasil dibuat.')
        ->and($body['data'])->toBe(['id' => 1])
        ->and($body['meta'])->toBe(['total' => 1])
        ->and($body['links'])->toBe(['self' => 'http://localhost/api/campaigns/1']);
});

it('mengubah meta dan links kosong menjadi objek JSON', function () {
    $controller = new EnvelopeTestController();
    $content = $controller->emptyEnvelope()->getContent();

    expect($content)->toContain('"data":null')
        ->and($content)->toContain('"meta":{}')
        ->and($content)->toContain('"links":{}');
});