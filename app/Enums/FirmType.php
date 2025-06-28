<?php

namespace App\Enums;

enum FirmType: string {
    case proprietorship = 'Proprietorship'; 
    case partnership = 'Partnership'; 
    case llp = 'Limited Liability Partnership'; 
    case pvtltd = 'Private Ltd'; 
    case inc = 'Incorporated'; 

    public function getLabel(): ?string
    {
        return $this->name;
    }
}