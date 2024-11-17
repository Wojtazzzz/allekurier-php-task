<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

	/**
	 * @ORM\Column(type="boolean", options={"default": false})
	 */
	private string $isActive;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private string $email;

    public function __construct(string $email, bool $isActive = false)
    {
        $this->id = null;
        $this->email = $email;
        $this->isActive = $isActive;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

	public function getIsActive(): bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $isActive): void
	{
		$this->isActive = $isActive;
	}
}
