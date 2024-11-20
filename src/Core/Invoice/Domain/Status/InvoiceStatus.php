<?php

namespace App\Core\Invoice\Domain\Status;

use App\Core\Invoice\Domain\Exception\InvalidInvoiceStatusException;

enum InvoiceStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELED = 'canceled';

	public static function fromName(string $name): InvoiceStatus
	{
		foreach (self::cases() as $status) {
			if ($name === $status->value) {
				return $status;
			}
		}

		throw new InvalidInvoiceStatusException("Status {$name} jest niepoprawny");
	}
}
