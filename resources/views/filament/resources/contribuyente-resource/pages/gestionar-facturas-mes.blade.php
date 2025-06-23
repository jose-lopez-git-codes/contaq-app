<x-filament-panels::page>
    <!-- Información del Período -->
    <div class="mb-6">
        {{ $this->periodoInfolist }}
    </div>

    <!-- Estadísticas rápidas del mes -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-2xl font-bold text-blue-600">
                    {{ $this->periodo->facturas->count() }}
                </div>
                <p class="text-sm text-gray-600">Total Facturas</p>
            </div>
        </x-filament::section>

        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-2xl font-bold text-green-600">
                    Q{{ number_format($this->periodo->facturas->sum('total_documento'), 2) }}
                </div>
                <p class="text-sm text-gray-600">Total Ventas</p>
            </div>
        </x-filament::section>

        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-2xl font-bold text-purple-600">
                    Q{{ number_format($this->periodo->facturas->sum('iva_debito_fiscal'), 2) }}
                </div>
                <p class="text-sm text-gray-600">Total IVA</p>
            </div>
        </x-filament::section>

        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-2xl font-bold text-yellow-600">
                    {{ $this->periodo->facturas->where('estado', 'Vigente')->count() }}
                </div>
                <p class="text-sm text-gray-600">Facturas Activas</p>
            </div>
        </x-filament::section>
    </div>

    <!-- Tabla de facturas -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                Facturas de {{ $this->periodo->nombre_mes }} {{ $this->periodo->año }}
            </h3>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
