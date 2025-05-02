@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: "Sucesso",
                text: "{{ session('success') }}",
                icon: "success"
            })
        });
    </script>
    {{-- <p class="alert-success">{{ session('success') }}</p> --}}
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: "Erro",
                text: "{{ session('error') }}",
                icon: "error"
            })
        });
    </script>
    {{-- <p class="alert-error">{{ session('error') }}</p> --}}
@endif

@if ($errors->any())
    @php
        $message = '';
        foreach ($errors->all() as $error) {
            $message.= $error.'<br/>';
        }
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                title: "Erro",
                html: "{!! $message !!}",
                icon: "error"
            })
        });
    </script>
    {{-- <div class="alert-error">
        @foreach ($errors->all() as $error)
            {{ $error }} <br/>
        @endforeach
    </div> --}}
@endif