<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Test;
use AppBundle\Zap\ZapService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Zap\Zapv2;


/**
 * Class TestListener
 * @package AppBundle\EventListener
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TestListener implements EventSubscriberInterface
{
    protected $zapService;
    protected $toknStorage;

    public function __construct(ZapService $zapService, TokenStorage $tokenStorage)
    {
        $this->zapService = $zapService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array|void
     */
    public static function getSubscribedEvents()
    {
        return array(
          'app.test.post_create' => 'onCreate'
        );
    }

    public function onCreate(GenericEvent $event)
    {
        /** @var Test $subject */
        $subject = $event->getSubject();
        $subject->setSession($this->tokenStorage->getToken()->getUsername() . time());
        $subject->setStartDate(new \DateTime());
        $this->zapService->startNewScan($subject);

    }

} 