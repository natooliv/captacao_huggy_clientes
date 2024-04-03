<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    public function index()
    {
        Log::info('Buscando todos os clientes.');
        $clients = Client::all();
        return response()->json($clients);
    }


    public function uploadImage(Request $request)
    {
        Log::info('Recebendo arquivo para upload.');
        $request->validate([
            'photo' => 'required|image|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $temporaryPath = $file->store('temp', 'public');
            return response()->json(['temporaryPath' => $temporaryPath]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }


    public function store(Request $request)
    {
        Log::info('Recebendo dados para criar um novo cliente', ['data' => $request->all()]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients|max:255',
            'phone' => 'required|string|max:20',
            'celular' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'photo' => 'nullable|max:10240',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $photoPath = $file->store('public/uploads');
                $validatedData['photo'] = $photoPath;
            }

            // Criando o cliente com os dados validados
            $client = Client::create($validatedData);

            Log::info('Cliente criado com sucesso.', ['client_id' => $client->id]);
            return response()->json($client, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao criar cliente', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao processar a requisição'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        Log::info('Iniciando atualização do cliente', ['client_id' => $id, 'request_data' => $request->all()]);

        $client = Client::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:clients,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:255',
            'celular' => 'sometimes|required|string|max:20',
            'bairro' => 'sometimes|required|string|max:255',
            'estado' => 'sometimes|required|string|max:255',
            'photo' => 'nullable|max:10240',
        ]);

        if ($validator->fails()) {
            Log::error('Erro de validação ao atualizar cliente', ['errors' => $validator->errors()]);
            return response()->json(['error' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();


           try {
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoPath = $file->store('public/uploads');
            $validatedData['photo'] = $photoPath;
            }

            $client->update($validatedData);

            Log::info('Cliente atualizado com sucesso no banco de dados', ['client_id' => $id, 'updated_data' => $client]);
            return response()->json($client);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cliente', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao processar a requisição'], 500);
        }
    }

    public function destroy($clientId)
    {
        Log::info('Iniciando a exclusão do cliente.', ['client_id' => $clientId]);
        try {
            $client = Client::findOrFail($clientId);

            if ($client->photo && Storage::disk('public')->exists($client->photo)) {
                // Deletar a foto do cliente
                Storage::disk('public')->delete($client->photo);
            }

            // Deletar o cliente do banco de dados
            $client->delete();

            Log::info('Cliente excluído com sucesso.', ['client_id' => $clientId]);

            // Retornar uma resposta 204 (No Content) indicando sucesso na exclusão
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Em caso de erro, registrar o erro e retornar uma resposta de erro 500
            Log::error('Erro ao excluir cliente.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao processar a requisição'], 500);
        }
    }

    private function handlePhotoDeletion($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
