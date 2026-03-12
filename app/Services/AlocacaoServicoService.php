<?php
namespace App\Services;

use App\Models\Clientes;
use App\Models\Servicos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlocacaoServicoService
{
    /**
     * Encontra o melhor serviço disponível dentro de uma conta.
     */
    public function buscarMelhorServico(int $contaId, ?int $servicoIgnoradoId = null): ?Servicos
    {

        $query = Servicos::where('se_contas_id', $contaId)->whereNotIn('se_status', ['fechada', 'desligada'])->whereNot('se_tipo', 'Free');

        // Se estiver realocando, ignora o serviço que está sendo desligado
        if ($servicoIgnoradoId) {
            $query->where('se_id', '!=', $servicoIgnoradoId);
        }

        return $query->where(function ($q) {
            $q->where('se_status', 'davez');
            $q->orWhereNull('se_limite')->orWhereColumn('se_qtd_assinantes', '<', 'se_limite');
        })
        ->orderByRaw("CASE WHEN se_status = 'davez' THEN 1 ELSE 0 END DESC")
        ->orderBy('se_qtd_assinantes', 'asc')
        ->first();
    }

    /**
     * Aloca um cliente fisicamente em um serviço.
     */
    public function alocarNovoCliente(Clientes $cliente, int $contaId, array $dadosPivot = [])
    {

        $servico = $this->buscarMelhorServico($contaId);

        if (!$servico) {
            Log::critical("Sem vagas! Cliente ID {$cliente->cl_id} não pôde ser alocado na conta {$contaId}");
            return null; // Retorna null para o Controller saber que falhou
        }

        DB::transaction(function () use ($cliente, $servico, $dadosPivot) {
            // Vincula na tabela pivot (cv_cliente_servico)
            $cliente->servicos()->attach($servico->se_id, $dadosPivot);

            // Atualiza o contador físico
            $servico->increment('se_qtd_assinantes');
        });

        return $servico; // Retorna o serviço escolhido
    }
}
