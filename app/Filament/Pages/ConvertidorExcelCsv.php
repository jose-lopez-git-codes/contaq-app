<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ConvertidorExcelCsv extends Page implements HasForms
{
    use InteractsWithForms;
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static string $view = 'filament.pages.convertidor-excel-csv';
    protected static ?string $navigationLabel = 'Convertidor Excel → CSV';
    protected static ?string $title = 'Convertidor Excel a CSV';
    protected static ?string $navigationGroup = 'Herramientas';
    protected static ?int $navigationSort = 99;

    public ?array $data = [];
    public ?string $archivoConvertido = null;
    public ?string $nombreOriginal = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Convertir Excel a CSV')
                    ->description('Sube un archivo Excel (.xls o .xlsx) para convertirlo a CSV con formato UTF-8')
                    ->schema([
                        FileUpload::make('archivo_excel')
                            ->label('Archivo Excel')
                            ->acceptedFileTypes([
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/excel',
                            ])
                            ->maxSize(10240) // 10MB max
                            ->directory('excel-conversiones')
                            ->visibility('private')
                            ->required()
                            ->helperText('Formatos soportados: .xls, .xlsx (máximo 10MB)')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('convertir')
                ->label('Convertir a CSV')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->action(function () {
                    // Primero validar que hay un archivo
                    if (empty($this->data['archivo_excel'])) {
                        Notification::make()
                            ->title('Archivo requerido')
                            ->body('Por favor selecciona un archivo Excel antes de convertir.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $this->convertirArchivo();
                })
                ->requiresConfirmation()
                ->modalHeading('¿Convertir archivo?')
                ->modalDescription('Se convertirá el archivo Excel a formato CSV con codificación UTF-8.')
                ->modalSubmitActionLabel('Sí, convertir'),
        ];
    }

    public function convertirArchivo(): void
    {
        try {
            $archivoData = $this->data['archivo_excel'];

            if (!$archivoData) {
                throw new \Exception('No se ha seleccionado ningún archivo.');
            }

            // Manejar el TemporaryUploadedFile de Livewire
            $archivo = null;
            if (is_array($archivoData)) {
                // Obtener el primer archivo del array
                $firstKey = array_key_first($archivoData);
                $temporaryFile = $archivoData[$firstKey];

                if ($temporaryFile instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $archivo = $temporaryFile->getRealPath();
                    $nombreOriginal = $temporaryFile->getClientOriginalName();
                }
            }

            if (!$archivo || !file_exists($archivo)) {
                throw new \Exception('El archivo no se pudo procesar correctamente.');
            }

            // Guardar nombre original para el CSV (sin extensión)
            $nombreOriginal = pathinfo($nombreOriginal, PATHINFO_FILENAME);
            $this->nombreOriginal = $nombreOriginal;

            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($archivo);

            // Configurar el escritor CSV
            $writer = IOFactory::createWriter($spreadsheet, 'Csv');

            if ($writer instanceof \PhpOffice\PhpSpreadsheet\Writer\Csv) {
                $writer->setUseBOM(true); // Para UTF-8
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setLineEnding("\r\n");
                $writer->setSheetIndex(0); // Solo la primera hoja
            }

            // Generar nombre único para el CSV
            $nombreLimpio = Str::slug($nombreOriginal);
            $nombreCsv = $nombreLimpio . '-' . now()->format('Ymd-His') . '.csv';
            $rutaCsv = 'csv-convertidos/' . $nombreCsv;

            // Crear directorio si no existe
            Storage::makeDirectory('csv-convertidos');

            // Guardar el CSV
            $rutaCompletaCsv = Storage::path($rutaCsv);
            $writer->save($rutaCompletaCsv);

            // Guardar la ruta para descargar
            $this->archivoConvertido = $rutaCsv;

            // Limpiar el formulario
            $this->data['archivo_excel'] = null;
            $this->form->fill();

            Notification::make()
                ->title('¡Conversión exitosa!')
                ->body("El archivo '{$nombreOriginal}' se convirtió correctamente a CSV.")
                ->success()
                ->duration(5000)
                ->send();

        } catch (\Throwable $e) {
            // Log del error para debugging
            Log::error('Error convirtiendo Excel a CSV:', [
                'mensaje' => $e->getMessage(),
                'archivo_data' => $this->data['archivo_excel'] ?? 'sin archivo',
                'linea' => $e->getLine(),
            ]);

            Notification::make()
                ->title('Error en la conversión')
                ->body('No se pudo convertir el archivo: ' . $e->getMessage())
                ->danger()
                ->duration(8000)
                ->send();
        }
    }

    public function descargarCsv()
    {
        if (!$this->archivoConvertido || !Storage::exists($this->archivoConvertido)) {
            Notification::make()
                ->title('Archivo no encontrado')
                ->body('El archivo CSV ya no está disponible.')
                ->warning()
                ->send();
            return;
        }

        $nombreDescarga = ($this->nombreOriginal ?? 'archivo') . '.csv';

        return response()->download(
            Storage::path($this->archivoConvertido),
            $nombreDescarga,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $nombreDescarga . '"',
            ]
        );
    }

    public function limpiarArchivos(): void
    {
        if ($this->archivoConvertido && Storage::exists($this->archivoConvertido)) {
            Storage::delete($this->archivoConvertido);
        }

        $this->archivoConvertido = null;
        $this->nombreOriginal = null;

        Notification::make()
            ->title('Archivos limpiados')
            ->body('Se eliminó el archivo CSV generado.')
            ->success()
            ->send();
    }
}
