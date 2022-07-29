<?php

namespace Leandroferreirama\PagamentoCnab240\Entities;

class DataFile
{
    public $file_header;
    public $bacth_header;
    public $detail = [];
    public $batch_trailer;
    public $file_trailer;
}
