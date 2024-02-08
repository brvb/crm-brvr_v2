<div>
    <style>
        .dropdown-icon-notion{
            margin:0 7px 0 0
        }
        .icon-notion:active{
            transform: translateY(2px);
        }
        .icon-notion{
            position: relative;
            background: rgba(254, 99, 78, 0.05);
            border-radius: 1.25rem;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: none;
        }
        .icon-notion i{
            font-size:21px;
            color: #326c91;
            font-weight:bolder;
            padding: 0 0 0 5px;
        }
        .dropdown-icon-notion {
            margin: 0 7px 0 0;
            position: relative;
        }
        .overlay{
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
        }
        .texto-Anotacao{
            margin:10px 0 0 0;
            padding: 5px;
        }
        .texto-Anotacao:hover{
            background-color: rgb(215, 226, 250);
        }
        .dropdown-content {
            position: absolute;
            top: 100%;
            right: -50px !important;
            display: none;
            padding: 15px;
            width: 300px;
            border-radius:20px !important;
            height: 430px !important;
        }
        .options-container{
            width: 300px;
            background:#f0f1f1;
            padding: 10px;
            border-radius:20px !important;
            position:absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .container-buttons{
            margin-top:10px;
            width:100%;
            display:flex;
            justify-content: space-around;
        }
        .btns-edit button{
            width:46px;
        }
        #textoOpcoes{
            min-height: 100px;
        }
        .btn-navbar{
            padding: 10px 15px;
        }
        @media screen and (max-width: 767px) {
            .dropdown-content {
                left: 0;
            }
        }
        @media screen and (max-width: 576px) {
            .dropdown-content {
                left: -120px !important;
            }
        }
        @media screen and (min-width: 1401px) {
            .icon-notion{
                width: 56px;
                height: 56px;
            }
            .icon-notion i{
                font-size:22px;
            }
        }
    </style>
        <li class="nav-item dropdown-icon-notion">
            <div class="icon-notion" onclick="toggleDropdown(event)">
                <i class="fa fa-edit"></i>
            </div>
            <div id="dropdownAno" class="card dropdown-content" style="display: none;">
                <div>
                    <textarea id="textoSalvo" class="form-control" placeholder="Digite seu texto aqui..."></textarea>
                    <div class="container-buttons">
                        <button class="btn btn-primary btn-navbar" onclick="salvarTexto()" >Adicionar</button>
                        <button class="btn btn-danger delete-all-button btn-navbar" wire:click="excluirTodasAnotacoes">Excluir Todas</button>
                    </div>
                </div>
                <div id="anotacoesSalvas">
                    @if($this->hasAnotacoesSalvas())
                        @foreach($this->getAnotacoesSalvas() as $index => $annotations)
                            <div class="texto-Anotacao" data-ordem="{{ $index + 1 }}" onclick="exibirOpcoes('{{ $annotations->text }}','{{ $annotations->id }}')"
                                draggable="true" ondragstart="dragStart(event)" ondragover="allowDrop(event)">
                                {{ $annotations->text }}
                            </div>
                        @endforeach
                    @else
                    @endif
                </div>
                @if (session()->has('alert'))
                    <script>
                        alert("{{ session('alert') }}");
                    </script>
                @endif
            </div>
        </li>
        <div class="overlay" id="optionsOverlay">
            <div class="options-container" id="optionsContainer">
                <textarea id="textoOpcoes" class="form-control"></textarea>
                <div class="container-buttons btns-edit">
                    <button class="btn btn-danger btn-navbar" onclick="excluirAnotacao()"><i class="fa fa-trash"></i></button>
                    <button class="btn btn-primary edit-button btn-navbar" onclick="editarAnotacao()"><i class="fa fa-check"></i></button>
                    <button class="btn btn-secondary btn-navbar" onclick="fecharOpcoes()">X</button>
                </div>
            </div>
        </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('atualizarAnotacoes', function (message) {
            console.log(message);
            document.getElementById('textoSalvo').value = '';
            var dropdown = document.getElementById('dropdownAno');
            var optionsOverlay = document.getElementById('optionsOverlay');
            dropdown.style.display = 'block';
        });

        document.addEventListener('click', function (event) {
            var dropdown = document.getElementById('dropdownAno');
            var optionsOverlay = document.getElementById('optionsOverlay');
            var optionsContainer = document.getElementById('optionsContainer');

       
            if (!dropdown.contains(event.target) && !optionsOverlay.contains(event.target) && !optionsContainer.contains(event.target)) {
                dropdown.style.display = 'none';
                optionsOverlay.style.display = 'none';
            }
        });
        
        var optionsOverlay = document.getElementById('optionsOverlay');
        optionsOverlay.addEventListener('click', function (event) {
            // Impede a propagação do evento para que não seja capturado pelo document click
            event.stopPropagation();
        });
    });

    function toggleDropdown(event) {
        event.stopPropagation();
        
        var dropdown = document.getElementById('dropdownAno');

        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }
        var AnotacaoId = null;

        function exibirOpcoes(texto, id) {
            const optionsOverlay = document.getElementById('optionsOverlay');
            const textoOpcoes = document.getElementById('textoOpcoes');

            textoOpcoes.value = texto;
            AnotacaoId = id;;
            optionsOverlay.style.display = 'block';
        }

        function excluirAnotacao() {
            if (AnotacaoId !== null) {
                Livewire.emit('excluirAnotacaoviaID', AnotacaoId);
                AnotacaoId = null;
            } else {
                alert('ID da anotação não encontrado.',AnotacaoId);
            }
        }

        function editarAnotacao() {
            const texto = document.getElementById('textoOpcoes').value;

            if (AnotacaoId !== null && texto.trim() !== '') {
                Livewire.emit('editarAnotacao', AnotacaoId, texto);
                AnotacaoId = null;
            } else {
                alert('ID da anotação não encontrado ou o texto está vazio.');
            }
        }
        function salvarTexto() {
            const texto = document.getElementById('textoSalvo').value;
            if (texto.trim() !== '') {
                Livewire.emit('adicionarAnotacao', texto );
            } else {
                alert('Por favor, digite algo antes de salvar.');
            }
        }
        function fecharOpcoes() {
            const optionsOverlay = document.getElementById('optionsOverlay');
            optionsOverlay.style.display = 'none';
        }
    </script>
</div>