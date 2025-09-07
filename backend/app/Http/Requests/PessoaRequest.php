<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ContatoTipo;
use App\Enums\DocumentoTipo;
use App\Enums\PessoaTipo;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->segment(2);
        $cpfCnpj = $this->input('pessoa.cpf_cnpj');
        $isPessoaFisica = $this->input('pessoa.pessoa_tipo_id') === PessoaTipo::Fisica;
        $contatos = $this->input('pessoa.contatos') ?? [];
        $documentos = $this->input('pessoa.documentos') ?? [];

        $rules = [
            'pessoa.pessoa_tipo_id' => ['required', 'exists:pessoa_tipo,id'],
            'pessoa.nome' => ['required', 'string', 'max:100'],
            'pessoa.pessoa_cor_id' => [
                'nullable',
                'integer',
                'exists:pessoa_cor,id',
            ],
            'pessoa.nacionalidade_id' => [
                'nullable',
                'integer',
                'exists:nacionalidade,id',
            ],
            'pessoa.nome_social' => [
                ...($isPessoaFisica ? ['nullable'] : ['required']),
                'string',
                'max:100',
            ],
            'pessoa.estado_civil_id' => [
                'exclude_if:pessoa.pessoa_tipo_id,2',
                'nullable',
                'exists:estado_civil,id',
            ],
            'pessoa.sexo_id' => [
                'exclude_if:pessoa.pessoa_tipo_id,2',
                'nullable',
                'exists:sexo,id',
            ],
            'pessoa.genero_id' => [
                'exclude_if:pessoa.pessoa_tipo_id,2',
                'nullable',
                'exists:genero,id',
            ],
            'pessoa.data_nascimento' => [
                'exclude_if:pessoa.pessoa_tipo_id,2',
                'nullable',
                'date',
            ],
            'pessoa.falecido' => [
                'exclude_if:pessoa.pessoa_tipo_id,2',
                'required',
                'boolean',
            ],
            'pessoa.cpf_cnpj' => [
                'required',
                Rule::unique('pessoa')
                    ->where('cpf_cnpj', $cpfCnpj)
                    ->ignore($id),
            ],
            'pessoa.necessidades_especiais_ids' => [
                'exclude_if:pessoa.pessoa_tipo_id,2',
                'array',
            ],
            'pessoa.necessidades_especiais_ids.*' => [
                'required',
                'integer',
                'exists:necessidade_especial,id',
            ],
            'pessoa.filiacao' => ['array'],
            'pessoa.filiacao.*.falecido' => ['required', 'boolean'],
            'pessoa.filiacao.*.nome' => ['required', 'string', 'max:150'],
            'pessoa.filiacao.*.parentesco_id' => [
                'required',
                'integer',
                'exists:parentesco,id',
            ],
            'pessoa.enderecos' => ['sometimes', 'required', 'array'],
            'pessoa.enderecos.*.cep' => ['required'],
            'pessoa.enderecos.*.logradouro' => ['required'],
            'pessoa.enderecos.*.sem_numero' => ['required', 'boolean'],
            'pessoa.enderecos.*.numero' => [
                'required_unless:pessoa.enderecos.*.sem_numero,true',
                'nullable',
                'numeric',
            ],
            'pessoa.enderecos.*.complemento' => ['nullable', 'string'],
            'pessoa.enderecos.*.bairro' => ['required', 'string'],
            'pessoa.enderecos.*.padrao' => ['boolean'],
            'pessoa.enderecos.*.cidade_id' => ['required', 'exists:cidade,id'],
            'pessoa.contatos' => ['sometimes', 'required', 'array'],
            'pessoa.contatos.*.contato_tipo_id' => ['required', 'exists:contato_tipo,id'],
            'pessoa.documentos' => ['sometimes', 'required', 'array'],
            'pessoa.documentos.*.conteudo' => ['sometimes', 'required', 'array'],
            'pessoa.documentos.*.documento_tipo_id' => ['required', 'exists:documento_tipo,id'],
            'pessoa.documentos.*.documento_situacao_id' => ['nullable', 'exists:documento_situacao,id'],
            'pessoa.documentos.*.file_id' => ['nullable', 'string'],
            'pessoa.documentos.*.id' => ['nullable', 'integer', 'exists:documento,id'],
        ];

        foreach ($contatos as $key => $contato) {
            $tipo = $contato['contato_tipo_id'];
            $name = "pessoa.contatos.$key.conteudo";

            switch ($tipo) {
                case ContatoTipo::Email:
                    $rules[$name] = ['required', 'email:rfc,filter,spoof,dns', 'max:100'];
                    break;
                case ContatoTipo::TelefoneFixo:
                case ContatoTipo::TelefoneComercial:
                    $rules[$name] = ['required', 'string', 'phone:landline'];
                    break;
                case ContatoTipo::Celular:
                    $rules[$name] = ['required', 'string', 'phone:cellphone'];
                    break;
                default:
                    break;
            }
        }

        foreach ($documentos as $key => $documento) {
            $documentoId = $documento['id'] ?? null;
            $tipo = $documento['documento_tipo_id'];
            $prefix = "pessoa.documentos.$key.conteudo";

	    /*
            $rules["pessoa.documentos.$key.documento_tipo_id"] = [
                Rule::unique('documento')->where('model_id', $id)->ignore($documentoId),
            ];
	    */

            switch ($tipo) {
                case DocumentoTipo::RG:
                    $rules["$prefix.rg"] = ['required', 'string'];
                    $rules["$prefix.orgao_emissor"] = ['nullable', 'string'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.estado_id"] = ['nullable', 'integer', 'exists:estado,id'];
                    $rules["$prefix.cidade_id"] = ['nullable', 'integer', 'exists:cidade,id'];
                    break;
                case DocumentoTipo::CNH:
                    $rules["$prefix.numero"] = ['required', 'string'];
                    $rules["$prefix.orgao_emissor"] = ['nullable', 'string'];
                    $rules["$prefix.categoria"] = ['nullable', 'string'];
                    $rules["$prefix.data_validade"] = ['nullable', 'date'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.data_primeira_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.estado_id"] = ['nullable', 'integer', 'exists:estado,id'];
                    $rules["$prefix.cidade_id"] = ['nullable', 'integer', 'exists:cidade,id'];
                    break;
                case DocumentoTipo::CN:
                    $rules["$prefix.matricula"] = ['nullable'];
                    $rules["$prefix.livro"] = ['nullable'];
                    $rules["$prefix.folha"] = ['nullable'];
                    $rules["$prefix.termo"] = ['nullable'];
                    $rules["$prefix.nome_cartorio"] = ['nullable'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.estado_id"] = ['nullable', 'exists:estado,id'];
                    $rules["$prefix.cidade_id"] = ['nullable', 'exists:cidade,id'];
                    break;
                case DocumentoTipo::CAM:
                    $rules["$prefix.ra"] = ['nullable'];
                    $rules["$prefix.rm"] = ['nullable'];
                    $rules["$prefix.csm"] = ['nullable'];
                    $rules["$prefix.categoria"] = ['nullable'];
                    $rules["$prefix.orgao_emissor"] = ['nullable'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.estado_id"] = ['nullable', 'exists:estado,id'];
                    $rules["$prefix.cidade_id"] = ['nullable', 'exists:cidade,id'];
                    break;
                case DocumentoTipo::CC:
                    $rules["$prefix.matricula"] = ['nullable'];
                    $rules["$prefix.livro"] = ['nullable'];
                    $rules["$prefix.folha"] = ['nullable'];
                    $rules["$prefix.termo"] = ['nullable'];
                    $rules["$prefix.nome_conjugue"] = ['nullable'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.estado_id"] = ['nullable', 'exists:estado,id'];
                    $rules["$prefix.cidade_id"] = ['nullable', 'exists:cidade,id'];
                    break;
                case DocumentoTipo::TE:
                    $rules["$prefix.numero"] = ['required', 'string'];
                    $rules["$prefix.zona"] = ['nullable', 'string'];
                    $rules["$prefix.secao"] = ['nullable', 'string'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.estado_id"] = ['nullable', 'integer', 'exists:estado,id'];
                    $rules["$prefix.cidade_id"] = ['nullable', 'integer', 'exists:cidade,id'];
                    break;
                case DocumentoTipo::CPF:
                    $rules["$prefix.numero"] = ['required', 'string', 'max:14'];
                    $rules["$prefix.nome"] = ['nullable', 'string', 'max:150'];
                    $rules["$prefix.data_nascimento"] = ['nullable', 'date', 'before:now'];
                    $rules["$prefix.data_emissao"] = ['nullable', 'date', 'before:now'];
                    break;
                case DocumentoTipo::CNPJ:
                    $rules["$prefix.numero"] = ['required', 'string', 'max:18'];
                    $rules["$prefix.razao_social"] = ['required', 'string', 'max:100'];
                    $rules["$prefix.nome_fantasia"] = ['nullable', 'string', 'max:100'];
                    $rules["$prefix.data_abertura"] = ['nullable', 'date', 'before:now'];
                    break;
                default:
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'pessoa.contatos.*.conteudo.phone' => 'Um ou mais números de telefone são inválidos.',
            'pessoa.contatos.*.conteudo.email' => 'Um ou mais endereços e-mail são inválidos.',
            'pessoa.documentos.*.documento_tipo_id.unique' => 'Já existe um documento deste tipo para esta pessoa.',
            'pessoa.cpf_cnpj.unique' => 'Já existe uma pessoa utilizando o mesmo CPF/CNPJ.',
        ];
    }
}
