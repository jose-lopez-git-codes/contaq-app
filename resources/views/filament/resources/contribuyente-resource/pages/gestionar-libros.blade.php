<x-filament-panels::page>
    <!-- Información del Contribuyente -->
    <div class="mb-6">
        {{ $this->contribuyenteInfolist }}
    </div>

    <!-- Menú de opciones usando componentes de Filament -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Libro de Compras -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center">
                    <x-filament::icon
                        icon="heroicon-o-document-text"
                        class="h-6 w-6 text-primary-600 mr-2"
                    />
                    Libro de Compras
                </div>
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    Gestiona las facturas de compras y gastos del contribuyente.
                </p>

                <x-filament::button
                    color="primary"
                    icon="heroicon-o-plus"
                >
                    Gestionar Compras
                </x-filament::button>
            </div>
        </x-filament::section>

        <!-- Libro de Ventas -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center">
                    <x-filament::icon
                        icon="heroicon-o-currency-dollar"
                        class="h-6 w-6 mr-2"
                        style="color: #059669;"
                    />
                    Libro de Ventas
                </div>
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    Registra las facturas de ventas e ingresos del contribuyente.
                </p>

                <x-filament::button
                    color="success"
                    icon="heroicon-o-plus"
                    tag="a"
                    href="/admin/contribuyentes/{{ $this->record->id }}/libro-ventas"
                >
                    Gestionar Ventas
                </x-filament::button>
            </div>
        </x-filament::section>

        <!-- Facturas -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center">
                    <x-filament::icon
                        icon="heroicon-o-document-duplicate"
                        class="h-6 w-6 mr-2"
                        style="color: #7c3aed;"
                    />
                    Todas las Facturas
                </div>
            </x-slot>

            <div class="space-y-4">
                <p class="text-sm text-gray-600">
                    Vista general de todas las facturas del contribuyente.
                </p>

                <x-filament::button
                    color="warning"
                    icon="heroicon-o-eye"
                >
                    Ver Facturas
                </x-filament::button>
            </div>
        </x-filament::section>
    </div>

    <!-- Estadísticas usando Stats de Filament -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-3xl font-bold" style="color: #2563eb;">0</div>
                <p class="text-sm text-gray-600">Compras este mes</p>
            </div>
        </x-filament::section>

        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-3xl font-bold" style="color: #059669;">0</div>
                <p class="text-sm text-gray-600">Ventas este mes</p>
            </div>
        </x-filament::section>

        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-3xl font-bold" style="color: #7c3aed;">$0</div>
                <p class="text-sm text-gray-600">Total facturas</p>
            </div>
        </x-filament::section>

        <x-filament::section class="text-center">
            <div class="space-y-2">
                <div class="text-3xl font-bold" style="color: #d97706;">$0</div>
                <p class="text-sm text-gray-600">Impuestos por pagar</p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
