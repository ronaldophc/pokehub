@extends('errors.layout')

@section('sprite', 'https://img.pokemondb.net/sprites/red-blue/normal/slowpoke.png')
@section('code', '419')
@section('title', 'Sessão expirada')
@section('message', 'Sua sessão expirou. Recarregue a página e tente novamente.')
@section('href', 'javascript:location.reload()')
@section('action', 'Recarregar')
