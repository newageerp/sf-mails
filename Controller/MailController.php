<?php

namespace Newageerp\SfMail\Controller;

use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfMail\Event\SfMailBeforeLoadEvent;
use Newageerp\SfMail\Event\SfMailBeforeSendEvent;
use Newageerp\SfMail\Service\IMailSendService;
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
     * @Route(path="/getData", methods={"POST"})
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
                    $output,
                    $schema,
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
     * @Route(path="/send", methods={"POST"})
     * @OA\Post (operationId="NAEmailsSend")
     */
    public function sendEmail(Request $request, IMailSendService $mailSendService)
    {
        try {
            $request = $this->transformJsonBody($request);

            if (!($user = $this->findUser($request))) {
                throw new \Exception('Invalid user');
            }

            $subject = $request->get('subject');
            $recipients = explode(',', str_replace([' ', ';'], ['', ','], $request->get('recipients')));
            $content = $request->get('content');
            $files = $request->get('files');

            $extraData = $request->get('extraData');

            $type = $extraData['type'] ?? '';
            $parentId = $extraData['id'] ?? 0;
            $parentSchema = $extraData['schema'] ?? 0;

            $event = new SfMailBeforeSendEvent(
                '',
                $user->getEmail(),
                $user
            );
            $this->eventDispatcher->dispatch($event, SfMailBeforeSendEvent::NAME);

            $mailSendService->prepareMail(
                $event->getFromName(),
                $event->getFromEmail() ,
                $subject,
                $content,
                $recipients,
                $files,
                $user,
                $parentId,
                $parentSchema,
                $type,
            );

            $this->sendSocketPool();

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