<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Support\Facades\Session;

class CarritoService
{
    /**
     * Obtener el carrito actual desde la sesión
     */
    public function obtener(): array
    {
        return Session::get('carrito', []);
    }

    /**
     * Agregar un producto al carrito por ID
     */
    public function agregar(int $id): array
    {
        $producto = Producto::find($id);

        if (!$producto) {
            throw new \Exception('Producto no encontrado');
        }

        $carrito = $this->obtener();

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'cantidad' => 1
            ];
        }

        Session::put('carrito', $carrito);

        return $carrito;
    }

    /**
     * Eliminar un producto del carrito por ID
     */
    public function eliminar(int $id): void
    {
        $carrito = $this->obtener();

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            Session::put('carrito', $carrito);
        }
    }

    /**
     * Vaciar el carrito completamente
     */
    public function vaciar(): void
    {
        Session::forget('carrito');
    }

    /**
     * Obtener el total de productos en el carrito
     */
    public function contar(): int
    {
        return collect($this->obtener())->sum('cantidad');
    }

    /**
     * Calcular el total monetario del carrito
     */
    public function total(): float
    {
        return collect($this->obtener())->reduce(function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0.0);
    }
} 
