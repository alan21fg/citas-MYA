<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre_servicio' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
            'productos' => 'array', // Valida que productos sea un array
            'productos.*.id_producto' => 'required|exists:productos,id', // Cada producto debe existir en la tabla productos
            'productos.*.cantidad' => 'required|integer|min:1', // Cantidad mínima de 1
        ];
    }
}
