<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SummaryController extends Controller
{
    public function summary(Request $request)
    {
        $despesas = Despesa::where('user_id', Auth::id())->get();
        $texto = "Minhas despesas:\n";
        foreach ($despesas as $d) {
            $texto .= "{$d->nome_despesa}: R$ {$d->valor_despesa} em {$d->data_despesa}\n";
        }
        
        $apiKey = env('OPENROUTER_API_KEY');
        
        if (is_null($apiKey)) {
            Log::error('A chave OPENROUTER_API_KEY não está definida no arquivo .env ou o cache de configuração não foi limpo.');
            return response()->json(['summary' => 'Erro de configuração no servidor: A chave da API não foi encontrada.']);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            
            'model' => 'mistralai/mistral-7b-instruct', 
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um assistente financeiro. Gere um resumo e insights das despesas.'],
                ['role' => 'user', 'content' => $texto],
            ],
        ]);
        
        Log::info('Resposta da API OpenRouter (Summary): ' . $response->body());

        if ($response->failed()) {
            $errorMessage = $response->json('error.message') ?? 'Não foi possível decodificar a mensagem de erro da API.';
            return response()->json(['summary' => 'A API retornou um erro: ' . $errorMessage]);
        }

        $summary = $response->json('choices.0.message.content') ?? 'Não foi possível gerar o resumo.';

        return response()->json(['summary' => $summary]);
    }
}

