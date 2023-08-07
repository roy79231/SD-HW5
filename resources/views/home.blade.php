@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                    <br>將在<span id="countdown">3</span>秒後會跳到首頁
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var seconds=3;
    var countdownElement = document.getElementById('countdown');

    function updateCountdown() {
        countdownElement.textContent = seconds;

        if (seconds === 0) {
            window.location.href = "{{ route('root') }}";
        } else {
            seconds--;
            setTimeout(updateCountdown, 1000); 
        }
    }

    updateCountdown();
</script>
@endsection

