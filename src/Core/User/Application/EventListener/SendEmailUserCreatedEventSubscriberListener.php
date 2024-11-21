<?php

namespace App\Core\User\Application\EventListener;

use App\Common\Mailer\MailerInterface;
use App\Core\User\Domain\Event\UserCreatedEvent;

class SendEmailUserCreatedEventSubscriberListener
{
	public function __construct(private readonly MailerInterface $mailer)
	{
	}

	public function send(UserCreatedEvent $event): void
	{
		$this->mailer->send(
			$event->user->getEmail(),
			'Rejestracja konta',
			'Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h'
		);
	}

	public static function getSubscribedEvents(): array
	{
		return [
			UserCreatedEvent::class => 'send'
		];
	}
}