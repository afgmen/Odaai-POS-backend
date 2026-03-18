<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::with('currentOrder')->orderBy('table_number')->get();

        return response()->json($tables);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:tables',
            'capacity' => 'integer|min:1',
            'status' => 'in:available,occupied,reserved',
            'position_x' => 'nullable|integer',
            'position_y' => 'nullable|integer',
        ]);

        $table = Table::create($validated);

        return response()->json($table, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $table = Table::with(['currentOrder.items.product', 'orders'])->findOrFail($id);

        return response()->json($table);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $table = Table::findOrFail($id);

        $validated = $request->validate([
            'table_number' => 'sometimes|string|unique:tables,table_number,' . $id,
            'capacity' => 'integer|min:1',
            'status' => 'in:available,occupied,reserved',
            'position_x' => 'nullable|integer',
            'position_y' => 'nullable|integer',
        ]);

        $table->update($validated);

        return response()->json($table);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = Table::findOrFail($id);
        $table->delete();

        return response()->json(['message' => 'Table deleted successfully']);
    }
}
