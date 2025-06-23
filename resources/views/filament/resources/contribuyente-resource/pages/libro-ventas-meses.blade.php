<x-filament-panels::page>
    <!-- Información del Contribuyente -->
    <div style="margin-bottom: 1.5rem;">
        {{ $this->contribuyenteInfolist }}
    </div>

    <!-- Header con título y controles de vista -->
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; color: rgb(96, 165, 250, 1); margin-bottom: 0.5rem;">Libro de Ventas {{ $this->ano }}</h2>
            <p style="font-size: 1rem; color: #6b7280;">Selecciona el mes que deseas gestionar.</p>
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
        .month-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            color: inherit;
        }
        .month-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .month-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .month-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 1024px) {
            .month-grid { grid-template-columns: repeat(6, 1fr); }
        }
        .month-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .month-icons {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .month-icons { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .month-icons { grid-template-columns: repeat(3, 1fr); }
        }
    </style>

    <!-- Vista de Cuadrícula (Grid) -->
    @if($this->viewMode === 'grid')
        <div class="month-grid">
            @foreach($this->getMesesDelAño() as $periodo)
                <a href="{{ \App\Filament\Resources\ContribuyenteResource::getUrl('gestionar-facturas-mes', ['record' => $this->record, 'periodo' => $periodo->id]) }}" class="month-card">
                    <x-filament::section style="height: 100%; cursor: pointer;">
                        <div style="text-align: center; padding: 1.5rem 1rem;">
                            <!-- Icono del mes -->
                            <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                <x-entypo-book style="width: 2.5rem; height: 2.5rem; color: #10b981;" />
                            </div>

                            <!-- Nombre del mes -->
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: #5b6677; margin-bottom: 0.75rem; line-height: 1.2;">
                                {{ $periodo->nombre_mes }}
                            </h3>

                            <!-- Estado -->
                            <div style="margin-bottom: 0.75rem;">
                                @if($periodo->estado === 'abierto')
                                    <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                                        Abierto
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">
                                        Cerrado
                                    </span>
                                @endif
                            </div>

                            <!-- Información adicional -->
                            <p style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">
                                {{ $periodo->facturas->count() }} facturas
                            </p>
                        </div>
                    </x-filament::section>
                </a>
            @endforeach
        </div>
    @endif

    <!-- Vista de Lista -->
    @if($this->viewMode === 'list')
        <div class="month-list">
            @foreach($this->getMesesDelAño() as $periodo)
                <a href="{{ \App\Filament\Resources\ContribuyenteResource::getUrl('gestionar-facturas-mes', ['record' => $this->record, 'periodo' => $periodo->id]) }}" class="month-card">
                    <x-filament::section style="cursor: pointer;">
                        <div style="display: flex; align-items: center; padding: 1.25rem;">
                            <!-- Icono del mes -->
                            <div style="margin-right: 1rem;">
                                <x-entypo-book style="width: 2rem; height: 2rem; color: #10b981;" />
                            </div>

                            <!-- Contenido principal -->
                            <div style="flex: 1;">
                                <h3 style="font-size: 1.25rem; font-weight: 600; color: #5b6677; margin-bottom: 0.25rem;">
                                    {{ $periodo->nombre_mes }}
                                </h3>
                                <p style="font-size: 0.875rem; color: #6b7280; font-weight: 500;">
                                    {{ $periodo->facturas->count() }} facturas
                                </p>
                            </div>

                            <!-- Estado y flecha -->
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                @if($periodo->estado === 'abierto')
                                    <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                                        Abierto
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">
                                        Cerrado
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
        <div class="month-icons">
            @foreach($this->getMesesDelAño() as $periodo)
                <a href="{{ \App\Filament\Resources\ContribuyenteResource::getUrl('gestionar-facturas-mes', ['record' => $this->record, 'periodo' => $periodo->id]) }}" class="month-card">
                    <x-filament::section style="cursor: pointer;">
                        <div style="text-align: center; padding: 2.5rem 1.5rem;">
                            <!-- Icono grande del mes -->
                            <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
                                <x-entypo-book style="width: 4rem; height: 4rem; color: #10b981;" />
                            </div>

                            <!-- Nombre del mes -->
                            <h3 style="font-size: 1.75rem; font-weight: 700; color: #5b6677; margin-bottom: 1rem; line-height: 1.2;">
                                {{ $periodo->nombre_mes }}
                            </h3>

                            <!-- Estado -->
                            <div style="margin-bottom: 1rem;">
                                @if($periodo->estado === 'abierto')
                                    <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.875rem; font-weight: 600; background-color: #d1fae5; color: #065f46; border: 1px solid #10b981;">
                                        Abierto
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 1rem; font-size: 0.875rem; font-weight: 600; background-color: #f3f4f6; color: #374151; border: 1px solid #d1d5db;">
                                        Cerrado
                                    </span>
                                @endif
                            </div>

                            <!-- Información adicional -->
                            <p style="font-size: 1rem; color: #6b7280; font-weight: 500;">
                                {{ $periodo->facturas->count() }} facturas
                            </p>
                        </div>
                    </x-filament::section>
                </a>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
