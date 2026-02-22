<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    /**
     * 1 - Solicitar Propostas
     * POST /v3/proposal/request
     */
    public function request(Request $request)
    {
        $identifier = $request->header('identifier', '00000000000');
        $sessionId = (string) Str::uuid();

        return response()->json([
            'name' => 'CLIENTE SIMULADO',
            'id' => rand(900000000, 999999999),
            'message' => 'Consulta de propostas iniciada com sucesso!',
            'sessionId' => $sessionId,
        ]);
    }

    /**
     * 2 - Consultar status de Solicitacao/Atualizacao de Propostas
     * GET /v3/proposal/{proposalId}/status
     */
    public function status(Request $request, $proposalId)
    {
        return response()->json([
            'name' => 'CLIENTE SIMULADO',
            'id' => (int) $proposalId,
            'completed' => true,
            'message' => 'Consulta de propostas concluida.',
        ]);
    }

    /**
     * 3 - Atualizacao de Propostas
     * POST /v3/proposal/{proposalId}/update
     */
    public function update(Request $request, $proposalId)
    {
        return response()->json([
            'name' => 'CLIENTE SIMULADO',
            'id' => (int) $proposalId,
            'message' => 'Consulta de propostas iniciada com sucesso!',
        ]);
    }

    /**
     * 4 - Consultar Propostas
     * GET /v3/proposal/{proposalId}
     */
    public function show(Request $request, $proposalId)
    {
        $identifier = $request->header('identifier', '00000000000');
        $validDate = now()->addDays(7)->format('Y-m-d');
        $today = now()->format('Y-m-d');
        $maxDate = now()->addDays(30)->format('Y-m-d');

        $debitId = rand(130000000, 140000000);
        $contractId = rand(140000000, 150000000);
        $debitValue = round(rand(1000000, 5000000) / 100, 2);

        $paymentOptions = $this->generatePaymentOptions($debitValue);

        return response()->json([
            'id' => (int) $proposalId,
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
                    'finalizedDate' => now()->toIso8601String(),
                    'identifier' => 'Negociacao',
                    'description' => null,
                    'paymentOptions' => $paymentOptions,
                    'selectedPaymentOption' => null,
                    'status' => 'OPEN',
                    'subStatus' => 'OPEN',
                    'totalValue' => $debitValue,
                    'validDate' => $validDate,
                    'suggestedValidDate' => $validDate,
                    'maxValidDate' => $maxDate,
                    'minValidDate' => $today,
                    'boleto' => null,
                    'minValue' => $paymentOptions[0]['firstValue'],
                    'externalId' => null,
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
                'initialValidDate' => $validDate,
                'userCustomerDate' => true,
                'maxValidDate' => $maxDate,
                'minValidDate' => $today,
                'expirateProposalDate' => $validDate,
                'expirateAccessDate' => $maxDate,
            ],
        ]);
    }

    /**
     * 5 - Confirmar Proposta
     * POST /v3/proposal/{proposalId}/confirm
     */
    public function confirm(Request $request, $proposalId)
    {
        $debitId = $request->input('debitId', rand(80000000, 90000000));
        $paymentOptionId = $request->input('paymentOptionId');

        if (!$paymentOptionId || !$debitId) {
            return response()->json([
                'message' => 'Opcao de pagamento invalida',
                'paymentCode' => null,
                'processing' => false,
                'dealId' => (int) $debitId,
            ], 400);
        }

        return response()->json([
            'message' => 'Acordo em processamento',
            'paymentCode' => null,
            'processing' => true,
            'dealId' => (int) $debitId,
        ]);
    }

    /**
     * Gera opcoes de pagamento fake
     */
    private function generatePaymentOptions(float $debitValue): array
    {
        $baseDiscount = 0.87;
        $avista = round($debitValue * (1 - $baseDiscount), 2);

        $installmentConfigs = [
            ['number' => 0, 'discount' => 0.8729, 'suggested' => true],
            ['number' => 2, 'discount' => 0.8330, 'suggested' => true],
            ['number' => 6, 'discount' => 0.8313, 'suggested' => false],
            ['number' => 12, 'discount' => 0.8289, 'suggested' => false],
            ['number' => 18, 'discount' => 0.8264, 'suggested' => false],
            ['number' => 24, 'discount' => 0.8240, 'suggested' => false],
        ];

        $options = [];
        foreach ($installmentConfigs as $config) {
            $id = rand(200000000, 250000000);
            $discountValue = round($debitValue * $config['discount'], 2);
            $totalValue = round($debitValue - $discountValue, 2);

            if ($config['number'] === 0) {
                $firstValue = $totalValue;
                $installmentValue = 0;
                $identifier = "A Vista R$ " . number_format($totalValue, 2, ',', '.');
                $model = 'ONE_PAYMENT';
            } else {
                $totalParcelas = $config['number'] + 1;
                $installmentValue = round($totalValue / $totalParcelas, 2);
                $firstValue = $installmentValue;
                $identifier = "Entrada de R$ " . number_format($firstValue, 2, ',', '.') .
                    " + {$config['number']}x de R$ " . number_format($installmentValue, 2, ',', '.');
                $model = 'INSTALLMENTS';
            }

            $options[] = [
                'id' => $id,
                'firstValue' => $firstValue,
                'debitValue' => $debitValue,
                'identifier' => $identifier,
                'description' => null,
                'installmentNumber' => $config['number'],
                'installmentValue' => $installmentValue,
                'model' => $model,
                'paymentDescription' => null,
                'paymentComplement' => '',
                'suggested' => $config['suggested'],
                'totalValue' => $totalValue,
                'discountValue' => $discountValue,
                'percDiscount' => $config['discount'] * 100,
            ];
        }

        return $options;
    }
}
