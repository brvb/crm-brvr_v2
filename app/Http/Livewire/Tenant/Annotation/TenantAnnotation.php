<?php

namespace App\Http\Livewire\Tenant\Annotation;

use Livewire\Component;
use App\Models\Anotacao;

class TenantAnnotation extends Component
{
    
    public $texto;
    public $anotacoesSalvas = [];
    protected $listeners = [
        "adicionarAnotacao" => 'adicionarAnotacao',
        "excluirAnotacaoviaID" => 'excluirAnotacaoviaID',
        "editarAnotacao" => 'editarAnotacao'
    ];

    public function adicionarAnotacao($texto)
    {
        if (!empty($texto)) {
            $userId = auth()->id();
    
            $ultimaOrdem = Anotacao::where('user_id', $userId)->max('ordem') ?? 0;
    
            Anotacao::create([
                'text' => $texto,
                'user_id' => $userId,
                'ordem' => $ultimaOrdem + 1,
            ]);
    
            $this->emit('atualizarAnotacoes', 'Anotação criada com sucesso');
        } else {
            session()->flash('alert', 'Por favor, digite algo antes de adicionar.');
        }
    }
    public $anotacaoId;

    public function excluirAnotacaoviaID($anotacaoId)
    {   
        logger("Vinicius - ID: " . $anotacaoId);
        if (!empty($anotacaoId)) {
            $userId = auth()->id();
            
            Anotacao::where('user_id', $userId)
                    ->where('id', $anotacaoId)
                    ->delete();

            $this->emit('atualizarAnotacoes', 'Anotação foi excluída com sucesso');
        } else {
            logger("Erro com o ID: " . $anotacaoId);
        }
    }


    public function excluirTodasAnotacoes()
    {
        $userId = auth()->id();
        // Em seguida, exclua as anotações
        Anotacao::where('user_id', $userId)->delete();

        $this->emit('atualizarAnotacoes', 'Todas as anotações foram excluídas com sucesso');
    }
    public function editarAnotacao($anotacaoId, $novoTexto)
{
    if (!empty($anotacaoId)) {
        $userId = auth()->id();

        $anotacao = Anotacao::where('user_id', $userId)
            ->where('id', $anotacaoId)
            ->first();

        if ($anotacao) {
            $anotacao->text = $novoTexto;
            $anotacao->save();

            $this->emit('atualizarAnotacoes', 'Anotação foi editada com sucesso');
        } else {
            session()->flash('alert', 'Anotação não encontrada.');
        }
    } else {
        session()->flash('alert', 'ID da anotação não encontrado.');
    }
}


    public function getAnotacoesSalvas()
    {
        $userId = auth()->id();

        return Anotacao::where('user_id', $userId)->get();
    }
    public function hasAnotacoesSalvas()
    {

        $userId = auth()->id();

        return Anotacao::where('user_id', $userId)->exists();
    }

    public function render()
    {
        return view('livewire.tenant-annotation');
    }
}
