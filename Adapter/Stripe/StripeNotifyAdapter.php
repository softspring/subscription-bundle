<?php

namespace Softspring\SubscriptionBundle\Adapter\Stripe;

use Softspring\SubscriptionBundle\PlatformInterface;
use Softspring\SubscriptionBundle\Adapter\NotifyAdapterInterface;
use Softspring\SubscriptionBundle\Event\NotifyEvent;
use Stripe\Error\SignatureVerification;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Request;

class StripeNotifyAdapter extends AbstractStripeAdapter implements NotifyAdapterInterface
{
    /**
     * @param Request $request
     * @return NotifyEvent
     * @throws SignatureVerification
     */
    public function createEvent(Request $request): NotifyEvent
    {
        $this->initStripe();

        try {
            if (null === ($payload = json_decode($request->getContent(), true))) {
                throw new \UnexpectedValueException('Bad JSON body from Stripe!');
            }
            $sig_header = $request->server->get('HTTP_STRIPE_SIGNATURE');
            $event = Webhook::constructEvent($payload, $sig_header, $this->webhookSigningSecret);
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            throw $e;
        } catch(SignatureVerification $e) {
            // Invalid signature
            throw $e;
        }

        return new NotifyEvent(PlatformInterface::PLATFORM_STRIPE, $event->type, $event);
    }
}
