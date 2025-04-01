<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\LotacaoController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\PessoaEnderecoController;
use App\Http\Controllers\ServidorEfetivoController;
use App\Http\Controllers\ServidorTemporarioController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\UnidadeEnderecoController;
use Illuminate\Support\Facades\Route;

/** AUTENTICAÇÃO DE LOGIN **/
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){

    /** REFRESH TOKEN **/
    Route::post('/refresh', [AuthController::class, 'refresh']);

    /** LOGOFF API **/
    Route::post('/logout', [AuthController::class, 'logout']);

    /** ROTAS PARA CIDADE **/
    Route::get('/cidade', [CidadeController::class, 'index']);
    Route::get('/show-cidade/{cid_id}', [CidadeController::class, 'show']);
    Route::post('/store-cidade', [CidadeController::class, 'store']);
    Route::put('/update-cidade/{cid_id}', [CidadeController::class, 'update']);
    Route::delete('/delete-cidade/{cid_id}', [CidadeController::class, 'destroy']);

    /** ROTAS PARA ENDERECO **/
    Route::get('/endereco', [EnderecoController::class, 'index']);
    Route::get('/show-endereco/{end_id}', [EnderecoController::class, 'show']);
    Route::post('/store-endereco', [EnderecoController::class, 'store']);
    Route::put('/update-endereco/{end_id}', [EnderecoController::class, 'update']);
    Route::delete('/delete-endereco/{end_id}', [EnderecoController::class, 'destroy']);

    /** ROTAS PARA PESSOA **/
    Route::get('/pessoa', [PessoaController::class, 'index']);
    Route::get('/show-pessoa/{pes_id}', [PessoaController::class, 'show']);
    Route::post('/store-pessoa', [PessoaController::class, 'store']);
    Route::put('/update-pessoa/{pes_id}', [PessoaController::class, 'update']);
    Route::delete('/delete-pessoa/{pes_id}', [PessoaController::class, 'destroy']);

    /** ROTAS PARA ENDERECO DA PESSOA **/
    Route::get('/pessoa-endereco', [PessoaEnderecoController::class, 'index']);
    Route::get('/show-pessoa-endereco/{pes_id}', [PessoaEnderecoController::class, 'show']);
    Route::post('/store-pessoa-endereco', [PessoaEnderecoController::class, 'store']);
    Route::put('/update-pessoa-endereco/{pes_id}', [PessoaEnderecoController::class, 'update']);
    Route::delete('/delete-pessoa-endereco/{pes_id}', [PessoaEnderecoController::class, 'destroy']);

    /** ROTAS PARA FOTO **/
    Route::get('/foto-pessoa', [FotoController::class, 'index']);
    Route::get('/show-foto-pessoa/{pes_id}', [FotoController::class, 'show']);
    Route::post('/store-foto-pessoa/{pes_id}', [FotoController::class, 'store']);
    Route::put('/update-foto-pessoa/{pes_id}', [FotoController::class, 'update']);
    Route::delete('/delete-foto-pessoa/{pes_id}', [FotoController::class, 'destroy']);

    /** ROTAS PARA LOTACAO **/
    Route::get('/lotacao', [LotacaoController::class, 'index']);
    Route::get('/show-lotacao/{lot_id}', [LotacaoController::class, 'show']);
    Route::get('/consulta-unidade/{unid_id}', [LotacaoController::class, 'showByUnidade']);
    Route::post('/consulta-lotacao-nome/', [LotacaoController::class, 'showByNome']);
    Route::post('/store-lotacao', [LotacaoController::class, 'store']);
    Route::put('/update-lotacao/{lot_id}', [LotacaoController::class, 'update']);
    Route::delete('/delete-lotacao/{lot_id}', [LotacaoController::class, 'destroy']);
    
    /** ROTAS PARA UNIDADE **/
    Route::get('/unidade', [UnidadeController::class, 'index']);
    Route::get('/show-unidade/{unidade_id}', [UnidadeController::class, 'show']);
    Route::post('/store-unidade', [UnidadeController::class, 'store']);
    Route::put('/update-unidade/{unidade_id}', [UnidadeController::class, 'update']);
    Route::delete('/delete-unidade/{unidade_id}', [UnidadeController::class, 'destroy']);

    /** ROTAS PARA UNIDADE END **/
    Route::get('/unidade-endereco', [UnidadeEnderecoController::class, 'index']);
    Route::get('/show-unidade-endereco/{unid_id}', [UnidadeEnderecoController::class, 'show']);
    Route::post('/store-unidade-endereco', [UnidadeEnderecoController::class, 'store']);
    Route::put('/update-unidade-endereco/{unid_id}', [UnidadeEnderecoController::class, 'update']);
    Route::delete('/delete-unidade-endereco/{unid_id}', [UnidadeEnderecoController::class, 'destroy']);

    /** ROTAS PARA SERVIDOR EFETICO **/
    Route::get('/servidor-efetivo', [ServidorEfetivoController::class, 'index']);
    Route::get('/show-servidor-efetivo/{pes_id}', [ServidorEfetivoController::class, 'show']);
    Route::post('/store-cadastro-servidor-efetivo', [ServidorEfetivoController::class, 'cadastroServidorEfetivo']);
    Route::post('/store-servidor-efetivo', [ServidorEfetivoController::class, 'store']);
    Route::put('/update-servidor-efetivo/{pes_id}', [ServidorEfetivoController::class, 'update']);
    Route::delete('/delete-servidor-efetivo/{pes_id}', [ServidorEfetivoController::class, 'destroy']);

    /** ROTAS PARA SERVIDOR TEMP **/
    Route::get('/servidor-temporario', [ServidorTemporarioController::class, 'index']);
    Route::get('/show-servidor-temporario/{pes_id}', [ServidorTemporarioController::class, 'show']);
    Route::post('/store-servidor-temporario', [ServidorTemporarioController::class, 'store']);
    Route::put('/update-servidor-temporario/{pes_id}', [ServidorTemporarioController::class, 'update']);
    Route::delete('/delete-servidor-temporario/{pes_id}', [ServidorTemporarioController::class, 'destroy']);

});

