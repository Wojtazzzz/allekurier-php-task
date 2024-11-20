<?php

namespace App\Core\Invoice\Application\Command\CreateInvoice;

use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\User\Domain\Exception\UserIsInactiveException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateInvoiceHandler
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(CreateInvoiceCommand $command): void
    {
		$user = $this->userRepository->getByEmail($command->email);

		if (false === $user->getIsActive()) {
			throw new UserIsInactiveException('Podany użytkownik jest nieaktywny');
		}

        $this->invoiceRepository->save(new Invoice(
			$user,
			$command->amount
		));

        $this->invoiceRepository->flush();
    }
}
