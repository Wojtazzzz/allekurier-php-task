<?php

namespace App\Core\Invoice\Application\Query\GetInvoicesByStatusAndAmountGreater;

use App\Core\Invoice\Domain\Status\InvoiceStatus;

class GetInvoicesByStatusAndAmountGreaterQuery
{
	public readonly InvoiceStatus $status;
    public function __construct(
		string $status,
		public readonly int $amount
	) {
		$this->status = InvoiceStatus::fromName($status);
	}
}
