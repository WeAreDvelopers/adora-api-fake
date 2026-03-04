<?php

namespace App\Http\Controllers;

use App\Helpers\NumberToWords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DealController extends Controller
{
    /**
     * 6 - Consultar dados de acordo
     * GET /v3/deals/{dealId}
     */
    public function show(Request $request, $dealId)
    {
        $identifier = $request->header('identifier', '00000000000');
        $contractId = rand(140000000, 150000000);
        $today = now()->format('Y-m-d');

        $debitValue = round(rand(100000, 300000) / 100, 2);
        $installmentNumber = 18;
        $totalParcelas = $installmentNumber + 1;
        $totalValue = round($debitValue * 0.53, 2);
        $installmentValue = round($totalValue / $totalParcelas, 2);
        $discountValue = round($debitValue - $totalValue, 2);

        return response()->json([
            'message' => '',
            'processing' => false,
            'debit' => [
                'id' => (int) $dealId,
                'contracts' => [
                    [
                        'products' => [],
                        'id' => $contractId,
                        'description' => $identifier,
                        'value' => 0,
                        'percDiscount' => 0,
                        'installments' => [],
                        'invoices' => [],
                        'externalId' => null,
                    ],
                ],
                'finalizedDate' => now()->subHours(rand(1, 48))->toIso8601String(),
                'identifier' => 'Negociacao',
                'description' => null,
                'paymentOptions' => null,
                'selectedPaymentOption' => [
                    'id' => rand(200000000, 250000000),
                    'firstValue' => $installmentValue,
                    'firstValueExtenso' => NumberToWords::brl($installmentValue),
                    'debitValue' => $debitValue,
                    'debitValueExtenso' => NumberToWords::brl($debitValue),
                    'identifier' => "Entrada de R$ " . number_format($installmentValue, 2, ',', '.') .
                        " + {$installmentNumber}x de R$ " . number_format($installmentValue, 2, ',', '.'),
                    'description' => null,
                    'installmentNumber' => $installmentNumber,
                    'installmentValue' => $installmentValue,
                    'installmentValueExtenso' => NumberToWords::brl($installmentValue),
                    'model' => 'INSTALLMENTS',
                    'paymentDescription' => null,
                    'paymentComplement' => '',
                    'suggested' => false,
                    'discountValue' => $discountValue,
                    'discountValueExtenso' => NumberToWords::brl($discountValue),
                    'totalValue' => $totalValue,
                    'totalValueExtenso' => NumberToWords::brl($totalValue),
                    'percDiscount' => round(($discountValue / $debitValue) * 100, 2),
                ],
                'status' => 'CLOSED',
                'subStatus' => 'WAITING_PAYMENT',
                'totalValue' => $debitValue,
                'totalValueExtenso' => NumberToWords::brl($debitValue),
                'validDate' => $today,
                'suggestedValidDate' => $today,
                'maxValidDate' => $today,
                'minValidDate' => $today,
                'boleto' => [
                    'paymentLine' => '36890.' . rand(10000, 99999) . ' ' . rand(10000, 99999) . '.' . rand(100000, 999999) . ' ' . rand(10000, 99999) . '.' . rand(100000, 999999) . ' 4 ' . str_pad(rand(1, 99999999999999), 14, '0', STR_PAD_LEFT),
                    'barcode' => null,
                    'qrCodePix' => Str::random(60),
                    'paymentLink' => null,
                    'url' => 'https://negocie-aqui.com/c/boleto/' . Str::random(8) . '/' . Str::random(8),
                    'value' => $installmentValue,
                    'valueExtenso' => NumberToWords::brl($installmentValue),
                    'base64' => base64_encode(Str::random(100)),
                ],
                'minValue' => round($debitValue * 0.42, 2),
                'minValueExtenso' => NumberToWords::brl(round($debitValue * 0.42, 2)),
                'externalId' => (string) rand(10000000, 99999999),
                'requestPromise' => false,
                'canNegotiateAfterPromise' => false,
            ],
        ]);
    }

    /**
     * 7 - Listar Acordos
     * GET /v3/deals
     */
    public function index(Request $request)
    {
        $identifier = $request->header('identifier', '00000000000');

        // Simula chance de nao encontrar acordos
        if (rand(1, 10) > 8) {
            return response()->json([
                'id' => null,
                'name' => '',
                'firstName' => null,
                'document' => null,
                'debits' => null,
                'addresses' => null,
                'processing' => false,
                'message' => 'Nenhum acordo encontrado',
                'hash' => null,
                'extraFields' => null,
                'extraData' => null,
                'baseDebit' => null,
            ]);
        }

        $debitId = rand(130000000, 140000000);
        $contractId = rand(140000000, 150000000);
        $today = now()->format('Y-m-d');
        $maxDate = now()->addDays(30)->format('Y-m-d');

        $debitValue = round(rand(100000, 300000) / 100, 2);
        $installmentNumber = 18;
        $totalParcelas = $installmentNumber + 1;
        $totalValue = round($debitValue * 0.53, 2);
        $installmentValue = round($totalValue / $totalParcelas, 2);
        $discountValue = round($debitValue - $totalValue, 2);

        return response()->json([
            'id' => rand(900000000, 999999999),
            'name' => 'CLIENTE SIMULADO DA SILVA',
            'firstName' => 'CLIENTE',
            'document' => $identifier,
            'debits' => [
                [
                    'id' => $debitId,
                    'contracts' => [
                        [
                            'products' => [],
                            'id' => $contractId,
                            'description' => $identifier,
                            'value' => 0,
                            'percDiscount' => 0,
                            'installments' => [],
                            'invoices' => [],
                            'externalId' => null,
                        ],
                    ],
                    'finalizedDate' => now()->subHours(rand(1, 48))->toIso8601String(),
                    'identifier' => 'Negociacao',
                    'description' => null,
                    'paymentOptions' => null,
                    'selectedPaymentOption' => [
                        'id' => rand(200000000, 250000000),
                        'firstValue' => $installmentValue,
                        'firstValueExtenso' => NumberToWords::brl($installmentValue),
                        'debitValue' => $debitValue,
                        'debitValueExtenso' => NumberToWords::brl($debitValue),
                        'identifier' => "Entrada de R$ " . number_format($installmentValue, 2, ',', '.') .
                            " + {$installmentNumber}x de R$ " . number_format($installmentValue, 2, ',', '.'),
                        'description' => null,
                        'installmentNumber' => $installmentNumber,
                        'installmentValue' => $installmentValue,
                        'installmentValueExtenso' => NumberToWords::brl($installmentValue),
                        'model' => 'INSTALLMENTS',
                        'paymentDescription' => null,
                        'paymentComplement' => '',
                        'suggested' => false,
                        'discountValue' => $discountValue,
                        'discountValueExtenso' => NumberToWords::brl($discountValue),
                        'totalValue' => $totalValue,
                        'totalValueExtenso' => NumberToWords::brl($totalValue),
                        'percDiscount' => round(($discountValue / $debitValue) * 100, 2),
                    ],
                    'status' => 'CLOSED',
                    'subStatus' => 'WAITING_PAYMENT',
                    'totalValue' => $debitValue,
                    'totalValueExtenso' => NumberToWords::brl($debitValue),
                    'validDate' => $today,
                    'suggestedValidDate' => $today,
                    'maxValidDate' => $today,
                    'minValidDate' => now()->subDay()->format('Y-m-d'),
                    'boleto' => [
                        'paymentLine' => '36890.' . rand(10000, 99999) . ' ' . rand(10000, 99999) . '.' . rand(100000, 999999) . ' ' . rand(10000, 99999) . '.' . rand(100000, 999999) . ' 4 ' . str_pad(rand(1, 99999999999999), 14, '0', STR_PAD_LEFT),
                        'barcode' => null,
                        'qrCodePix' => Str::random(60),
                        'paymentLink' => null,
                        'url' => 'https://negocie-aqui.com/c/boleto/' . Str::random(8) . '/' . Str::random(8),
                        'value' => $installmentValue,
                        'valueExtenso' => NumberToWords::brl($installmentValue),
                        'base64' => base64_encode(Str::random(100)),
                    ],
                    'minValue' => round($debitValue * 0.42, 2),
                    'minValueExtenso' => NumberToWords::brl(round($debitValue * 0.42, 2)),
                    'externalId' => (string) rand(10000000, 99999999),
                    'requestPromise' => false,
                    'canNegotiateAfterPromise' => false,
                ],
            ],
            'addresses' => null,
            'processing' => false,
            'message' => null,
            'hash' => Str::random(11),
            'extraFields' => null,
            'extraData' => [
                'API_EXTRA_1' => $identifier,
            ],
            'baseDebit' => [
                'id' => rand(50000, 60000),
                'initialValidDate' => $today,
                'userCustomerDate' => true,
                'maxValidDate' => $maxDate,
                'minValidDate' => now()->subDay()->format('Y-m-d'),
                'expirateProposalDate' => $today,
                'expirateAccessDate' => $maxDate,
            ],
        ]);
    }
}
