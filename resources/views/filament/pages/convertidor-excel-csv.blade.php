<x-filament-panels::page>
    <!-- Formulario de carga -->
    <div class="mb-6">
        <form wire:submit="convertirArchivo">
            {{ $this->form }}
        </form>
    </div>

    <!-- Área de resultado -->
    @if($archivoConvertido)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center">
                    <x-filament::icon
                        icon="heroicon-o-check-circle"
                        class="h-6 w-6 text-success-600 mr-2"
                    />
                    Conversión Completada
                </div>
            </x-slot>

            <div class="space-y-4">
                <div class="bg-success-50 border border-success-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-success-800">
                                Archivo CSV listo para descargar
                            </h4>
                            <p class="text-sm text-success-600 mt-1">
                                {{ $nombreOriginal ?? 'archivo' }}.csv
                            </p>
                            <p class="text-xs text-success-500 mt-1">
                                Codificación: UTF-8 • Separador: coma (,)
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <x-filament::button
                                color="success"
                                icon="heroicon-o-arrow-down-tray"
                                wire:click="descargarCsv"
                            >
                                Descargar CSV
                            </x-filament::button>

                            <x-filament::button
                                color="gray"
                                icon="heroicon-o-trash"
                                wire:click="limpiarArchivos"
                                outlined
                            >
                                Limpiar
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>
    @endif

    <!-- Información de ayuda -->
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center">
                <x-filament::icon
                    icon="heroicon-o-information-circle"
                    class="h-5 w-5 text-gray-500 mr-2"
                />
                <span class="text-gray-700 font-semibold">Información</span>
            </div>
        </x-slot>

        <div class="prose prose-sm max-w-none text-gray-600">
            <ul class="space-y-2">
                <li class="flex items-start">
                    <x-filament::icon
                        icon="heroicon-o-document-text"
                        class="h-4 w-4 mt-0.5 mr-2"
                        style="color: #3b82f6;" {{-- text-blue-500 fallback --}}
                    />
                    <span>Formatos soportados: <strong style="color: #3b82f6; font-weight: 600;">.xls</strong> y <strong style="color: #3b82f6; font-weight: 600;">.xlsx</strong></span>
                </li>
                <li class="flex items-start">
                    <x-filament::icon
                        icon="heroicon-o-language"
                        class="h-4 w-4 mt-0.5 mr-2"
                        style="color: #22c55e;" {{-- text-green-500 fallback --}}
                    />
                    <span>El CSV generado usa codificación <strong style="color: #22c55e; font-weight: 600;">UTF-8</strong> para caracteres especiales</span>
                </li>
                <li class="flex items-start">
                    <x-filament::icon
                        icon="heroicon-o-table-cells"
                        class="h-4 w-4 mt-0.5 mr-2"
                        style="color: #a21caf;" {{-- text-purple-500 fallback --}}
                    />
                    <span>Solo se convierte la <strong style="color: #a21caf; font-weight: 600;">primera hoja</strong> del archivo Excel</span>
                </li>
                <li class="flex items-start">
                    <x-filament::icon
                        icon="heroicon-o-shield-check"
                        class="h-4 w-4 mt-0.5 mr-2"
                        style="color: #eab308;" {{-- text-yellow-500 fallback --}}
                    />
                    <span>Tamaño máximo: <strong style="color: #eab308; font-weight: 600;">10 MB</strong></span>
                </li>
            </ul>
        </div>
    </x-filament::section>

    <!-- Scripts para mejorar UX -->
    <script>
        // Auto-limpiar archivos después de 10 minutos
        @if($archivoConvertido)
            setTimeout(() => {
                @this.call('limpiarArchivos');
            }, 600000); // 10 minutos
        @endif
    </script>
</x-filament-panels::page>
