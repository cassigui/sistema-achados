@extends('wf.account::layouts.mail')

@section('header')
<a href="{{ url('/') }}" target="_blank">
	<img style="max-height:70px;" src="{{ asset('site/images/icon.png') }}">
</a>
@endsection

@section('intro-h1')
@endsection

@section('intro-p')
@endsection

@section('content')
<p>
    <span style="font-weight: bold">
    Recebemos uma solicitação para redefinir a senha, aqui está o código para continuar o processo::
    </span>
    <h4>{{ $token }}</h4>
    </span style="font-weight: bold">
    Se você não solicitou essa redefinição, ignore este e-mail.
    </span>
</p>
<br>
@endsection

@section('outro')
@endsection

@section('notes')
@endsection

@section('footer')
<p>{{ $fantasy_name }}</p>
<p>
	<a href="{{ url('/') }}" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;color:#3869d4" target="_blank" >
		Acesse o site
	</a>
</p>
@endsection
