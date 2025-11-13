<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    // Tampilkan semua tugas
    public function index()
    {
        $tasks = Todo::orderBy('created_at', 'desc')->get();
        return view('home', compact('tasks'));
    }

    // Tambah tugas baru
    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Todo::create([
            'task' => $request->task,
            'description' => $request->description,
        ]);

        return redirect()->route('home')->with('success', 'Tugas berhasil ditambahkan!');
    }

    // Ubah status selesai / batal
    public function update($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->is_done = !$todo->is_done; // toggle status
        $todo->completed_at = $todo->is_done ? now() : null; // simpan waktu selesai jika true
        $todo->save();

        return redirect()->route('home')->with('success', 'Status tugas diperbarui.');
    }

    // Hapus tugas
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect()->route('home')->with('success', 'Tugas dihapus.');
    }
}
