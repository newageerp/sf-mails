<?php
namespace Newageerp\SfMail\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfMail\Event\SfMailBeforeLoadEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route(path="/app/nae-core/mails")
 */
class MailController extends OaBaseController
{
    /**
     * @Route(path="/getData")
     * @OA\Post (operationId="NAEmailsGetData")
     */
    public function getData(Request $request): Response
    {
        $output = [];

        try {
            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $request = $this->transformJsonBody($request);
            $id = $request->get('id');
            $schema = $request->get('schema');
            $current = $request->get('current');

            if ($schema) {
                $className = $this->convertSchemaToEntity($schema);

                $repository = $this->em->getRepository($className);
                $entity = $repository->find($id);

                $event = new SfMailBeforeLoadEvent(
                    $request,
                    $user,
                    $entity,
                    $current,
                    $output
                );
                $this->eventDispatcher->dispatch($event, SfMailBeforeLoadEvent::NAME);

                $output = $event->getData();
            }

            if (isset($output['recipientsSuggest']) && count($output['recipientsSuggest']) > 0 && !isset($output['recipients'])) {
                $output['recipients'] = $output['recipientsSuggest'][0]['email'];
            }
        } catch (\Exception $e) {
        }
        return $this->json($output);
    }
}