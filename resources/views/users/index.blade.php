@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="my-4">Data Users</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Create</a>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $key => $user)
            <tr>
                <td>{{ $key++ + 1 }}</td>
                <td>{{ $user->nama_lengkap }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->iduser) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('users.destroy', $user->iduser) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Data tidak ada</td>
                                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<script>
    function saveCurrentUrl() {
        // Simpan URL saat ini ke dalam localStorage
        localStorage.setItem('redirect_url', window.location.href);
    }
</script>
@endsection
