<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PlotingInterfaces
{
    public function plotingDosen();
    public function getAllFinalisasi();
    public function finalisasi(Request $request);
}
