@extends('layouts.default')

@section('page-title', 'Usuários')
@section('page-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary">Adicionar</a>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('users.index') }}" class="mb-3" method="GET" style="width: 400px">
        <div class="input-group input-group-sm">
            <input type="text" name="keyword" class="form-control" placeholder="Pesquisa por nome ou email" value="{{ request()?->keyword }}">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
        </div>

    </form>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Ação</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    {{ $users->links() }}
@endsection
