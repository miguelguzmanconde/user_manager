<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;

class UserCrudController extends Controller {

    public function list(Request $request): Response {

        return Inertia::render('User/List');

    }

    public function table(Request $request) {

        $params = [
            'items' => [],
            'total' => 0
        ];

        $query = $request->all();
        $items = null;

        if (array_key_exists('sortBy', $query) && isset($query['sortBy']))
            foreach ($query['sortBy'] as $key => $item)
                $items = !isset($items) ?
                    User::orderBy($item['key'], $item['order']) :
                    $items->orderBy($item['key'], $item['order']);

        $items = !isset($items) ?
            User::orderBy('id', 'asc') :
            $items->orderBy('id', 'asc');

        $limit = $query['itemsPerPage'];
        $offset = ($query['page'] - 1) * $limit;

        $items = $items->limit($limit)->offset($offset);

        $params['items'] = json_decode(json_encode($items->get()));
        $params['total'] = User::count();

        return response()->json($params);

    }

    public function create(): Response {

        return Inertia::render('User/Form');

    }

    public function edit($id): Response {

        return Inertia::render('User/Form', ['user' => User::find($id)]);

    }

    public function store(UserCreateRequest $request): RedirectResponse {

        $validated = $request->validated();
        User::create($validated);
        return redirect()->intended(route('user.list', absolute: false));

    }

    public function update(UserUpdateRequest $request): RedirectResponse {

        $validated = $request->validated();

        $user = User::find($validated['id']);

        if (isset($user))
            if ($user->id === $request->user()->id) {
                if (stripos($request->user()->email, $validated['email']) !== false)
                    unset($validated['email']);
                $request->user()->fill($validated);
                $request->user()->save();
            } else {
                if (stripos($user->email, $validated['email']) !== false)
                    unset($validated['email']);
                $user->fill($validated);
                $user->save();
            }

        return back();

    }

    public function password(PasswordRequest $request): RedirectResponse {

        $validated = $request->validated();

        $user = User::find($validated['id']);

        if (isset($user))
            if ($user->id === $request->user()->id) {
                $request->user()->update([
                    'password' => Hash::make($validated['password']),
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }

        return back();

    }

    public function destroy(DeleteRequest $request): RedirectResponse {

        $validated = $request->validated();

        $user = User::find($validated['id']);

        if (isset($user))
            if ($user->id === $request->user()->id) {

                $user = $request->user();
                Auth::logout();
                $user->delete();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return Redirect::to(route('login'));

            } else {

                $user->delete();

            }

        return Redirect::to(route('user.list'));

    }

}