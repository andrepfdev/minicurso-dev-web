<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
        'email',
        'senha',
        'salario',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nome' => 'required|max_length[255]',
        'email' => 'required|valid_email|is_unique[usuarios.email,id,{id}]',
        'senha' => 'required|min_length[6]',
        'salario' => 'permit_empty|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [
        'nome' => [
            'required' => 'O campo nome é obrigatório.',
            'max_length' => 'O campo nome não pode ter mais de 255 caracteres.',
        ],
        'email' => [
            'required' => 'O campo email é obrigatório.',
            'valid_email' => 'O campo email deve ser um endereço de email válido.',
            'is_unique' => 'O email informado já está em uso.',
        ],
        'senha' => [
            'required' => 'O campo senha é obrigatório.',
            'min_length' => 'O campo senha deve ter pelo menos 6 caracteres.',
        ],
        'salario' => [
            'permit_empty' => 'O campo salário pode ser vazio.',
            'numeric' => 'O campo salário deve ser um número.',
            'greater_than_equal_to' => 'O campo salário deve ser maior ou igual a 0.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];    
}
