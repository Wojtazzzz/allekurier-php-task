<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Core\User\Domain\Exception\UserAlreadyExistsException;
use App\Core\User\Domain\Exception\UserNotFoundException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Psr\EventDispatcher\EventDispatcherInterface;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly EventDispatcherInterface $eventDispatcher
	) {}

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->getUserByEmail($email);

        if (null === $user) {
            throw new UserNotFoundException('Użytkownik nie istnieje');
        }

        return $user;
    }

	/**
	 * @throws NonUniqueResultException
	 */
	public function save(User $user): void
	{
		$exists = (bool) $this->getUserByEmail($user->getEmail());

		if ($exists) {
			throw new UserAlreadyExistsException('Podany adres e-mail jest zajęty');
		}

		$this->entityManager->persist($user);

		$events = $user->pullEvents();
		foreach ($events as $event) {
			$this->eventDispatcher->dispatch($event);
		}
	}

	public function flush(): void
	{
		$this->entityManager->flush();
	}

	/**
	 * @throws NonUniqueResultException
	 */
	private function getUserByEmail(string $email): User|null
	{
		return $this->entityManager->createQueryBuilder()
			->select('u')
			->from(User::class, 'u')
			->where('u.email = :user_email')
			->setParameter(':user_email', $email)
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
	}

	public function getByActive(bool $isActive): array
	{
		return $this->entityManager->createQueryBuilder()
			->select('u')
			->from(User::class, 'u')
			->where('u.isActive = :is_active')
			->setParameter(':is_active', $isActive)
			->getQuery()
			->getResult();
	}
}
