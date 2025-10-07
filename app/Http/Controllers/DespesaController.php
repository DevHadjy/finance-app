<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DespesaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $despesas = Despesa::where('user_id', Auth::id())->orderBy('data_despesa', 'desc')->get();
        return view('despesas.index', compact('despesas'));
    }

    public function create()
    {
        return view('despesas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_despesa' => 'required|string|max:255',
            'valor_despesa' => 'required|numeric',
            'data_despesa' => 'required|date',
        ]);

        Despesa::create([
            'user_id' => Auth::id(),
            'nome_despesa' => $request->nome_despesa,
            'valor_despesa' => $request->valor_despesa,
            'data_despesa' => $request->data_despesa,
        ]);

        return redirect()->route('despesas.index')->with('success', 'Despesa adicionada!');
    }

    public function edit(Despesa $despesa)
    {
        $this->authorize('update', $despesa);
        return view('despesas.edit', compact('despesa'));
    }

    public function update(Request $request, Despesa $despesa)
    {
        $this->authorize('update', $despesa);

        $request->validate([
            'nome_despesa' => 'required|string|max:255',
            'valor_despesa' => 'required|numeric',
            'data_despesa' => 'required|date',
        ]);

        $despesa->update($request->only(['nome_despesa', 'valor_despesa', 'data_despesa']));

        return redirect()->route('despesas.index')->with('success', 'Despesa atualizada!');
    }

    public function destroy(Despesa $despesa)
    {
        $this->authorize('delete', $despesa);
        $despesa->delete();
        return redirect()->route('despesas.index')->with('success', 'Despesa removida!');
    }

    public function importForm()
    {
        return view('despesas.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Supondo: nome, valor, data
            if(count($data) < 3) continue;
            Despesa::create([
                'user_id' => Auth::id(),
                'nome_despesa' => $data[0],
                'valor_despesa' => $data[1],
                'data_despesa' => $data[2],
            ]);
        }
        fclose($handle);

        return redirect()->route('despesas.index')->with('success', 'Despesas importadas!');
    }
    
    public function show(Despesa $despesa)
    {
        $this->authorize('view', $despesa);
        return view('despesas.show', compact('despesa'));
    }
}