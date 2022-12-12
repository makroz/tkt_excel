<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Repositories\EstudianteRepository;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class EstudianteExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithColumnFormatting
{
  protected $repository;

  public function __construct(EstudianteRepository $repository)
  {
    $this->repository = $repository;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    $data = array(
      "sorted" => request('sorted', 'DESC'),
      "eventos_id" => session('eventos_id'),
      "tipo" => "E"
    );
    return $this->repository->search($data);
  }
  public function headings(): array
  {
    return [
      'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno', 'Cargo', 'Organización', 'Profesión', 'País', 'Departamento', 'Email', 'Email 2', 'Celular', 'Grupo	Registrado', 'FechRegistro'
    ];
  }
  public function map($e): array
  {
    return [
      $e->dni_doc,
      $e->nombres,
      $e->ap_paterno,
      $e->ap_materno,
      $e->cargo,
      $e->organizacion,
      $e->profesion,
      $e->pais,
      $e->region,
      $e->email,
      $e->email_labor,
      $e->celular,
      $e->grupo,
      Date::dateTimeToExcel($e->created_at),
    ];
  }
  public function columnFormats(): array
  {
    return [
      'N' => "yyyy-mm-dd HH:mm:ss",
    ];
  }
}