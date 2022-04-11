<?php
namespace Newageerp\SfMail\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfMail\Event\SfMailBeforeLoadEvent;
use Newageerp\SfSocket\Event\SocketSendPoolEvent;
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

    /**
     * @Route(path="/send")
     * @OA\Post (operationId="NAEmailsSend")
     */
    public function sendEmail(Request $request)
    {
        try {
            $request = $this->transformJsonBody($request);

            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $subject = $request->get('subject');
            $recipient = explode(',', str_replace([' ', ';'], ['', ','], $request->get('recipients')));
            $content = $request->get('content');
            $files = $request->get('files');

            $extraData = $request->get('extraData');

            $type = isset($extraData['type']) ? $extraData['type'] : '';
            $parentId = isset($extraData['id']) ? $extraData['id'] : 0;
            $parentSchema = isset($extraData['schema']) ? $extraData['schema'] : 0;

            $event = new SocketSendPoolEvent();
            $this->eventDispatcher->dispatch($event, SocketSendPoolEvent::NAME);

            return $this->json(['success' => 1]);
        } catch (\Exception $e) {
            $response = $this->json([
                'description' => $e->getMessage(),
                'f' => $e->getFile(),
                'l' => $e->getLine()

            ]);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }
    }
}