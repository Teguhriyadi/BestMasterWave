<?php

namespace App\Http\Requests\Permissions;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama'        => ['required'],

            'akses'       => ['required'],

            'menu_id'     => ['required', 'exists:menu,id'],

            'tipe_akses'      => ['required', 'array', 'min:1'],

            'tipe_akses.*'    => ['required', 'string', Rule::in([
                'read',
                'create',
                'edit',
                'delete',
                'show',
                'change_status'
            ])],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->akses || !$this->menu_id || !is_array($this->tipe_akses)) {
                return;
            }

            foreach ($this->tipe_akses as $tipe) {
                $fullAkses = $this->akses . '.' . $tipe;

                $exists = Permission::where('akses', $fullAkses)
                    ->where('menu_id', $this->menu_id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add(
                        'tipe_akses',
                        "Permission '{$fullAkses}' sudah ada untuk menu ini."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'nama.required'        => 'Nama Permissions wajib diisi',
            'akses.required'       => 'Akses Permissions wajib diisi',
            'menu_id.required'     => 'Nama Menu wajib diisi',
            'menu_id.exists'       => 'Nama Menu tidak valid',
            'tipe_akses.required'  => 'Tipe Akses wajib dipilih minimal 1',
            'tipe_akses.array'     => 'Tipe Akses harus berupa array',
            'tipe_akses.min'       => 'Tipe Akses wajib dipilih minimal 1',
            'tipe_akses.*.required' => 'Tipe Akses tidak boleh kosong',
            'tipe_akses.*.in'      => 'Tipe Akses tidak valid',
        ];
    }
}
