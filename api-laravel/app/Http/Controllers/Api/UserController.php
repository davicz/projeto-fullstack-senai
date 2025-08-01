<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Importe o modelo User
use App\Http\Resources\UserResource; // Importe seu API Resource
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Importe para usar os status code
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


// Supondo que você tenha uma classe de serviço. Se não, a lógica pode ir direto no controller.
// use App\Services\UserService;

class UserController extends Controller
{

    use AuthorizesRequests;
    // Se você estiver usando uma camada de serviço, injete-a no construtor.
    // protected $userService;
    // public function __construct(UserService $userService)
    // {
    //     $this->userService = $userService;
    // }

    /**
     * Exibe uma lista paginada de usuários.
     * GET /api/users
     */
    public function index(Request $request)
    {
        $query = User::query();

                // 2. Verificamos se um parâmetro 'search' foi enviado na URL.
        if ($request->has('search')) {
            $searchTerm = $request->input('search');

            // 3. Adicionamos a lógica de busca à nossa consulta.
            // Esta consulta busca o termo em múltiplas colunas: name, email e cpf.
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        // Busca todos os usuários, com paginação para não sobrecarregar a resposta.
        $users = $query->orderBy('name')->paginate(15);

        // Retorna uma coleção de usuários, formatada pelo UserResource.
        return UserResource::collection($users);
    }

    /**
     * Cria um novo usuário.
     * POST /api/users
     */
    public function store(Request $request)
    {
        // Valida os dados da requisição. Se a validação falhar, o Laravel retorna um erro 422 automaticamente.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // Adicione outras validações aqui (cpf, role, etc.)
        ]);

        // Cria o usuário. A senha é criptografada no Model através de um Mutator (boa prática).
        $user = User::create($validatedData);

        // Retorna o usuário recém-criado, formatado, com o status 201 Created.
        return new UserResource($user);
    }

    /**
     * Exibe um usuário específico.
     * GET /api/users/{user}
     */
    public function show(User $user) // <-- Isso é Route-Model Binding
    {
        // O Laravel já encontrou o usuário para nós. Se não encontrar, ele retorna um 404 automaticamente.
        // Não precisamos mais da linha: $user = $this->userService->findUserById($id);

        // Retorna o usuário encontrado, formatado pelo UserResource.
        return new UserResource($user);
    }

    /**
     * Atualiza um usuário existente.
     * PUT/PATCH /api/users/{user}
     */
    
    public function updateRole(Request $request, User $user)
    {
        // 1. Autorização: Verificamos se o usuário logado pode realizar esta ação.
        // Isso chama o método 'updateRole' que criamos na UserPolicy.
        // Se a política retornar 'false', o Laravel automaticamente retorna um erro 403 Forbidden.
        $this->authorize('updateRole', $user);

        // 2. Validação: Garantimos que o 'role_id' foi enviado e que ele existe na tabela 'roles'.
        $validated = $request->validate([
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        // 3. Ação: Atualizamos o perfil. O método sync() é perfeito para relações Many-to-Many.
        // Ele remove todos os perfis antigos e adiciona apenas o novo.
        $user->roles()->sync($validated['role_id']);

        // 4. Resposta: Retornamos o usuário atualizado, com o novo perfil carregado, formatado pelo resource.
        return new \App\Http\Resources\UserResource($user->load('roles'));
    }

    public function export(Request $request)
    {
        // Reutilizamos a mesma lógica de busca do método index()
        // para garantir que a exportação respeite os filtros.
        $query = User::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('cpf', 'like', '%' . $searchTerm . '%');
            });
        }

        $users = $query->orderBy('name')->get();

        // Define o nome do arquivo que será baixado
        $fileName = 'colaboradores.csv';

        // Define os cabeçalhos para o navegador entender que é um arquivo para download
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Cria o conteúdo do CSV
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Adiciona o cabeçalho do CSV
            fputcsv($file, ['ID', 'Nome Completo', 'Email', 'CPF']);

            // Adiciona os dados de cada usuário
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->cpf
                ]);
            }

            fclose($file);
        };

        // Retorna a resposta de streaming, que permite baixar o arquivo
        return response()->stream($callback, 200, $headers);
    }

    public function update(Request $request, User $user) // <-- Route-Model Binding aqui também
    {
        // Valida os dados. Note a regra de 'unique' para o email, que ignora o usuário atual.
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            // A validação de senha é opcional na atualização
        ]);

        // Atualiza os dados do usuário
        $user->update($validatedData);

        // Retorna o usuário atualizado.
        return new UserResource($user);
    }


    /**
     * Remove um usuário do banco de dados.
     * DELETE /api/users/{user}
     */
    public function destroy(User $user) // <-- Route-Model Binding novamente
    {
        // Deleta o usuário.
        $user->delete();

        // Retorna uma resposta vazia com o status 204 No Content, indicando sucesso.
        return response()->noContent();
    }
}