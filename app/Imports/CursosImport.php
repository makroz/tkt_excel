<?php

namespace App\Imports;

use App\Models\Curso;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

use Illuminate\Validation\Rule;#
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;#
use Maatwebsite\Excel\Concerns\WithValidation;#
use Maatwebsite\Excel\Concerns\WithUpsertColumns;

// de esta forma
//use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};

//class CursosImport implements ToModel
class CursosImport implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

  

    public function uniqueBy()
    {
        return 'cod_curso';
    }

    public function upsertColumns()
    {
        return ['nom_curso','modalidad','fech_ini','fech_fin','evento_id','tpo'];
    }

    public function rules(): array
    {
        return [
            'cod_usuario' => Rule::in(['patrick@maatwebsite.nl']),
        ];
    }

    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return ['cod_usuario' => 'email'];
    }

    public function model(array $row)
    {
        
        return new Curso([
            'cod_curso'     => mb_strtoupper($row['cod_curso']),
            'nom_curso'     => mb_strtoupper($row['nom_curso']),
            'modalidad'     => mb_strtoupper($row['modalidad']),
            'evento_id'     => $row['evento_id'] ?? $row['evento'] ?? session('eventos_id'),
            'tpo'           => $row['tpo'] ?? $row['tipo'] ?? 1,
            'fech_ini'      => $this->transformDate($row['fech_fin'])->format('d/m/Y'),
            'fech_fin'      => $this->transformDate($row['fech_fin'])->format('d/m/Y'),
            //'fech_ini'      => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fech_ini']),
        ]);
    }

}
