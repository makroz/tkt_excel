<?php
namespace App\Repositories\Interfaces;

interface ICampaniaRepository {
    public function send($historyEmail=[]);
    public function totalXStatus($campania_id);
    function actualizaCampania($campania_id);
    public function hola($mensaje);
}
