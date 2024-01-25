<x-tenant-layout title="Adicionar Intervenção" :themeAction="$themeAction">
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Intervenção') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ __('Adicionar') }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $task->reference }}</a></li>
            </ol>
        </div>
        <div class="default-tab">
            @livewire('tenant.tasks-reports.edit-tasks-reports', ['task' => $task])
        </div>
    </div>
</x-tenant-layout>
<script>



function initializeDrawing(canvasId, signatureType) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('touchstart', startDrawing);

        canvas.addEventListener('mousemove', (e) => draw(e, canvas));
        canvas.addEventListener('touchmove', (e) => drawTouch(e, canvas));

        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            draw(e);
        }

        function draw(e) {
            if (!isDrawing) return;

            e.preventDefault();

            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || e.touches[0].clientX) - rect.left;
            const y = (e.clientY || e.touches[0].clientY) - rect.top;

            drawLine(x, y);
        }

        function drawTouch(e) {
            if (!isDrawing) return;

            e.preventDefault();

            const rect = canvas.getBoundingClientRect();
            const x = e.touches[0].clientX - rect.left;
            const y = e.touches[0].clientY - rect.top;

            drawLine(x, y);
        }

        function drawLine(x, y) {
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#000';

            ctx.lineTo((x * canvas.width) / canvas.clientWidth, (y * canvas.height) / canvas.clientHeight);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo((x * canvas.width) / canvas.clientWidth, (y * canvas.height) / canvas.clientHeight);
        }

        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath();
            saveSignature(signatureType);
        }

        


        function saveSignature(type) {
            var image = new Image();
            image.src = canvas.toDataURL('image/png');

            var imageURL = image.src;

            Livewire.emit("signaturePads",imageURL,canvas.id);
            console.log(`Link da imagem da assinatura ${type}:`, imageURL);
        }
    }
    function clearSignature(canvasId, signatureType) {
            const canvas = document.getElementById(canvasId);
            const ctx = canvas.getContext('2d');

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.beginPath();  // Reinicia o contexto após limpar

            var image = new Image();
            image.src = canvas.toDataURL('image/png');
            var imageURL = image.src;
            
            Livewire.emit("signaturePadsClear",imageURL,canvas.id);
    }
    initializeDrawing('signature-pad', 'Técnico');
    initializeDrawing('signature-pad-cliente', 'Cliente');


</script>
