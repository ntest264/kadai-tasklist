<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task; 

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            $tasks = $user->task()->paginate();
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
         
            // タスク一覧ビューでそれを表示
        return view('tasks.index', $data);
    }
    
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'status'=>'required|max:10',
            'content'=>'required',
            ]);
        
        // 認証済みユーザ（閲覧者）のタスクとして作成（リクエストされた値をもとに作成）
        $request->user()->task()->create([
        'content' => $request->content,
        'status' => $request->status,
        ]);

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は、タスクを閲覧
         if (\Auth::id() === $task->user_id) {
        // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
         }
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
         // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を編集
         if (\Auth::id() === $task->user_id) {
    
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
        }
         // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function update(Request $request, $id)
    {
          $request->validate([
            'status'=>'required|max:10',
            'content'=>'required',
            ]);
         // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを更新
        $task->content = $request->content;
        $task->status =$request->status;
        $task->save();
         

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    public function destroy($id)
    {
          // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
          // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
         if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
