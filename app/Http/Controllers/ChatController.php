<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Despesa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $apiKey = env('OPENROUTER_API_KEY');

        if (is_null($apiKey)) {
            Log::error('A chave OPENROUTER_API_KEY não está definida no arquivo .env ou o cache de configuração não foi limpo.');
            return response()->json(['reply' => 'Erro de configuração no servidor: A chave da API não foi encontrada.']);
        }

        $despesas = Despesa::where('user_id', Auth::id())->get();
        $context = "Minhas despesas:\n";
        foreach ($despesas as $d) {
            $context .= "{$d->nome_despesa}: R$ {$d->valor_despesa} em {$d->data_despesa}\n";
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-7b-instruct',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um assistente financeiro. Responda perguntas sobre as despesas do usuário.'],
                ['role' => 'user', 'content' => $context . "\nPergunta: " . $userMessage],
            ],
        ]);
        
        Log::info('Resposta da API OpenRouter: ' . $response->body());

        if ($response->failed()) {
            $errorMessage = $response->json('error.message') ?? 'Não foi possível decodificar a mensagem de erro da API.';
            return response()->json(['reply' => 'A API retornou um erro: ' . $errorMessage]);
        }
        
        $rawReply = $response->json('choices.0.message.content');
        
        if (is_null($rawReply)) {
            return response()->json(['reply' => 'A resposta da API foi recebida com sucesso, mas o conteúdo esperado não foi encontrado. Verifique o log.']);
        }

        $cleanedReply = preg_replace('/\[\/?OUT\]\s*/', '', $rawReply);

        return response()->json(['reply' => trim($cleanedReply)]);
    }
}

