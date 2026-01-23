<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PlotingInterfaces
{
    public function plotingDosen();
    public function getAllFinalisasi();
    public function getDataById($id);
    public function updateData(Request $request, $id);
    public function finalisasi(Request $request);
}
