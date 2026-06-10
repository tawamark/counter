<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request, AuditLogService $auditLogService): RedirectResponse
    {
        $data = $this->validateUser($request);

        $user = User::create([
            'company_id' => auth()->user()->company_id,
            ...$data,
        ]);

        $auditLogService->record(auth()->user(), 'usuarios', 'criou', 'Usuário cadastrado: '.$user->name, $user, [
            'email' => $user->email,
            'role' => $user->role,
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuário cadastrado com sucesso.');
    }

    public function edit(User $user): View
    {
        $this->authorizeUser($user);

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user, AuditLogService $auditLogService): RedirectResponse
    {
        $this->authorizeUser($user);

        $user->update($this->validateUser($request, $user));

        $auditLogService->record(auth()->user(), 'usuarios', 'atualizou', 'Usuário atualizado: '.$user->name, $user, [
            'email' => $user->email,
            'role' => $user->role,
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user, AuditLogService $auditLogService): RedirectResponse
    {
        $this->authorizeUser($user);

        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Não é possível excluir o usuário autenticado.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        $user->delete();

        $auditLogService->record(auth()->user(), 'usuarios', 'excluiu', 'Usuário excluído: '.$userName, null, [
            'email' => $userEmail,
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuário excluído com sucesso.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'role' => ['required', Rule::in(['admin', 'stockist', 'counter'])],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8', 'max:255'],
        ];

        $data = $request->validate($rules);

        if (($data['password'] ?? null) === null || $data['password'] === '') {
            unset($data['password']);
        }

        return $data;
    }

    private function authorizeUser(User $user): void
    {
        abort_unless($user->company_id === auth()->user()->company_id, 404);
    }
}
