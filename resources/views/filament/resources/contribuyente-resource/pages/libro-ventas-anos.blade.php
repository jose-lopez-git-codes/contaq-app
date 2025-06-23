<x-filament-panels::page>
    <!-- Información del Contribuyente -->
    <div style="margin-bottom: 1.5rem;">
        {{ $this->contribuyenteInfolist }}
    </div>

    <!-- Header con título y controles de vista -->
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: rgb(96, 165, 250, 1); margin-bottom: 0.5rem;">Selecciona el año a gestionar</h2>
            <p style="font-size: 1rem; color: #6b7280;">Elige un año existente o crea uno nuevo para gestionar el libro de ventas.</p>
        </div>

        <!-- Controles de vista -->
        <div style="display: flex; gap: 0.5rem; background-color: #f9fafb; border-radius: 0.75rem; padding: 0.5rem; border: 1px solid #e5e7eb;">
            <button
                wire:click="setViewMode('grid')"
                style="display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s ease; {{ $this->viewMode === 'grid' ? 'background-color: #ffffff; color: rgb(96, 165, 250, 1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);' : 'color: #6b7280; background-color: transparent;' }}"
                title="Vista de cuadrícula"
            >
                <x-heroicon-o-squares-2x2 style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" />
                Cuadrícula
            </button>

            <button
                wire:click="setViewMode('list')"
                style="display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s ease; {{ $this->viewMode === 'list' ? 'background-color: #ffffff; color: rgb(96, 165, 250, 1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);' : 'color: #6b7280; background-color: transparent;' }}"
                title="Vista de lista"
            >
                <x-heroicon-o-list-bullet style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" />
                Lista
            </button>

            <button
                wire:click="setViewMode('icons')"
                style="display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s ease; {{ $this->viewMode === 'icons' ? 'background-color: #ffffff; color: rgb(96, 165, 250, 1); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);' : 'color: #6b7280; background-color: transparent;' }}"
                title="Vista de iconos grandes"
            >
                <x-heroicon-o-squares-plus style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" />
                Iconos
            </button>
        </div>
    </div>

    <style>
        .year-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: inherit;
        }
        .year-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .year-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .year-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 1024px) {
            .year-grid { grid-template-columns: repeat(6, 1fr); }
        }
        .year-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .year-icons {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .year-icons { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .year-icons { grid-template-columns: repeat(4, 1fr); }
        }
    </style>

    @if($this->getAñosDisponibles()->count() > 0)
        <!-- Vista de Cuadrícula (Grid) -->
        @if($this->viewMode === 'grid')
            <div class="year-grid">
                @foreach($this->getAñosDisponibles() as $año)
                    <a href="{{ \App\Filament\Resources\ContribuyenteResource::getUrl('libro-ventas-meses', ['record' => $this->record, 'ano' => $año]) }}" class="year-card">
                        <x-filament::section style="height: 100%; cursor: pointer;">
                            <div style="text-align: center; padding: 1.5rem 1rem;">
                                <!-- Icono del año -->
                                <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                    <x-heroicon-o-folder style="width: 2.5rem; height: 2.5rem; color: #10b981;" />
                                </div>

                                <!-- Año -->
                                <h3 style="font-size: 1.5rem; font-weight: 700; color: #5b6677; margin-bottom: 0.75rem; line-height: 1.2;">
                                    {{ $año }}
                                </h3>

                                <!-- Información adicional -->
                                <p style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">
                                    {{ \App\Models\LibroVentasPeriodo::where('contribuyente_id', $this->record->id)->where('año', $año)->count() }} meses
                                </p>
                            </div>
                        </x-filament::section>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Vista de Lista -->
        @if($this->viewMode === 'list')
            <div class="year-list">
                @foreach($this->getAñosDisponibles() as $año)
                    <a href="{{ \App\Filament\Resources\ContribuyenteResource::getUrl('libro-ventas-meses', ['record' => $this->record, 'ano' => $año]) }}" class="year-card">
                        <x-filament::section style="cursor: pointer;">
                            <div style="display: flex; align-items: center; padding: 1.25rem;">
                                <!-- Icono del año -->
                                <div style="margin-right: 1rem;">
                                    <x-heroicon-o-folder style="width: 2rem; height: 2rem; color: #10b981;" />
                                </div>

                                <!-- Contenido principal -->
                                <div style="flex: 1;">
                                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #5b6677; margin-bottom: 0.25rem;">
                                        Año {{ $año }}
                                    </h3>
                                    <p style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">
                                        {{ \App\Models\LibroVentasPeriodo::where('contribuyente_id', $this->record->id)->where('año', $año)->count() }} meses creados
                                    </p>
                                </div>

                                <!-- Estado y flecha -->
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    @php
                                        $mesesCount = \App\Models\LibroVentasPeriodo::where('contribuyente_id', $this->record->id)->where('año', $año)->count();
                                    @endphp

                                    @if($mesesCount === 12)
                                        <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                                            Completo
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;">
                                            Parcial
                                        </span>
                                    @endif

                                    <x-heroicon-o-chevron-right style="width: 1.25rem; height: 1.25rem; color: #9ca3af;" />
                                </div>
                            </div>
                        </x-filament::section>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Vista de Iconos Grandes -->
        @if($this->viewMode === 'icons')
            <div class="year-icons">
                @foreach($this->getAñosDisponibles() as $año)
                    <a href="{{ \App\Filament\Resources\ContribuyenteResource::getUrl('libro-ventas-meses', ['record' => $this->record, 'ano' => $año]) }}" class="year-card">
                        <x-filament::section style="cursor: pointer;">
                            <div style="text-align: center; padding: 2.5rem 1.5rem;">
                                <!-- Icono grande del año -->
                                <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
                                    <x-heroicon-o-folder style="width: 4rem; height: 4rem; color: #10b981;" />
                                </div>

                                <!-- Año -->
                                <h3 style="font-size: 2rem; font-weight: 700; color: #5b6677; margin-bottom: 1rem; line-height: 1.2;">
                                    {{ $año }}
                                </h3>

                                <!-- Estado -->
                                <div style="margin-bottom: 1rem;">
                                    @php
                                        $mesesCount = \App\Models\LibroVentasPeriodo::where('contribuyente_id', $this->record->id)->where('año', $año)->count();
                                    @endphp

                                    @if($mesesCount === 12)
                                        <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.875rem; font-weight: 600; background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                                            Año Completo
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.875rem; font-weight: 600; background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b;">
                                            Parcial
                                        </span>
                                    @endif
                                </div>

                                <!-- Información adicional -->
                                <p style="font-size: 1rem; color: #6b7280; font-weight: 500;">
                                    {{ $mesesCount }} de 12 meses
                                </p>
                            </div>
                        </x-filament::section>
                    </a>
                @endforeach
            </div>
        @endif
    @else
        <!-- Estado vacío -->
        <div style="text-align: center; padding: 3rem 0;">
            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                <x-heroicon-o-folder style="width: 2.5rem; height: 2.5rem; color: #9ca3af;" />
            </div>
            <h3 style="font-size: 1rem; font-weight: 500; color: #1f2937; margin-bottom: 0.5rem;">No hay años contables creados</h3>
            <p style="font-size: 0.875rem; color: #6b7280;">Comienza creando un año contable para gestionar el libro de ventas.</p>
        </div>
    @endif
</x-filament-panels::page>
