<?php


namespace App\Mapper;

use App\Entity\Receipt;
use App\Model\ReceiptModel;

final class ReceiptMapper
{
    public static function entityToModel(Receipt $receipt): ReceiptModel
    {
        $receiptDto = new ReceiptModel();
        $receiptDto->setDescription($receipt->getDescription())
            ->setName($receipt->getName())
            ->setPrice($receipt->getPrice())
            ->setUnit($receipt->getUnit())
            ->setCategory($receipt->getCategory())
            ->setSeoTitle($receipt->getSeoTitle())
            ->setSeoDescription($receipt->getSeoDescription());

        return $receiptDto;
    }
}