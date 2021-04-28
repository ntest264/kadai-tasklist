@extends('layouts.app')

@section('content')

<h1>タスク一覧</h1>

    @if (count($tasks) > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>タスク</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->content }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

@endif

    {{-- タスク作成ページへのリンク --}}
    {!! LINK_TO_ROUTE('tasks.create', '新規タスクの投稿', [], ['class' => 'btn btn-primary']) !!}


@endsection